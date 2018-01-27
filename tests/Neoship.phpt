<?php declare(strict_types=1);

use Tester\Assert;
use Tester\Helpers;
use Neoship\Neoship;

require __DIR__ . '/bootstrap.php';

$tempDir = __DIR__ . '/../temp';

Tester\Helpers::purge($tempDir);


$neoship = new Neoship('123', '456', 'http://example.org', Neoship::API_URL_DEVELOPMENT, $tempDir);

Assert::exception(function () use ($neoship) {
	$neoship->getToken();
}, '\Neoship\NeoshipException', 'OAuth token missing or expired');

Assert::same(false, $neoship->isAuthorized());

$neoship->setToken('abc', 'xyz', 3600, 'bearer', NULL);

Assert::same('123', $neoship->getClientId());
Assert::same('456', $neoship->getClientSecret());
Assert::same('http://example.org', $neoship->getRedirectUri());
Assert::same($tempDir, $neoship->getTempDir());
Assert::same(Neoship::API_URL_DEVELOPMENT, $neoship->getApiUrl());
Assert::same(Neoship::API_URL_DEVELOPMENT . '/oauth/v2/auth?client_id=123&response_type=code&redirect_uri=http%3A%2F%2Fexample.org', $neoship->getAuthorizationUrl());
Assert::same(true, $neoship->isAuthorized());

$token = (array) $neoship->getToken();
Assert::true($token['expiration_time'] instanceof \DateTime);
unset($token['expiration_time']);
Assert::same([
	'access_token' => 'abc',
	'expires_in' => 3600,
	'token_type' => 'bearer',
	'scope' => null,
	'refresh_token' => 'xyz',
], $token);

Assert::exception(function () use ($tempDir) {
	new Neoship('123', '456', 'NON_VALID_URL', Neoship::API_URL_DEVELOPMENT, $tempDir);
}, '\Neoship\NeoshipException', 'Invalid redirect URI format');

Assert::exception(function () use ($tempDir) {
	new Neoship('123', '456', 'http://example.org', 'NON_VALID_URL', $tempDir);
}, '\Neoship\NeoshipException', 'Invalid API URL format');



$neoship = new Neoship('123', '456', 'http://example.org', Neoship::API_URL_DEVELOPMENT, $tempDir);
Assert::same(true, $neoship->isAuthorized());



$neoship = new Neoship('123', '456', 'http://example.org', Neoship::API_URL_DEVELOPMENT, NULL);
Assert::same(sys_get_temp_dir(), $neoship->getTempDir());



$neoship = new Neoship('123', '456', 'http://example.org', Neoship::API_URL_DEVELOPMENT, __DIR__ . '/non-existing-directory');
Assert::exception(function () use ($neoship) {
	$neoship->setToken('abc', 'xyz', 3600, 'bearer', NULL);
}, '\Neoship\NeoshipException', 'Could not write to temp directory');
