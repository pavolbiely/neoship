<?php declare(strict_types=1);

use Tester\Assert,
	Neoship\Currency;

require __DIR__ . '/bootstrap.php';

$currency = new Currency(1, 'Euro', 'EUR', '€', 1.0);

Assert::same(1, $currency->getId());
Assert::same('Euro', $currency->getName());
Assert::same('EUR', $currency->getCode());
Assert::same('€', $currency->getSymbol());
Assert::same(1.0, $currency->getRate());
Assert::same([
	'id' => 1,
	'name' => 'Euro',
	'code' => 'EUR',
	'symbol' => '€',
	'rate' => 1.0,
], $currency->asArray());
