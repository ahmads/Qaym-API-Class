<?php

/**
 * Qaym API Class
 * 
 * An implementation of Qaym's public API V0.1
 *
 * Arabic documentation of the API can be found at:
 * http://www.qaym.com/a/api_01_docs
 * 
 * @author		Ahmad Salman <hello@as.sa>
 * @version		1.0
 * @license		MIT License
 * @link		http://github.com/ahmads/Qaym-API-Class/
 */

class QaymAPI
{
	
	/* Constant for the base API URL */
	const API_URL = 'http://api.qaym.com/0.1/';
	
	/* Qaym's API key */
	private $_APIKey;
	/* Last API response received */
	private $_lastResponse;
	/* Last API URL called */
	private $_lastURL;

	/**
	 * Constructor function
	 *
	 * @param string $key Qaym's API Key
	 */
	public function __construct($key = '') {
	
		$this->setAPIKey($key);
	}

	/**
	 * sets the API key that will be used
	 *
	 * @param string $key Qaym's API Key
	 */
	public function setAPIKey($key) {
	
		$this->_APIKey = $key;
	}
	
	/**
	 * Returns all the available Countries
	 *
	 * @return array 
	 */
	public function getCountries() {
	
		$url = $this->_buildURL('countries');
		return $this->_call($url);
	}
	
	/**
	 * Returns the information about the specified Country
	 *
	 * @param integer $countryId the ID of the country
	 * @return array 
	 */
	public function getCountryInfo($countryId) {
		
		$url = $this->_buildURL('countries', $countryId);
		return $this->_call($url);
	}
	
	/**
	 * Returns all the available Cities in the specified Country
	 *
	 * @param integer $countryId the ID of the country
	 * @return array 
	 */
	public function getCountryCities($countryId) {
		
		$url = $this->_buildURL('countries', $countryId, 'cities');
		return $this->_call($url);
	}
	
	/**
	 * Returns all the available Cities
	 *
	 * @return array
	 */
	public function getCities() {
		
		$url = $this->_buildURL('cities');
		return $this->_call($url);
	}
	
	/**
	 * Returns the information about the specified City
	 *
	 * @param integer $cityId the ID of the City
	 * @return array 
	 */
	public function getCityInfo($cityId) {
		
		$url = $this->_buildURL('cities', $cityId);
		return $this->_call($url);
	}

	/**
	 * Returns all the available Restaurants in the specified City
	 *
	 * @param integer $cityId the ID of the City
	 * @return array 
	 */
	public function getCityItems($cityId) {
		
		$url = $this->_buildURL('cities', $cityId, 'items');
		return $this->_call($url);
	}

	/**
	 * Returns the top 50 Restaurants in the specified City
	 *
	 * @param integer $cityId the ID of the City
	 * @return array 
	 */
	public function getCityTopItems($cityId) {
		
		$url = $this->_buildURL('cities', $cityId, 'items/top');
		return $this->_call($url);
	}

	/**
	 * Returns the information about the specified Restaurant
	 *
	 * @param integer $itemId the ID of the Restaurant
	 * @return array 
	 */
	public function getItemInfo($itemId) {
	
		$url = $this->_buildURL('items', $itemId);
		return $this->_call($url);	
	}

	/**
	 * Returns all the available Branches for the specified Restaurant
	 *
	 * @param integer $itemId the ID of the Restaurant
	 * @return array 
	 */
	public function getItemLocations($itemId) {
		
		$url = $this->_buildURL('items', $itemId, 'locations');
		return $this->_call($url);
	}

	/**
	 * Returns all the available Reviews for the specified Restaurant
	 *
	 * @param integer $itemId the ID of the Restaurant
	 * @return array 
	 */
	public function getItemReviews($itemId) {
		
		$url = $this->_buildURL('items', $itemId, 'reviews');
		return $this->_call($url);
	}

	/**
	 * Returns all the available Images for the specified Restaurant
	 *
	 * @param	integer $itemId the ID of the Restaurant
	 * @return	array 
	 */
	public function getItemImages($itemId) {
		
		$url = $this->_buildURL('items', $itemId, 'images');
		return $this->_call($url);
	}

	/**
	 * Returns all the available Votes for the specified Restaurant
	 *
	 * @param	integer $itemId the ID of the Restaurant
	 * @return	array 
	 */
	public function getItemVotes($itemId) {
		
		$url = $this->_buildURL('items', $itemId, 'votes');
		return $this->_call($url);
	}
	
	/**
	 * Returns all the available Tags
	 *
	 * @return	array 
	 */
	public function getTags() {
		
		$url = $this->_buildURL('tags');
		return $this->_call($url);
	}

	/**
	 * Returns all the Restaurants tagged with the specified Tag
	 *
	 * @param	integer $tagId the ID of the Tag
	 * @return	array 
	 */
	public function getTagItems($tagId) {
		
		$url = $this->_buildURL('tags', $tagId, 'items');
		return $this->_call($url);
	}

	/**
	 * Returns the last API response received
	 *
	 * @return array 
	 */
	public function getLastResponse() {
	
		return $this->_lastResponse;
	}
	
	/**
	 * Returns the last URL called
 	 *
	 * @return string 
	 */
	public function getLastURL() {
	
		return $this->_lastURL;
	}
	
	/**
	 * Makes the cURL call and decodes the JSON response
	 *
	 * @param	string $url the url to be called
	 * @return	array 
	 */ 
	private function _call($url) {
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$response = curl_exec($ch);
		curl_close($ch);
		
		$this->_lastResponse = json_decode($response, true);
		
		return $this->_lastResponse;
	}
	
	/**
	 * Constructs the full URL for the API call
	 *
	 * @param		string	$origin		countries, cities, items or tags
	 * @param		integer	$id			the ID of the concerned country/city/item/tag
	 * @param		string	$request	the type of request (for cities, items, votes..etc)
	 * @return		string 
	 */
	private function _buildURL($origin, $id = '', $request = '') {
		
		$key	= $this->_APIKey;
		$url	= self::API_URL;
		
		$url	.= $origin;
		
		if ($id) {
			$url .= '/' . $id;
		}
		
		if ($request) {
			$url .= '/' . $request;
		}
		
		$url .= '/key=' . $key;
		
		$this->_lastURL = $url;
		
		return $this->_lastURL;
	}
}
/* being nice is nice. */
?>