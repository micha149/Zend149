<?php

require_once 'Zend/Service/Abstract.php';

class Zend_Service_Bitly extends Zend_Service_Abstract
{
    /**
     * Url to the bit.ly API
     */
    const URI_BASE = 'http://api.bit.ly/';

    /**
     * The used bit.ly API key
     *
     * @var string
     */
    protected $_apiKey;
    
    /**
     * The used bit.ly user id
     *
     * @var string
     */
    protected $_userId;
    
    /**
     * Format of response
     * 
     * @var string
     */
    protected $_format = 'object';
    
    /**
     * Performs object initializations
     *
     *  # Sets up character encoding
     *  # Saves the user ID
     *  # Saves the API key
     *
     * @param  string $apiKey Your Flickr API key
     * @return void
     */
    public function __construct($userId = '', $apiKey = '')
    {
        if (!extension_loaded('iconv')) {
            throw new Zend_Service_Bitly_Exception('Extension "iconv" is not loaded!');
        }
        
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');
        $this->userId = (string) $userId;
        $this->apiKey = (string) $apiKey;
    }
       
    /**
     * Processes the HTTP Request to the API
     *
     * @param string $path Pathname of API method
     * @param array $params Associative array with api method parameters
     * @return Zend_Http_Response HTTP Response Object
     */
    protected function _request($path, array $params = array())
    {
        $client = $this->getHttpClient();
        $client->resetParameters();
        
        // adding base uri
        $client->setUri(self::URI_BASE);
       
        $params = array_merge(array(
            'apiKey' => $this->getApiKey(),
            'login'  => $this->getUserId(),
            'format' => $this->getFormat(),
        ), $params);
       
        $client->getUri()->setPath($path);
        $client->setParameterGet($params);

        return $client->request();
    }
    
    /**
     * Wrappes the http response
     * 
     * This method depends on the property <$format>. If this property ist equal
     * to 'object', this method will return an Zend_Service_Bitly_Result object.
     * In other cases, the http response body is returned.
     * 
     * @param Zend_Http_Response $response Response object
     */
    protected function _createResult(Zend_Http_Response $response)
    {
        if ($this->getFormat() == 'object') {
            return new Zend_Service_Bitly_Result($response);
        }
        return $response->getBody();
    }
       
    /**
     * Shorten an URL by using the API
     *
     * @param string URL to shorten
     */
    public function shorten($longUrl)
    {
        $response = $this->_request('/v3/shorten', array(
            'longUrl' => $longUrl
        ));
        
        return $this->_createResult($response);
    }
       
    /**
     * Given a bit.ly URL or hash (or multiple), this method
     * decodes it by using the API
     *
     * @param string|array $hash One or more hashes or short URLs
     * @return 
     */
    public function expand($hash)
    {
        $params = array();
        // If there is any slash in the string, it should be an url
        if (strpos($hash, '/') !== FALSE) {
            $params['shortUrl'] = $hash;
        } else {
            $params['hash'] = $hash;
        }

        $response = $this->_request('/v3/expand', $params);

        return $this->_createResult($response);
    }
    
    /**
     * Get the API key
     * 
     * @return string
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }
    
    /**
     * Set the API key
     * 
     * @param  string $apikey
     * @return Zend_Service_Bitly
     */
    public function setApiKey($apikey = '')
    {
        $this->_apiKey = $apikey;
        return $this;
    }
    
    /**
     * Get the User ID
     * 
     * @return string
     */
    public function getUserId()
    {
        return $this->_userId;
    }
    
    /**
     * Set the User ID
     * 
     * @param  string $userId
     * @return Zend_Service_Bitly
     */
    public function setUserId($userId = '')
    {
        $this->_userId = $userId;
        return $this;
    }
    
    /**
     * Get the request format
     * 
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }
    
    /**
     * Set the request format
     * 
     * @param  string $format
     * @return Zend_Service_Bitly
     */
    public function setFormat($format)
    {
        $allowed = array('object', 'json');
        
        if (in_array($format, $allowed))
        {
            throw new Zend_Service_Bitly_Exception("Response format '" . $format . "' is not supported");
        }
        $this->_format = $format;
        return $this;
    }
}