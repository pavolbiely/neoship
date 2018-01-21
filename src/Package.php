<?php declare(strict_types=1);

namespace Neoship;

class Package
{
	const NOTIFICATION_SMS = 'sms';
	const NOTIFICATION_PHONE = 'phone';
	const NOTIFICATION_EMAIL = 'email';
	const NOTIFICATIONS = [self::NOTIFICATION_SMS, self::NOTIFICATION_PHONE, self::NOTIFICATION_EMAIL];

	const EXPRESS_NONE = 0;
	const EXPRESS_BY_9 = 2;
	const EXPRESS_BY_12 = 1;
	const EXPRESS_TYPES = [self::EXPRESS_NONE, self::EXPRESS_BY_9, self::EXPRESS_BY_12];

	const PACKAGEMAT_BOX_TYPE_A = 1;
	const PACKAGEMAT_BOX_TYPE_B = 2;
	const PACKAGEMAT_BOX_TYPE_C = 3;
	const PACKAGEMAT_BOX_TYPES = [self::PACKAGEMAT_BOX_TYPE_A, self::PACKAGEMAT_BOX_TYPE_B, self::PACKAGEMAT_BOX_TYPE_C];

	/* @var int */
	protected $id;

	/* @var float */
	protected $price;

	/* @var float */
	protected $priceVat;

	/* @var int */
	protected $vat;

	/* @var \Neoship\Address */
	protected $recipient;

	/* @var \Neoship\Address */
	protected $sender;

	/* @var string */
	protected $variableNumber;

	/* @var \Neoship\Payment */
	protected $cashOnDelivery;

	/* @var \Neoship\Payment */
	protected $insurance;

	/* @var int */
	protected $insuranceCurrency;

	/* @var float */
	protected $weight = 0;

	/* @var int */
	protected $express = 0;

	/* @var bool */
	protected $saturdayDelivery = false;

	/* @var array */
	protected $notifications = [];

	/* @var string */
	protected $trackingNumber;

	/* @var string */
	protected $barcode;

	/* @var string */
	protected $invoiceNumber;

	/* @var \DateTime */
	protected $invoiceDate;

	/* @var string */
	protected $packageMatRecipient;

	/* @var int */
	protected $packageMatBox;

	/* @var string */
	protected $parcelShopRecipientName;

	/* @var bool */
	protected $holdDelivery = false;

	/* @var \DateTime */
	protected $dateCreated;

	/* @var \DateTime */
	protected $dateExported;

	/* @var string */
	protected $mainPackageNumber;



	/**
	 * @param int
	 * @param \Neoship\Address
	 * @param \Neoship\Address
	 * @param string
	 * @param string
	 */
	public function __construct(int $id, Address $sender, Address $recipient, string $variableNumber = NULL, $mainPackageNumber = NULL)
	{
		$this->setId($id);
		$this->setSender($sender);
		$this->setRecipient($recipient);
		$this->setVariableNumber($variableNumber);
		$this->setMainPackageNumber($mainPackageNumber);
	}



	/**
	 * @param int
	 * @return \Neoship\Package
	 */
	public function setId(int $id): Package
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
	 * @param float
	 * @param int
	 * @return \Neoship\Package
	 */
	public function setPrice(float $price, int $vat = 0): Package
	{
		$this->price = $price;
		$this->priceVat = round($price * ((100.0 + (float) $vat) / 100.0), 4);
		$this->vat = $vat;
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
	 * @param float
	 * @param int
	 * @return \Neoship\Package
	 */
	public function setPriceVat(float $price, int $vat = 0): Package
	{
		$this->price = round($price / ((100.0 + (float) $vat) / 100.0), 4);
		$this->priceVat = $price;
		$this->vat = $vat;
		return $this;
	}



	/**
	 * @return float
	 */
	public function getPriceVat(): float
	{
		return $this->priceVat;
	}



	/**
	 * @param int
	 * @return \Neoship\Package
	 */
	public function setVat(int $vat): Package
	{
		$this->setPrice($this->price, $vat);
		return $this;
	}



	/**
	 * @return int
	 */
	public function getVat(): int
	{
		return $this->vat;
	}



	/**
	 * @param \Neoship\Address
	 * @return self
	 */
	public function setRecipient(Address $address): Package
	{
		$this->recipient = $address;
		return $this;
	}



	/**
	 * @return \Neoship\Address
	 */
	public function getRecipient(): Address
	{
		return $this->recipient;
	}



	/**
	 * @param \Neoship\Address
	 * @return self
	 */
	public function setSender(Address $address): Package
	{
		$this->sender = $address;
		return $this;
	}



	/**
	 * @return \Neoship\Address
	 */
	public function getSender(): Address
	{
		return $this->sender;
	}


	/**
	 * @param \Neoship\Payment
	 * @return \Neoship\Package
	 */
	public function setCashOnDelivery(Payment $cashOnDelivery = NULL): Package
	{
		$this->cashOnDelivery = $cashOnDelivery;
		return $this;
	}



	/**
	 * @return \Neoship\Payment
	 */
	public function getCashOnDelivery(): ?Payment
	{
		return $this->cashOnDelivery;
	}



	/**
	 * @param \Neoship\Payment
	 * @return \Neoship\Package
	 */
	public function setInsurance(Payment $insurance = NULL): Package
	{
		$this->insurance = $insurance;
		return $this;
	}



	/**
	 * @return \Neoship\Payment
	 */
	public function getInsurance(): ?Payment
	{
		return $this->insurance;
	}



	/**
	 * @param string
	 * @return \Neoship\Package
	 */
	public function setVariableNumber(string $variableNumber = NULL): Package
	{
		$this->variableNumber = $variableNumber;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getVariableNumber(): ?string
	{
		return $this->variableNumber;
	}



	/**
	 * @param float
	 * @return \Neoship\Package
	 */
	public function setWeight(float $weight): Package
	{
		$this->weight = $weight;
		return $this;
	}



	/**
	 * @return float
	 */
	public function getWeight(): float
	{
		return $this->weight;
	}



	/**
	 * @param int
	 * @return self
	 * @throws \Neoship\NeoshipException
	 */
	public function setExpress(int $value): Package
	{
		if (in_array($value, self::EXPRESS_TYPES)) {
			$this->express = $value;
		} else {
			throw new NeoshipException("Express delivery type '" . $value . "' not found");
		}

		return $this;
	}



	/**
	 * @return int
	 */
	public function getExpress(): int
	{
		return $this->express;
	}



	/**
	 * @param bool
	 * @return \Neoship\Package
	 */
	public function setSaturdayDelivery(bool $saturdayDelivery): Package
	{
		$this->saturdayDelivery = $saturdayDelivery;
		return $this;
	}



	/**
	 * @return bool
	 */
	public function getSaturdayDelivery(): bool
	{
		return $this->saturdayDelivery;
	}



	/**
	 * @param string
	 * @return \Neoship\Package
	 */
	public function setTrackingNumber(string $trackingNumber = NULL): Package
	{
		$this->trackingNumber = $trackingNumber;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getTrackingNumber(): ?string
	{
		return $this->trackingNumber;
	}



	/**
	 * @param string[]
	 * @return \Neoship\Package
	 * @throws \Neoship\NeoshipException
	 */
	public function addNotification(string $notificiation): Package
	{
		if (!in_array($notificiation, self::NOTIFICATIONS)) {
			throw new NeoshipException("Notification type '" . $notificiation . "' not found");
		}

		if (!in_array($notificiation, $this->notifications)) {
			$this->notifications[] = $notificiation;
		}

		return $this;
	}



	/**
	 * @param string[]
	 * @return \Neoship\Package
	 */
	public function setNotifications(array $notificiations = NULL): Package
	{
		if ($notificiations !== NULL && count($notificiations)) {
			foreach ($notificiations as $notificiation) {
				$this->addNotification($notificiation);
			}
		} else {
			$this->notifications = [];
		}

		return $this;
	}



	/**
	 * @return array
	 */
	public function getNotifications(): array
	{
		return $this->notifications;
	}



	/**
	 * @param string
	 * @return \Neoship\Package
	 */
	public function setBarcode(string $barcode = NULL): Package
	{
		$this->barcode = $barcode;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getBarcode(): ?string
	{
		return $this->barcode;
	}



	/**
	 * @param string
	 * @return \Neoship\Package
	 */
	public function setInvoiceNumber(string $invoiceNumber = NULL): Package
	{
		$this->invoiceNumber = $invoiceNumber;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getInvoiceNumber(): ?string
	{
		return $this->invoiceNumber;
	}



	/**
	 * @param \DateTime
	 * @return \Neoship\Package
	 */
	public function setInvoiceDate(\DateTime $date = NULL): Package
	{
		$this->invoiceDate = $date;
		return $this;
	}



	/**
	 * @return \DateTime
	 */
	public function getInvoiceDate(): ?\DateTime
	{
		return $this->invoiceDate;
	}



	/**
	 * @param int
	 * @return \Neoship\Package
	 */
	public function setPackageMatRecipient(string $recipient = NULL): Package
	{
		$this->packageMatRecipient = $recipient;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getPackageMatRecipient(): ?string
	{
		return $this->packageMatRecipient;
	}



	/**
	 * @param int
	 * @return \Neoship\Package
	 */
	public function setPackageMatBox(int $boxType = NULL): Package
	{
		if (in_array($boxType, self::PACKAGEMAT_BOX_TYPES)) {
			$this->packageMatBox = $boxType;
		} else {
			throw new NeoshipException("Packagemat box type '" . $boxType . "' not found");
		}

		return $this;
	}



	/**
	 * @return int
	 */
	public function getPackageMatBox(): ?int
	{
		return $this->packageMatBox;
	}



	/**
	 * @param string
	 * @return \Neoship\Package
	 */
	public function setParcelShopRecipientName(string $recipient = NULL): Package
	{
		$this->parcelShopRecipientName = $recipient;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getParcelShopRecipientName(): ?string
	{
		return $this->parcelShopRecipientName;
	}



	/**
	 * @param bool
	 * @return \Neoship\Package
	 */
	public function setHoldDelivery(bool $holdDelivery): Package
	{
		$this->holdDelivery = $holdDelivery;
		return $this;
	}



	/**
	 * @return bool
	 */
	public function getHoldDelivery(): bool
	{
		return $this->holdDelivery;
	}



	/**
	 * @param \DateTime
	 * @return \Neoship\Package
	 */
	public function setDateCreated(\DateTime $date = NULL): Package
	{
		$this->dateCreated = $date;
		return $this;
	}



	/**
	 * @return \DateTime
	 */
	public function getDateCreated(): ?\DateTime
	{
		return $this->dateCreated;
	}



	/**
	 * @param \DateTime
	 * @return \Neoship\Package
	 */
	public function setDateExported(\DateTime $date = NULL): Package
	{
		$this->dateExported = $date;
		return $this;
	}



	/**
	 * @return \DateTime
	 */
	public function getDateExported(): ?\DateTime
	{
		return $this->dateExported;
	}



	/**
	 * @param string
	 * @return \Neoship\Package
	 */
	public function setMainPackageNumber(string $variableNumber = NULL): Package
	{
		$this->mainPackageNumber = $variableNumber;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getMainPackageNumber(): ?string
	{
		return $this->mainPackageNumber;
	}



	/**
	 * @return array
	 */
	public function asArray()
	{
		$package = [];

		if ($this->id) {
			$package['id'] = $this->getId();
		}

		// variable number / order number
		$package['variableNumber'] = $this->getVariableNumber();

		// main package variable number
		if ($this->mainPackageNumber) {
			$package['mainPackageNumber'] = $this->getMainPackageNumber();
		}

		// package cash on delivery
		if ($this->cashOnDelivery) {
			$package['cashOnDeliveryPrice'] = $this->cashOnDelivery->getPrice();
			$package['cashOnDeliveryPayment'] = $this->cashOnDelivery->getType();
			$package['cashOnDeliveryCurrency'] = $this->cashOnDelivery::CURRENCIES[$this->cashOnDelivery->getCurrency()];
		}

		// package insurance
		if ($this->insurance) {
			$package['insurance'] = $this->insurance->getPrice();
			$package['insuranceCurrency'] = $this->insurance::CURRENCIES[$this->insurance->getCurrency()];
		}

		// notifications & tracking
		$package['notification'] = implode(',', $this->getNotifications());
		$package['trackingNumber'] = $this->getTrackingNumber();

		// additional package info
		$package['weight'] = $this->getWeight();
		$package['barCode'] = $this->getBarcode();

		// special services
		$package['express'] = $this->getExpress();
		$package['saturdayDelivery'] = $this->getSaturdayDelivery() ? 1 : 0;

		// hold delivery?
		$package['holdDelivery'] = $this->getHoldDelivery() ? 1 : 0;

		// packagemat
		if ($this->packageMatRecipient) {
			$package['packageMatRecieverName'] = $this->getPackageMatRecipient();
		}
		if ($this->packageMatBox) {
			$package['packageMatBox'] = $this->getPackageMatBox();
		}

		// parcelshop
		if ($this->parcelShopRecipientName) {
			$package['parcelShopRecieverName'] = $this->getParcelShopRecipientName();
		}

		// invoice info
		if ($this->invoiceNumber) {
			$package['invoiceNumber'] = $this->getInvoiceNumber();

			if ($this->invoiceDate) {
				$package['invoiceDate'] = $this->getInvoiceDate()->format('U');
			}
		}

		// dates
		if ($this->dateCreated) {
			$package['createdAt'] = $this->getDateCreated()->format('U');
		}
		if ($this->dateExported) {
			$package['exportedAt'] = $this->getDateExported()->format('U');
		}

		// package sender
		$sender = $this->getSender();
		$package['sender'] = [
			'appelation' => $sender->getAppelation(),
			'name' => $sender->getName(),
			'company' => $sender->getCompany(),
			'street' => $sender->getStreet(),
			'city' => $sender->getCity(),
			'houseNumber' => $sender->getHouseNumber(),
			'houseNumberExt' => $sender->getHouseNumberExt(),
			'zIP' => $sender->getZipCode(),
			'state' => Address::STATES[$sender->getState()]['id'],
			'email' => $sender->getEmail(),
			'phone' => $sender->getPhone(),
		];

		// package recipient
		$recipient = $this->getRecipient();
		$package['reciever'] = [
			'appelation' => $recipient->getAppelation(),
			'name' => $recipient->getName(),
			'company' => $recipient->getCompany(),
			'street' => $recipient->getStreet(),
			'city' => $recipient->getCity(),
			'houseNumber' => $recipient->getHouseNumber(),
			'houseNumberExt' => $recipient->getHouseNumberExt(),
			'zIP' => $recipient->getZipCode(),
			'state' => Address::STATES[$recipient->getState()]['id'],
			'email' => $recipient->getEmail(),
			'phone' => $recipient->getPhone(),
		];

		return ['package' => $package];
	}
}
