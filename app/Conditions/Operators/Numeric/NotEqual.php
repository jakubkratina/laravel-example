<?php

namespace App\Conditions\Operators\Numeric;

use App\Contracts\Conditions\NumericOperator;
use App\Contracts\Conditions\Operatorable;



final class NotEqual implements NumericOperator, Operatorable
{

	/**
	 * {@inheritdoc}
	 */
	public function evaluate(int $variable, int $value): bool
	{
		return $variable !== $value;
	}
}
