<?php declare(strict_types=1);

use Tester\Assert,
	Neoship\Payment;

require __DIR__ . '/bootstrap.php';

$payment = new Payment(100.0, Payment::CURRENCY_CZK, Payment::TYPE_CARD);

Assert::same(100.0, $payment->getPrice());
Assert::same($payment::CURRENCY_CZK, $payment->getCurrency());
Assert::same($payment::TYPE_CARD, $payment->getType());

Assert::exception(function () use ($payment) {
	$payment->setCurrency(999);
}, '\Neoship\NeoshipException', "Currency type '999' not found");

Assert::exception(function () use ($payment) {
	$payment->setType('TEST');
}, '\Neoship\NeoshipException', "Payment type 'TEST' not found");
