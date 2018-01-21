<?php declare(strict_types=1);

namespace Neoship;

class Payment
{
	const CURRENCY_EUR = 1;
	const CURRENCY_CZK = 2;
	const CURRENCIES = [
		self::CURRENCY_EUR => [
			'id' => self::CURRENCY_EUR,
			'name' => 'Euro',
			'code' => 'EUR',
			'symbol' => '€',
			'rate' => 1,
		],
		self::CURRENCY_CZK => [
			'id' => self::CURRENCY_EUR,
			'name' => 'Česká koruna',
			'code' => 'CZK',
			'symbol' => 'Kč',
			'rate' => 1,
		],
	];

	const TYPE_DEFAULT = '';
	const TYPE_CARD = 'CARD';
	const TYPE_VIAMO = 'VIAMO';
	const TYPES = [self::TYPE_DEFAULT, self::TYPE_CARD, self::TYPE_VIAMO];

	/* @var float */
	protected $price;

	/* @var int */
	protected $currency;

	/** @var string */
	protected $type;
	


	/**
	 * @param string
	 * @param int
	 * @param string
	 */
	public function __construct(float $price, int $currency = self::CURRENCY_EUR, string $type = self::TYPE_DEFAULT)
	{
		$this->setPrice($price);
		$this->setCurrency($currency);
		$this->setType($type);
	}



	/**
	 * @param float
	 * @param int
	 * @return \Neoship\Package
	 */
	public function setPrice(float $price): Payment
	{
		$this->price = $price;
		return $this;
	}



	/**
	 * @return float
	 */
	public function getPrice(): float
	{
		return $this->price;
	}



	/**
	 * @param int
	 * @return self
	 * @throws \Neoship\NeoshipException
	 */
	public function setCurrency(int $id): Payment
	{
		if (isset(self::CURRENCIES[$id])) {
			$this->currency = $id;
		} else {
			throw new NeoshipException("Currency type '" . $id . "' not found");
		}

		return $this;
	}



	/**
	 * @return int
	 */
	public function getCurrency(): int
	{
		return $this->currency;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Neoship\NeoshipException
	 */
	public function setType(string $type): Payment
	{
		if (in_array($type, self::TYPES)) {
			$this->type = $type;
		} else {
			throw new NeoshipException("Payment type '" . $type . "' not found");
		}

		return $this;
	}



	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}
}
