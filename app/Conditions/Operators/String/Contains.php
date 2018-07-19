<?php

namespace App\Conditions\Operators\String;

use App\Contracts\Conditions\Operatorable;
use App\Contracts\Conditions\StringOperator;
use Illuminate\Support\Str;



class Contains implements StringOperator, Operatorable
{

	/**
	 * {@inheritdoc}
	 */
	public function evaluate(string $variable, string $value): bool
	{
		return Str::contains($variable, $value);
	}
}
