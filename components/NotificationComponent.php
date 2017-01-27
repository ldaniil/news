<?php

namespace app\components;

use app\entities\Notification;
use app\entities\notification\RecipientInterface;
use yii\base\Component;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\ErrorException;
use app\entities\notification\transport\Email as EmailTransport;
use app\entities\notification\transport\Browser as BrowserTransport;
use app\entities\notification\TransportInterface;
use app\entities\notification\SenderInterface;
use app\models\NotificationSettingModel;
use app\models\User;
use yii\base\Exception;

class NotificationComponent extends Component implements BootstrapInterface
{
	public function bootstrap($app)
	{

	}

	public function init()
	{
		$settings = NotificationSettingModel::find()->all();

		foreach ($settings as $setting) {
			if (!empty($setting->routes)) {
				Event::on(
					$setting->model,
					$setting->event,
					function ($event) use ($setting) {
						$this->send($event->sender, $setting);
					}
				);
			}
		}
	}

	/**
	 * Отправляет уведомление
	 *
	 * @param SenderInterface          $sender
	 * @param NotificationSettingModel $setting
	 *
	 * @return bool
	 * @throws ErrorException
	 * @throws Exception
	 */
	public function send(SenderInterface $sender, NotificationSettingModel $setting)
	{
		if ((!$sender instanceof SenderInterface)) {
			throw new ErrorException(
				get_class($sender)
				. ' должен поддерживать интерфейс '
				. SenderInterface::class
			);
		}

		$success = false;

		// Обрабатываем правила маршрутизации уведомления
		foreach ($setting->routes as $route) {
			if (!empty($route['transports'])) {
				// Список получателей полученный согласно правилам маршрута
				$recipients = $this->getRecipients($route);

				if ($recipients) {
					// Отправляем уведомление согласно способу получения
					foreach ($route['transports'] as $transportType) {
						$transport = $this->buildTransport($transportType);

						// Отправляем уведомление каждому получателю
						foreach ($recipients as $recipient) {
							$notification = (new Notification())
								->setTitle($setting->title)
								->setMessage($setting->message);
							$this->prepareNotification($notification, $sender, $recipient);
							$transport->send($notification, $recipient);
						}

						$success = true;
					}
				}
			}
		}

		return $success;
	}

	/**
	 * Подготавливает уведомление к отправке
	 *
	 * @param Notification    $notification
	 * @param SenderInterface $sender
	 */
	protected function prepareNotification(Notification $notification, SenderInterface $sender, RecipientInterface $recipient)
	{
		$templateVariables = $sender->getNotificationTemplateVariables();
		$templateVariables['recipient.name'] = $recipient->getName();

		$notification->title = str_replace(array_keys($templateVariables), $templateVariables, $notification->title);
		$notification->message = str_replace(array_keys($templateVariables), $templateVariables, $notification->message);
	}

	/**
	 * Возвращает объект доставки уведомления
	 *
	 * @param $type
	 *
	 * @return TransportInterface
	 * @throws Exception
	 */
	protected function buildTransport($type)
	{
		switch ($type) {
			case TransportInterface::TYPE_EMAIL:
				return new EmailTransport();
				break;
			case TransportInterface::TYPE_BROWSER:
				return new BrowserTransport();
				break;
			default:
				throw new Exception($type . ' - неверный способ получения уведомления');
		}
	}

	/**
	 * Возвращает получателей уведомления согласно маршрутизации
	 *
	 * @return array|User[]
	 */
	protected function getRecipients($route)
	{
		$query = User::find();

		if ($route['enable']) {
			$query->byRole($route['role']);
			if ($route['exclude']) {
				$query->byEmail($route['exclude'], true);
			}
		} else {
			if ($route['exclude']) {
				$query->byEmail($route['exclude']);
			} else {
				return [];
			}
		}

		return $query->byActive()->all();
	}
}