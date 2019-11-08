<?php declare(strict_types=1);

use Tester\Assert,
	Neoship\Address;

require __DIR__ . '/bootstrap.php';

$address = new Address('Test Tester', 'Webtec', 'Testovacia 1', '831 04', 'Praha', 2);
$address->setAppelation('Pán');
$address->setHouseNumber('1/A');
$address->setHouseNumberExt('XYZ');
$address->setPhone('+421949949949');
$address->setEmail('info@example.org');

Assert::same('Pán', $address->getAppelation());
Assert::same('Test Tester', $address->getName());
Assert::same('Webtec', $address->getCompany());
Assert::same('Testovacia 1', $address->getStreet());
Assert::same('1/A', $address->getHouseNumber());
Assert::same('XYZ', $address->getHouseNumberExt());
Assert::same('Praha', $address->getCity());
Assert::same('83104', $address->getZipCode());
Assert::same(2, $address->getState());
Assert::same('+421949949949', $address->getPhone());
Assert::same('info@example.org', $address->getEmail());

Assert::exception(function () use ($address) {
	$address->setEmail('SOME_INVALID_EMAIL_STRING');
}, '\Exception');
