<?php

namespace App\Conditions\Operators\Adapters;

use App\Contracts\Conditions\LogicOperator;
use App\Contracts\Conditions\Operator;
use App\Contracts\Conditions\Operatorable;



final class LogicOperatorAdapter implements Operator
{

	/**
	 * @var Operatorable|LogicOperator
	 */
	protected $operator;

	/**
	 * @var string
	 */
	protected $variable;



	/**
	 * @param LogicOperator $operator
	 * @param string        $variable
	 */
	public function __construct(LogicOperator $operator, string $variable)
	{
		$this->operator = $operator;
		$this->variable = $variable;
	}



	/**
	 * @return bool
	 */
	public function evaluate(): bool
	{
		return $this->operator->evaluate(
			$this->variable
		);
	}
}
