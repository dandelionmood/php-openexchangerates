<?php
use \OpenExchangeRates\OpenExchangeRates;

/**
	* You have to specify your «app id» before running the test suite !
*/
class OpenExchangeRatesTest extends \PHPUnit_Framework_TestCase
{
	/**
		* Change this string by your own app id.
	*/
	public static $app_id = '{YOUR APP ID HERE}';
	
	public function testCurrencies()
	{
		$currencies = $this->_getInstance( )
			->currencies();
		$this->assertTrue( is_object($currencies) );
		$this->assertTrue( $currencies->EUR == 'Euro' );
		$this->assertTrue( $currencies->USD == 'United States Dollar' );
	}
	
	public function testLatest()
	{
		$latest = $this->_getInstance()->latest();
		
		$this->assertTrue( is_object($latest) );
		$this->assertTrue( isset($latest->disclaimer) );
		$this->assertTrue( isset($latest->license) );
		$this->assertTrue( isset($latest->timestamp)
			&& is_numeric($latest->timestamp) );
		$this->assertTrue( isset($latest->base)
			&& $latest->base == 'USD' );
		$this->assertTrue( isset($latest->rates)
			&& is_object($latest->rates)
			&& count($latest->rates) > 0);
		
		// Let's try and change the base using an additionnal parameter
		// Caution : works only for paying customers
		// $latest = $this->_getInstance()->latest(array(
		// 	'base' => 'EUR'
		// ));
		// $this->assertTrue( isset($latest->base)
		// 	&& $latest->base == 'EUR' );
	}
	
	// Caution, this only works for paying customers.
	// public function testHistorical()
	// {
	// 	$date = strftime('%Y-%m-%d');
	// 	$historical = $this->_getInstance()->historical( $date );
	// 	
	// 	$this->assertTrue( is_object($historical) );
	// 	$this->assertTrue( isset($historical->disclaimer) );
	// 	$this->assertTrue( isset($historical->license) );
	// 	$this->assertTrue( isset($historical->timestamp)
	// 		&& is_numeric($historical->timestamp) );
	// 	$this->assertTrue( isset($historical->base)
	// 		&& $historical->base == 'USD' );
	// 	$this->assertTrue( isset($historical->rates)
	// 		&& is_object($historical->rates)
	// 		&& count($historical->rates) > 0);
	// }
	
	private function _getInstance()
	{
		return new OpenExchangeRates(
			self::$app_id,
			OpenExchangeRates::PROTOCOL_HTTP,
			// Can be set to HTTP_CLIENT_CURL, depends on your configuration.
			OpenExchangeRates::HTTP_CLIENT_FILE_GET_CONTENTS 
		);
	}
	
}
