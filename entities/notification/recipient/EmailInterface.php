<?php

namespace app\entities\notification\recipient;

use app\entities\notification\RecipientInterface;

interface EmailInterface extends RecipientInterface
{
	/**
	 * @return string
	 */
	public function getEmail();
}