<?php

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../../../library/Zend149/Service/Bitly.php';

/**
 * Test class for Zend149_Service_Bitly.
 * Generated by PHPUnit on 2010-09-09 at 13:14:32.
 */
class Zend149_Service_BitlyTest extends PHPUnit_Framework_TestCase
{

    /**
     * The Bitly service object
     *
     * @var Zend149_Service_Bitly
     */
    protected $_bitly;

    /**
     * Proxy for protected methods of Zend149_Service_Bitly
     *
     * @var Zend149_Service_BitlyProxy
     */
    protected $_bitlyProxy;

    /**
     * Path to test data files
     *
     * @var string
     */
    protected $_filesPath;

    /**
     * HTTP client adapter for testing
     *
     * @var Zend_Http_Client_Adapter_Test
     */
    protected $_httpClientAdapterTest;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_bitly       = new Zend149_Service_Bitly;
        $this->_bitlyProxy  = new Zend149_Service_BitlyProxy;
        $this->_filesPath   = dirname(__FILE__) . '/Bitly/_files';

        /**
         * @see Zend_Http_Client_Adapter_Test
         */
        require_once 'Zend/Http/Client/Adapter/Test.php';
        $this->_httpClientAdapterTest = new Zend_Http_Client_Adapter_Test();
    }

    /**
     * Utility method for returning a string HTTP response, which is loaded from a file
     *
     * @param  string $name
     * @return string
     */
    protected function _loadResponse($name, $format)
    {
        return file_get_contents("$this->_filesPath/$name.$format");
    }

    /**
     * Tests the request method
     *
     * @covers Zend149_Service_Bitly::_request
     */
    public function testRequest()
    {
        $clientStub       = $this->getMock('Zend_Http_Client', array('request'));
        $expectedResponse = 'Dummy';
        $methodPath       = '/path/to/method';
        $expectedParams   =  array (
            'apiKey' => 'kaskdllaksdklasdklakd',
            'format' => 'json',
            'login'  => 'micha149',
        );

        $clientStub->expects($this->once())
                   ->method('request')
                   ->will($this->returnValue($expectedResponse));

        $this->_bitlyProxy->setHttpClient($clientStub);
        $this->_bitlyProxy->_request($methodPath);

        $this->assertEquals('http://api.bit.ly:80'.$methodPath, $clientStub->getUri(TRUE));
        $this->assertAttributeEquals($expectedParams, 'paramsGet', $clientStub);
    }

    /**
     * Tests the shorten method
     * 
     * @covers Zend149_Service_Bitly::shorten
     */
    public function testShorten()
    {
        $this->_bitly->getHttpClient()
                     ->setAdapter($this->_httpClientAdapterTest);

        $this->_bitly->setLogin('micha149')
                     ->setApiKey('asdasdaad');

        $this->_httpClientAdapterTest->setResponse($this->_loadResponse('shorten', 'json'));

        $result = $this->_bitly->shorten('http://example.com/');

        $this->assertTrue($result instanceof Zend149_Service_Bitly_Result_Shorten);
        $this->assertSame(200, $result->getStatusCode());
        $this->assertSame('OK', $result->getStatusText());
        $this->assertEquals('http://example.com', $result->getLongUrl());
        $this->assertEquals('http://bit.ly/atA9Mk', $result->getUrl());
        $this->assertEquals('atA9Mk', $result->getHash());
        $this->assertEquals('4jgguo', $result->getGlobalHash());
        $this->assertFalse($result->isNewHash());
    }

    /**
     * @todo Implement testExpand().
     */
    public function testExpand()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Tests the api key getter
     *
     * @covers Zend149_Service_Bitly::getApiKey
     */
    public function testGetApiKey()
    {
        $bitly    = $this->_bitly;
        $expected = 'adadkasdklasldklasdklaklsdkl';

        $bitly->setApiKey($expected);

        $this->assertEquals($expected, $bitly->getApiKey());
    }

    /**
     * Tests the api key getter with a missing value. An exception is expected
     *
     * @covers Zend149_Service_Bitly::getapiKey
     * @expectedException Zend149_Service_Bitly_Exception
     */
    public function testGetApiKeyWithoutValue()
    {
        $bitly = $this->_bitly;
        $bitly->getApiKey();
    }

    /**
     * Tests the api key setter
     * 
     * @covers Zend149_Service_Bitly::setApiKey
     */
    public function testSetApiKey()
    {
        $bitly    = $this->_bitly;
        $expected = 'adadkasdklasldklasdklaklsdkl';

        $bitly->setApiKey($expected);

        $this->assertAttributeEquals($expected, '_apiKey', $bitly);
    }

    /**
     * Tests the login getter
     *
     * @covers Zend149_Service_Bitly::getLogin
     */
    public function testGetLogin()
    {
        $bitly    = $this->_bitly;
        $expected = 'micha149';

        $bitly->setLogin($expected);

        $this->assertEquals($expected, $bitly->getLogin());
    }

    /**
     * Tests the login getter with a missing login value. An exception is expected
     *
     *
     * @covers Zend149_Service_Bitly::getLogin
     * @expectedException Zend149_Service_Bitly_Exception
     */
    public function testGetLoginWithoutValue()
    {
        $bitly = $this->_bitly;
        $bitly->getLogin();
    }
    
    /**
     * Tests the login setter
     *
     * @covers Zend149_Service_Bitly::setLogin
     */
    public function testSetLogin()
    {
        $bitly    = $this->_bitly;
        $expected = 'micha149';

        $bitly->setLogin($expected);
        
        $this->assertAttributeEquals($expected, '_login', $bitly);
    }

    /**
     * Tests the format setter with valid formats
     *
     * @covers Zend149_Service_Bitly::setFormat
     * @dataProvider validFormatsDataProvider
     */
    public function testSetValidFormat($format)
    {
        $this->_bitly->setFormat($format);
        $this->assertAttributeEquals($format, '_format', $this->_bitly);
    }

    /**
     * Data provider for valid result formats
     *
     * @return array Array with format strings
     */
    public function validFormatsDataProvider() {
        return array(
            array('object'),
            array('json'),
            array('txt'),
            array('xml'),
        );
    }

    /**
     * Tests the format setter with an invalid format. An Exception should come up.
     *
     * @covers Zend149_Service_Bitly::setFormat
     * @expectedException Zend149_Service_Bitly_Exception
     */
    public function testSetInvalidFormat()
    {
        $bitly = $this->_bitly;
        $bitly->setFormat('hurz');
    }

    /**
     * Test the format getter
     *
     * @covers Zend149_Service_Bitly::getFormat
     */
    public function testGetFormat()
    {
        $bitly = $this->_bitly;
        $expected = 'json';

        $bitly->setFormat($expected);

        $result = $bitly->getFormat();
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the format getter with default value
     *
     * @covers Zend149_Service_Bitly::getFormat
     */
    public function testGetDefaultFormat()
    {
        $bitly = $this->_bitly;
        $result = $bitly->getFormat();
        $this->assertEquals('object', $result);
    }

}

class Zend149_Service_BitlyProxy extends Zend149_Service_Bitly
{
    
    public function getFormat()
    {
        return 'json';
    }

    public function getApiKey()
    {
        return 'kaskdllaksdklasdklakd';
    }

    public function getLogin()
    {
        return 'micha149';
    }

    public function  _request($path, array $params = array()) {
        return parent::_request($path, $params);
    }
}