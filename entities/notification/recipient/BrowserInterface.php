<?php

namespace app\entities\notification\recipient;

use app\entities\notification\RecipientInterface;

interface BrowserInterface extends RecipientInterface
{
	/**
	 * @return string
	 */
	public function getUserId();
}