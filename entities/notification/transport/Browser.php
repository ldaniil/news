<?php
/**
 * Created by PhpStorm.
 * User: Sony
 * Date: 26.01.2017
 * Time: 11:58
 */

namespace app\entities\notification\transport;

use yii\base\Exception;
use app\entities\Notification;
use app\entities\notification\TransportInterface;
use app\entities\notification\recipient\BrowserInterface;
use app\entities\notification\RecipientInterface;
use app\models\NotificationModel;

class Browser  implements TransportInterface
{
	/**
	 * @inheritdoc
	 */
	public function send(Notification $notification, RecipientInterface $recipient)
	{
		if ($recipient instanceof BrowserInterface) {
			$notificationModel = new NotificationModel();
			$notificationModel->setAttributes([
				'user_id' => $recipient->getUserId(),
				'message' => $notification->message
			]);
			return $notificationModel->save();
		} else {
			throw new Exception(get_class($recipient) . ' - неверный объект получателя уведомления');
		}
	}
}