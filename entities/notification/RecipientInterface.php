<?php
/**
 * Created by PhpStorm.
 * User: Sony
 * Date: 26.01.2017
 * Time: 0:07
 */

namespace app\entities\notification;

interface RecipientInterface
{
	/**
	 * Возвращает имя
	 *
	 * @return string
	 */
	public function getName();
}