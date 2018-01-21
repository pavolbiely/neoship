<?php declare(strict_types=1);

use Tester\Assert,
	Neoship\Package,
	Neoship\Address,
	Neoship\Payment;

require __DIR__ . '/bootstrap.php';

$sender = new Address('Test Tester', 'Webtec', 'Testovacia 1', '831 04', 'Bratislava', Address::STATE_SK);
$recipient = new Address('Test Tester 2', 'Viamedia SK', 'Testovacia 2', '831 05', 'Bratislava', Address::STATE_SK);
$cashOnDelivery = new Payment(10.0, Payment::CURRENCY_EUR, Payment::TYPE_VIAMO);
$insurance = new Payment(2500.0);

$package = new Package(123, $recipient, $sender, '1201800002', '1201800001');
$package->setPrice(100.0, 20);
$package->setCashOnDelivery($cashOnDelivery);
$package->setInsurance($insurance);
$package->setWeight(2.3);
$package->setExpress($package::EXPRESS_BY_9);
$package->setSaturdayDelivery(true);
$package->setNotifications([$package::NOTIFICATION_EMAIL, $package::NOTIFICATION_SMS]);
$package->setTrackingNumber('232323');
$package->setBarcode('9999');
$package->setInvoiceNumber('1800001');
$package->setInvoiceDate(new \DateTime('2018-01-10'));
$package->setPackageMatBox($package::PACKAGEMAT_BOX_TYPE_B);
$package->setPackageMatRecipient('Matt Packageman');
$package->setParcelShopRecipientName('Some Name');
$package->setHoldDelivery(true);
$package->setDateCreated(new \DateTime('2018-01-01'));
$package->setDateExported(new \DateTime('2018-01-02'));

Assert::true($package->getRecipient() instanceof Address);
Assert::true($package->getSender() instanceof Address);
Assert::true($package->getCashOnDelivery() instanceof Payment);
Assert::true($package->getInsurance() instanceof Payment);
Assert::same(123, $package->getId());
Assert::same(100.0, $package->getPrice());
Assert::same(120.0, $package->getPriceVat());
Assert::same(20, $package->getVat());
Assert::same(2.3, $package->getWeight());
Assert::same($package::EXPRESS_BY_9, $package->getExpress());
Assert::true($package->getSaturdayDelivery());
Assert::same('1201800002', $package->getVariableNumber());
Assert::same('1201800001', $package->getMainPackageNumber());
Assert::same('232323', $package->getTrackingNumber());
Assert::same('9999', $package->getBarcode());
Assert::same('1800001', $package->getInvoiceNumber());
Assert::true($package->getInvoiceDate() instanceof \DateTime);
Assert::same($package::PACKAGEMAT_BOX_TYPE_B, $package->getPackageMatBox());
Assert::same('Matt Packageman', $package->getPackageMatRecipient());
Assert::same('Some Name', $package->getParcelShopRecipientName());
Assert::true($package->getHoldDelivery());
Assert::true($package->getDateCreated() instanceof \DateTime);
Assert::true($package->getDateExported() instanceof \DateTime);




// test setting price inc. VAT
$package->setPriceVat(60.0, 20);
Assert::same(50.0, $package->getPrice());
Assert::same(60.0, $package->getPriceVat());
Assert::same(20, $package->getVat());




// change VAT
$package->setVat(10);
Assert::same(50.0, $package->getPrice());
Assert::same(55.0, $package->getPriceVat());
Assert::same(10, $package->getVat());




// clear notifications by entering [] parameter to setNotifications method
Assert::same([$package::NOTIFICATION_EMAIL, $package::NOTIFICATION_SMS], $package->getNotifications());
$package->setNotifications([]);
Assert::same([], $package->getNotifications());



// clear notifications by entering NULL parameter to setNotifications method
$package->setNotifications([$package::NOTIFICATION_PHONE]);
Assert::same([$package::NOTIFICATION_PHONE], $package->getNotifications());
$package->setNotifications(NULL);
Assert::same([], $package->getNotifications());



// test exceptions
Assert::exception(function () use ($package) {
	$package->setExpress(999);
}, '\Neoship\NeoshipException', "Express delivery type '999' not found");

Assert::exception(function () use ($package) {
	$package->addNotification('non_existing');
}, '\Neoship\NeoshipException', "Notification type 'non_existing' not found");

Assert::exception(function () use ($package) {
	$package->setPackageMatBox(999);
}, '\Neoship\NeoshipException', "Packagemat box type '999' not found");




// test array output
$package->setNotifications([$package::NOTIFICATION_EMAIL, $package::NOTIFICATION_SMS]);
Assert::same([
	'package' => [
		'id' => 123,
		'variableNumber' => '1201800002',
		'mainPackageNumber' => '1201800001',
		'cashOnDeliveryPrice' => 10.0,
		'cashOnDeliveryPayment' => 'VIAMO',
		'cashOnDeliveryCurrency' => [
			'id' => 1,
			'name' => 'Euro',
			'code' => 'EUR',
			'symbol' => '€',
			'rate' => 1,
		],
		'insurance' => 2500.0,
		'insuranceCurrency' => [
			'id' => 1,
			'name' => 'Euro',
			'code' => 'EUR',
			'symbol' => '€',
			'rate' => 1,
		],
		'notification' => 'email,sms',
		'trackingNumber' => '232323',
		'weight' => 2.3,
		'barCode' => '9999',
		'express' => 2,
		'saturdayDelivery' => 1,
		'holdDelivery' => 1,
		'packageMatRecieverName' => 'Matt Packageman',
		'packageMatBox' => 2,
		'parcelShopRecieverName' => 'Some Name',
		'invoiceNumber' => '1800001',
		'invoiceDate' => '1515542400',
		'createdAt' => '1514764800',
		'exportedAt' => '1514851200',
		'sender' => [
			'appelation' => null,
			'name' => 'Test Tester',
			'company' => 'Webtec',
			'street' => 'Testovacia 1',
			'city' => 'Bratislava',
			'houseNumber' => null,
			'houseNumberExt' => null,
			'zIP' => '83104',
			'state' => 1,
			'email' => null,
			'phone' => null,
		],
		'reciever' => [
			'appelation' => null,
			'name' => 'Test Tester 2',
			'company' => 'Viamedia SK',
			'street' => 'Testovacia 2',
			'city' => 'Bratislava',
			'houseNumber' => null,
			'houseNumberExt' => null,
			'zIP' => '83105',
			'state' => 1,
			'email' => null,
			'phone' => null,
		],
	],
], $package->asArray());
