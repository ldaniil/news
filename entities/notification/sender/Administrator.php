<?php

namespace app\entities\notification\sender;

use app\entities\notification\SenderInterface as SenderInterface;

class Administrator implements SenderInterface
{
	/**
	 * @return array
	 */
	public function getNotificationTemplateVariables()
	{
		return [];
	}
}