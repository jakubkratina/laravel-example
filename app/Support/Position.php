<?php

namespace App\Support;



final class Position
{

	/**
	 * @var int
	 */
	public $x;

	/**
	 * @var int
	 */
	public $y;



	/**
	 * @param int $x
	 * @param int $y
	 */
	public function __construct($x, $y)
	{
		$this->x = (int) $x;
		$this->y = (int) $y;
	}



	/**
	 * @param int $x
	 * @param int $y
	 * @return Position
	 */
	public static function create($x, $y): Position
	{
		return new self($x, $y);
	}



	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'position_x' => $this->x,
			'position_y' => $this->y
		];
	}



	/**
	 * @return int
	 */
	public function x(): int
	{
		return $this->x;
	}



	/**
	 * @return int
	 */
	public function y(): int
	{
		return $this->y;
	}
}
