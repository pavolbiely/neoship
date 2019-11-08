<?php declare(strict_types=1);

use Tester\Assert,
	Neoship\Payment,
	Neoship\Currency;

require __DIR__ . '/bootstrap.php';

$payment = new Payment(100.0, new Currency(1, 'Euro', 'EUR', '€', 1.0), Payment::TYPE_CARD);

Assert::same(100.0, $payment->getPrice());
Assert::true($payment->getCurrency() instanceof Currency);
Assert::same($payment::TYPE_CARD, $payment->getType());

Assert::exception(function () use ($payment) {
	$payment->setType('TEST');
}, '\Neoship\NeoshipException', "Payment type 'TEST' not found");
