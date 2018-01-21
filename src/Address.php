<?php declare(strict_types=1);

namespace Neoship;

class Address
{
	/* @var string */
	protected $appelation;

	/* @var string */
	protected $name;

	/* @var string */
	protected $company;

	/* @var string */
	protected $street;

	/* @var string */
	protected $houseNumber;

	/* @var string */
	protected $houseNumberExt;

	/* @var string */
	protected $zipCode;

	/* @var string */
	protected $city;

	/* @var string */
	protected $state;

	/* @var string */
	protected $email;

	/* @var string */
	protected $phone;

	const STATE_SK = 'SK'; // Slovensko
	const STATE_CZ = 'CZ'; // Česká republika
	const STATE_HU = 'HU'; // Maďarsko
	const STATE_AT = 'AT'; // Rakúsko
	const STATE_PT = 'PT'; // Portugalsko
	const STATES = [
		self::STATE_SK => [
			'id' => 1,
			'name' => 'Slovenská republika',
			'code' => 'SK',
			'isoCode' => '703',
			'cashOnDelivery' => 1,
		],
		self::STATE_CZ => [
			'id' => 9,
			'name' => 'Česká republika',
			'code' => 'CZ',
			'isoCode' => '203',
			'cashOnDelivery' => 1,
		],
		self::STATE_HU => [
			'id' => 10,
			'name' => 'Maďarsko',
			'code' => 'HU',
			'isoCode' => '348',
			'cashOnDelivery' => 1,
		],
		self::STATE_AT => [
			'id' => 11,
			'name' => 'Rakúsko',
			'code' => 'AT',
			'isoCode' => '040',
			'cashOnDelivery' => 1,
		],
		self::STATE_PT => [
			'id' => 12,
			'name' => 'Portugalsko',
			'code' => 'PT',
			'isoCode' => '620',
			'cashOnDelivery' => NULL,
		],
	];
	


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 */
	public function __construct(string $name, string $company, string $street, string $zipCode, string $city, string $state = self::STATE_SK)
	{
		$this->setName($name);
		$this->setCompany($company);
		$this->setStreet($street);
		$this->setZipCode($zipCode);
		$this->setCity($city);
		$this->setState($state);
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setAppelation(string $value = NULL): Address
	{
		$this->appelation = $value;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getAppelation(): ?string
	{
		return $this->appelation;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setName(string $value): Address
	{
		$this->name = mb_substr($value, 0, 50);
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
	public function setCompany(string $value = NULL): Address
	{
		$this->company = $value;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCompany(): ?string
	{
		return $this->company;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setStreet(string $value): Address
	{
		$this->street = mb_substr($value, 0, 50);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getStreet(): string
	{
		return $this->street;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setHouseNumber(string $value = NULL): Address
	{
		$this->houseNumber = $value ? mb_substr($value, 0, 4) : NULL;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getHouseNumber(): ?string
	{
		return $this->houseNumber;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setHouseNumberExt(string $value = NULL): Address
	{
		$this->houseNumberExt = $value ? mb_substr($value, 0, 6) : NULL;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getHouseNumberExt(): ?string
	{
		return $this->houseNumberExt;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setZipCode(string $value): Address
	{
		$value = preg_replace('~([^0-9]+)~', NULL, $value);
		$this->zipCode = mb_substr($value, 0, 6);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getZipCode(): string
	{
		return $this->zipCode;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setCity(string $value): Address
	{
		$this->city = mb_substr($value, 0, 50);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCity(): string
	{
		return $this->city;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setState(string $value): Address
	{
		$code = strtoupper($value);
		if (isset(self::STATES[$code])) {
			$this->state = $code;
		} else {
			throw new \Exception("State '" . $value . "' not found");
		}
		return $this;
	}



	/**
	 * @return string
	 */
	public function getState(): string
	{
		return $this->state;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setEmail(string $value = NULL): Address
	{
		$email = mb_substr($value, 0, 50);
		if ($value === NULL || filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->email = $email;
		} else {
			throw new \Exception("E-mail '" . $email . "' has invalid format");
		}
		return $this;
	}



	/**
	 * @return string
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function setPhone(string $value): Address
	{
		$this->phone = mb_substr($value, 0, 30);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getPhone(): ?string
	{
		return $this->phone;
	}
}
