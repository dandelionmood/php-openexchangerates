# OpenExchangeRates API PHP Wrapper

Provides a wrapper for the [OpenExchangeRates API](http://openexchangerates.org).

This JSON API allows you to get rates for various currencies, this projet will
give you a simple class to work seamlessly (hopefully ;)) with it.

*Disclaimer* : This tool is not endorsed by OpenExchangeRates, this is a
completely independant work.

## Get an APP ID

First of all, you'll need an app id to make it work, you can get one here :
https://openexchangerates.org/signup

If you want to test it, you can register for a free plan here :
https://openexchangerates.org/signup/free

Please note that a free plan will not allow you to work in HTTPS, amongst
other things. See at the bottom to know how to use the API in plain HTTP.

## Installation

You can easily install it through Composer and Packagist, see here for more
instructions :

 - http://getcomposer.org/doc/00-intro.md
 - https://packagist.org/packages/dandelionmood/openexchangerates

## Get supported currencies

You can see this data here :
http://openexchangerates.org/api/currencies.json

```php
// You'll need to get an app id.
define('OPENEXCHANGERATE_APP_ID', '123-123-123');

$oer = new OpenExchangeRates( OPENEXCHANGERATE_APP_ID );

// You'll get an object with all supported currencies
$currencies = $oer->currencies();
```

## Get latest rates

See here to see what you'll get :
https://openexchangerates.org/documentation#preview-api-response

```php
$oer = new OpenExchangeRates( OPENEXCHANGERATE_APP_ID );
$latest_rates = $eor->latest();

// With a paying plan, you can change base currency like this :
$latest_rates_in_euros = $eor->latest(array('base'=>'EUR'));
```

## Get historical rates (for paying customers only)

See here to see what you'll get :
https://openexchangerates.org/documentation#historical-data

```php
$one_month_ago = strftime('%Y-%m-%d', strtotime('- 1 month'));
$rates_last_month = $eor->historical($one_month_ago);

// You can list only certain currencies using additionnal parameters
$rates_last_month = $eor->historical(
	$one_month_ago,
	array('currencies'=>array('EUR','USD'))
);
```

## Work in HTTP (for free plan)

You can work in HTTP, though this is not the default behaviour (mainly
because you should favour HTTPS to be sure there's no «man in the middle»
for instance).

```php
$oer = new OpenExchangeRates(
	OPENEXCHANGERATE_APP_ID,
	OpenExchangeRates::PROTOCOL_HTTP
);
```

## Change HTTP Client

The default setting is to use `file_get_contents` to work with the API. As this
method is not available everywhere, you have the option to use `curl` instead,
here's how :

```php
$oer = new OpenExchangeRates(
	OPENEXCHANGERATE_APP_ID,
	OpenExchangeRates::PROTOCOL_HTTP,
	OpenExchangeRates::HTTP_CLIENT_CURL
);
```


