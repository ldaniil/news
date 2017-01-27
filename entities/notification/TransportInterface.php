<?php
/**
 * Created by PhpStorm.
 * User: Sony
 * Date: 26.01.2017
 * Time: 0:04
 */

namespace app\entities\notification;

use app\entities\Notification;

interface TransportInterface
{
	const TYPE_EMAIL = 'email';

	const TYPE_BROWSER = 'browser';

	/**
	 * Отправляет уведомление получателю
	 *
	 * @param Notification $notification
	 * @param RecipientInterface $recipient
	 * @return bool
	 */
	public function send(Notification $notification, RecipientInterface $recipient);
}