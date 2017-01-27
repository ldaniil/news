<?php

namespace app\entities\notification;

use app\entities\Notification;

interface SenderInterface
{
	/**
	 * Возвращает значение переменных используемых в шаблоне уведомления
	 *
	 * @param string $event
	 *
	 * @return Notification
	 */
	public function getNotificationTemplateVariables();
}