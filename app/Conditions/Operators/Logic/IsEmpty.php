<?php

namespace App\Conditions\Operators\Logic;

use App\Contracts\Conditions\LogicOperator;
use App\Contracts\Conditions\Operatorable;



final class IsEmpty implements LogicOperator, Operatorable
{

	/**
	 * {@inheritdoc}
	 */
	public function evaluate(string $variable): bool
	{
		return empty(trim($variable)) === true;
	}
}
