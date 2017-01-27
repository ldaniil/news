<?php

namespace app\entities\notification\transport;

use yii;
use yii\base\Exception;
use app\entities\Notification;
use app\entities\notification\TransportInterface;
use app\entities\notification\RecipientInterface;
use app\entities\notification\recipient\EmailInterface;

class Email implements TransportInterface
{
	/**
	 * @inheritdoc
	 */
	public function send(Notification $notification, RecipientInterface $recipient)
	{
		if ($recipient instanceof EmailInterface) {
			return (bool)Yii::$app->mailer
				->compose('notification', ['message' => $notification->message])
				->setFrom(Yii::$app->params['email']['noreply'])
				->setTo($recipient->email)
				->setSubject($notification->title)
				->send();
		} else {
			throw new Exception(get_class($recipient) . ' - неверный объект получателя уведомления');
		}
	}
}