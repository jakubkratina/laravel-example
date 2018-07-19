<?php

namespace App\Conditions\Operators\Adapters;

use App\Contracts\Conditions\NumericOperator;
use App\Contracts\Conditions\Operator;
use App\Contracts\Conditions\Operatorable;



final class NumericOperatorAdapter implements Operator
{

	/**
	 * @var Operatorable|NumericOperator
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
	 * @param NumericOperator $operator
	 * @param int             $variable
	 * @param int             $value
	 */
	public function __construct(NumericOperator $operator, int $variable, int $value)
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
