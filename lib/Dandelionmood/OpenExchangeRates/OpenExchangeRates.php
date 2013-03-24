<?php
namespace OpenExchangeRates;

use \Buzz\Browser;

/**
  * Wrapper for OpenExchangeRates API.
  * This will allow you to access the API data very easily.
  *
  * You can add an array of parameters to {@link self::currencies()},
  * {@link self::latest()} and {@link self::historical()} if you want to
  * customize output, see the API documentation here
  * https://openexchangerates.org/documentation
*/
class OpenExchangeRates
{
	const PROTOCOL_HTTP = 'http';
	const PROTOCOL_HTTPS = 'https';
	
	private $_app_id;
	private $_protocol;
	
	/**
		* API entry point format string.
		* @see self::_request() 
	*/
	private static $_fmt_api = '%s://openexchangerates.org/api/%s.json';
	
	/**
		* @param string mandatory app id, you have to sign up here
		* 	https://openexchangerates.org/signup
		* @param string protocol constant, you can switch to HTTP if you're
		* 	using OpenExchangeRates free plan.
	*/
	function __construct( $app_id, $protocol = self::PROTOCOL_HTTPS )
	{
		$this->_app_id = $app_id;
		$this->_protocol = $protocol;
	}
	
	/**
		* Get supported currencies.
		* @param array optional parameters to add to the request
		* @see http://openexchangerates.org/api/currencies.json
		* @return object all supported currencies
		* @throws Exception if something goes wrong.
	*/
	public function currencies( array $params = array() )
	{
		// When we're using plain old HTTP, there's no need to use an app id.
		$use_app_id = ( $this->_protocol == self::PROTOCOL_HTTPS );
		return $this->_request( 'currencies', $params, $use_app_id );
	}
	
	/**
		* Get latest rates.
		* @param array optional parameters to add to the request
		* @see https://openexchangerates.org/documentation#specification
		* @return object all currencies
		* @throws Exception if something goes wrong.
	*/
	public function latest( array $params = array() )
	{
		return $this->_request( 'latest', $params );
	}
	
	/**
	 * Get historical currencies rates.
	 * @param string date formatted as "YYYY-MM-DD"
	 * @see https://openexchangerates.org/documentation#specification
	 * @return object all currencies for the given date
	*/
	public function historical( $date, array $params = array() )
	{
		return $this->_request( 'historical/'.$date, $params );
	}
	
	/**
		* Actually calls the API to get an answer.
		* @param string api name (latest, currencies …)
		* @param array optionnal parameters to add to the request
		* @param boolean optional add the given app id to the request
		* 	(defaults to true)
		* @return object depending on the called API.
		* @throws Exception if something goes wrong.
	*/
	private function _request( $api_name, array $params = array(),
		$add_app_id = true )
	{
		// If it's needed, we're adding the app id to the request.
		if( $add_app_id )
			$params = array_merge($params, array('app_id'=>$this->_app_id));
		
		$get = http_build_query( $params );
		$url = sprintf( self::$_fmt_api, $this->_protocol, $api_name ).'?'.$get;
		
		$browser = new Browser();
		$response = $browser->get($url);
		
		$json = json_decode( $response->getContent() );
		
		// The JSON couldn't be decoded …
		if( $json === false )
			throw new \Exception("JSON response seems incorrect.");
		
		// An error has occurred …
		if( !empty($json->error) && $json->error == true )
			throw new \Exception("[{$json->status}|{$json->message}] "
				."{$json->description}");
		
		return $json;
	}
}
