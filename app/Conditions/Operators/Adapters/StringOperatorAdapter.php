<?php

namespace App\Conditions\Operators\Adapters;

use App\Contracts\Conditions\Operator;
use App\Contracts\Conditions\Operatorable;
use App\Contracts\Conditions\StringOperator;



final class StringOperatorAdapter implements Operator
{

	/**
	 * @var Operatorable|StringOperator
	 */
	protected $operator;

	/**
	 * @var string
	 */
	protected $variable;

	/**
	 * @var int
	 */
	protected $value;



	/**
	 * @param StringOperator $operator
	 * @param string         $variable
	 * @param string         $value
	 */
	public function __construct(StringOperator $operator, string $variable, string $value)
	{
		$this->operator = $operator;
		$this->variable = $variable;
		$this->value = $value;
	}



	/**
	 * @return bool
	 */
	public function evaluate(): bool
	{
		return $this->operator->evaluate(
			$this->variable, $this->value
		);
	}
}
