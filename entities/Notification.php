<?php

namespace app\entities;
use app\entities\notification\TransportInterface;

/**
 * Class Notification
 *
 * @package app\entities
 */
class Notification
{
	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $message;

	/**
	 * Возвращает типы способов получения
	 *
	 * @return array
	 */
	public static function getTransportTypes()
	{
		return [
			TransportInterface::TYPE_EMAIL,
			TransportInterface::TYPE_BROWSER,
		];
	}

	/**
	 * Установить название
	 *
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Устанавливает сообщение
	 *
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->message = $message;

		return $this;
	}
}