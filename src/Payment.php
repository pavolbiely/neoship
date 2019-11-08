<?php declare(strict_types=1);

namespace Neoship;

class Payment
{
	const TYPE_DEFAULT = '';
	const TYPE_CARD = 'CARD';
	const TYPE_VIAMO = 'VIAMO';
	const TYPES = [self::TYPE_DEFAULT, self::TYPE_CARD, self::TYPE_VIAMO];

	/* @var float */
	protected $price;

	/* @var \Neoship\Currency */
	protected $currency;

	/** @var string */
	protected $type;
	


	/**
	 * @param string
	 * @param int
	 * @param string
	 */
	public function __construct(float $price, Currency $currency, string $type = self::TYPE_DEFAULT)
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
	 */
	public function setCurrency(Currency $currency = null): Payment
	{
		$this->currency = $currency;
		return $this;
	}



	/**
	 * @return \Neoship\Currency
	 */
	public function getCurrency(): ?Currency
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
