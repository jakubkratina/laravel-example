<?php

namespace App\Conditions;

use App\App;
use App\Conditions\Operators\Adapters\LogicOperatorAdapter;
use App\Conditions\Operators\Adapters\NumericOperatorAdapter;
use App\Conditions\Operators\Adapters\StringOperatorAdapter;
use App\Contracts\Conditions\LogicOperator;
use App\Contracts\Conditions\NumericOperator;
use App\Contracts\Conditions\Operator;
use App\Contracts\Conditions\Operatorable;
use App\Contracts\Conditions\StringOperator;
use App\Exceptions\Conditions\UnknownAdapterException;
use App\Exceptions\Conditions\UnknownOperatorException;
use Illuminate\Support\Str;



final class Condition
{

	/**
	 * @var Operatorable|LogicOperator|NumericOperator|StringOperator
	 */
	protected $operator;

	/**
	 * @var string|int
	 */
	protected $variable;

	/**
	 * @var string|int|null
	 */
	protected $value;



	/**
	 * @param string          $operator
	 * @param int|string      $variable
	 * @param int|string|null $value
	 * @return bool
	 */
	public static function evaluate(string $operator, $variable, $value = null): bool
	{
		return (new Condition())
			->using($operator, $variable, $value)
			->process();
	}



	/**
	 * @param string          $operator
	 * @param int|string      $variable
	 * @param int|string|null $value
	 * @return self
	 * @throws UnknownOperatorException
	 */
	public function using(string $operator, $variable, $value): self
	{
		$this->operator = App::operatorClassFor($operator);
		$this->variable = $variable;
		$this->value = $value;

		return $this;
	}



	/**
	 * @return bool
	 * @throws UnknownAdapterException
	 */
	public function process(): bool
	{
		$method = 'get' . $this->resolveAdapterName($this->operator) . 'Adapter';

		if (method_exists($this, $method)) {
			return $this->$method()->evaluate();
		}

		throw new UnknownAdapterException($method);
	}



	/**
	 * It returns an adapter name based on the last directory
	 * used in the namespace according to PSR-4.
	 *
	 * App\Conditions\Operators\Numeric\Equal -> Numeric
	 *
	 * @param Operatorable $operator
	 * @return string
	 */
	protected function resolveAdapterName(Operatorable $operator): string
	{
		$class = rtrim(Str::replaceFirst(class_basename($operator), '', get_class($operator)), '\\');

		return substr($class, strrpos($class, '\\') + 1);
	}



	/**
	 * @return Operator
	 */
	protected function getLogicAdapter(): Operator
	{
		return new LogicOperatorAdapter(
			$this->operator, (string) $this->variable
		);
	}



	/**
	 * @return Operator
	 */
	protected function getNumericAdapter(): Operator
	{
		return new NumericOperatorAdapter(
			$this->operator, (int) $this->variable, (int) $this->value
		);
	}



	/**
	 * @return Operator
	 */
	protected function getStringAdapter(): Operator
	{
		return new StringOperatorAdapter(
			$this->operator, (string) $this->variable, (string) $this->value
		);
	}
}
