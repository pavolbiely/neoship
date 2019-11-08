<?php declare(strict_types=1);

namespace Neoship;

class Currency
{
	/* @var int */
	protected $id;

	/* @var string */
	protected $name;

	/* @var string */
	protected $code;

	/* @var string */
	protected $symbol;

	/* @var float */
	protected $rate;



	/**
	 * @param int
	 * @param string
	 * @param string
	 * @param string
	 * @param float
	 */
	public function __construct(int $id, string $name, string $code, string $symbol, float $rate)
	{
		$this->setId($id);
		$this->setName($name);
		$this->setCode($code);
		$this->setSymbol($symbol);
		$this->setRate($rate);
	}



	/**
	 * @param int
	 * @return self
	 */
	public function setId(int $id): Currency
	{
		$this->id = $id;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setName(string $name): Currency
	{
		$this->name = $name;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setCode(string $code): Currency
	{
		$this->code = $code;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setSymbol(string $symbol): Currency
	{
		$this->symbol = $symbol;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getSymbol(): string
	{
		return $this->symbol;
	}



	/**
	 * @param float
	 * @return self
	 */
	public function setRate(float $rate): Currency
	{
		$this->rate = $rate;
		return $this;
	}



	/**
	 * @return float
	 */
	public function getRate(): float
	{
		return $this->rate;
	}



	/**
	 * @return array
	 */
	public function asArray()
	{
		return [
			'id' => $this->getId(),
			'name' => $this->getName(),
			'code' => $this->getCode(),
			'symbol' => $this->getSymbol(),
			'rate' => $this->getRate(),
		];
	}
}
