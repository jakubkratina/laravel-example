<?php

namespace App\Conditions\Operators\String;

use App\Contracts\Conditions\Operatorable;
use App\Contracts\Conditions\StringOperator;
use Illuminate\Support\Str;



class NotContains implements StringOperator, Operatorable
{

	/**
	 * {@inheritdoc}
	 */
	public function evaluate(string $variable, string $value): bool
	{
		return Str::contains($variable, $value) === false;
	}
}
