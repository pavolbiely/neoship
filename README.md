# Neoship REST API Client
[![Build Status](https://travis-ci.org/pavolbiely/neoship.svg?branch=master)](https://travis-ci.org/pavolbiely/neoship)
[![Coverage Status](https://coveralls.io/repos/github/pavolbiely/neoship/badge.svg?branch=master)](https://coveralls.io/github/pavolbiely/neoship?branch=master)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BHZKXCWAK2NNS)

Please ask [Neoship](https://info.neoship.sk/sk/kontakt) for credentials in order to access their API.

## Installation

Use composer to install this package.

## Example of usage

Create a new Neoship API client instance
```php
use Neoship\Neoship;
use Neoship\NeoshipException;

$clientId = ''; // client secret is provided from Neonus
$clientSecret = ''; // client secret is provided from Neonus
$redirectUri = 'https://...'; // URL where Neoship will redirect you after you authorize it to exchange OAuth code for an access token
$neoship = new Neoship($clientId, $clientSecret, $redirectUri);
```

To gain access to the API you must first get an OAuth code that you will later exchange for the access token.
Getting OAuth code requires entering valid credentials on the Neoship website which URL you can get by calling the `getAuthorizationUrl()` method.
```php
$authUrl = $neoship->getAuthorizationUrl();
header('Location: ' . $authUrl);
exit;
```

After successful authorization you will be redirected from the Neoship website to a URL in the `$redirectUri` variable.

```php
try {
    if ($neoship->requestAccessToken($_GET['code'])) { // exchange OAuth code for an access token
        // we got the token
        print_r($neoship->getToken());
    }
} catch (NeoshipException $e) {
    // something went wrong
}
```

As long as the token is valid you can call any API call.
The following code shows an example of how to send a simple package.

```php
// get all countries list
$countries = [];
foreach ($neoship->apiGetState() as $item) {
	$countries[$item->code] = $item->id;
}

// prepare sender and recipient addresses
$sender = new Address('Harry Potter', 'Webtec', 'Magnetová 1', '831 04', 'Bratislava', $countries['SK']);
$recipient = new Address('Albus Dumbledore', 'Neonus', 'Miestneho priemyslu 1247', '029 01', 'Námestovo', $countries['SK']);

// create package
$package = new Package(123, $recipient, $sender, '1201800002');

// use the following code if the package is sent by cash on delivery payment
$cashOnDelivery = new Payment(10.0, Payment::CURRENCY_EUR, Payment::TYPE_VIAMO);
$package->setCashOnDelivery($cashOnDelivery);

// use the following code if the package is insured
$insurance = new Payment(2500.0);
$package->setInsurance($insurance);

// send a package through the Neoship API
$neoship->apiPostPackage($package);
```

Access token is typically valid for 1 hour. If a token is found to be invalid  `Neoship\NeoshipException` is thrown. In this case it is necessary to repeat the whole process of obtaining the access token from the very beginning.

## API overview
Method | Description
--- | ---
`apiGetLog()` | Returns all log entries for user
`apiGetLogCount()` | Returns count of log entries for user
`apiGetLogPage(int $page)` | Returns requested page of log entries (each page has 200 entries)
`apiGetUser()` | Returns current user
`apiGetState()` | Returns list of all states
`apiGetCurrency()` | Returns list of all currencies
`apiGetStatus(int $id)` | Returns list of statuses of package with given ID
`apiGetPackage(int $id, array $ref = [])` | Returns package with given ID, returns all for current user if no ID is given. If $ref is set, return all packages with given reference numbers.
`apiGetPackageCount()` | Returns count of packages for current user
`apiGetPackagePage(int $page)` | Returns requested page of packages (each page has 50 packages)
`apiPostPackagePrice(array $prices)` | Calculates price of package
`apiPostPackage(Neoship\Package $package)` | Creates new package
`apiPutPackage(int $id, array $package)` | Edits existing package
`apiDeletePackage(int $id)` | Deletes package
`apiGetPackageSticker(array $ref, int $template = 0)` | Outputs sticker PDF to browser for download
`apiGetPackageAcceptance(array $ref)` | Outputs acceptance PDF to browser for download
`apiGetPackagemat(int $id)` | Returns packagemat with given ID, returns all for current user if no ID is given
`apiGetPackagematBoxes()` | Returns list of packagemat boxes


## How to run tests?
Tests are build with [Nette Tester](https://tester.nette.org/). You can run it like this:
```bash
php -f tester ./ -c php.ini-mac --coverage coverage.html --coverage-src ../src
```

## Minimum requirements
- PHP 7.1+
- php-curl

## License
MIT License (c) Pavol Biely

Read the provided LICENSE file for details.
