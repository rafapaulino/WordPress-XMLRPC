<?php 
declare(strict_types = 1);
namespace BlogConnection\Tests;

use BlogConnection\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    protected $_conn1;
    protected $_conn2;

    protected function setUp()
    {
		$this->_conn1 = new Connection(
            'https://imaginaryurlhere.com.br',
            'sonic',
            'rafaeltest'
        );

        $this->_conn2 = new Connection(
            'https://www.google.com.br',
            'sonic',
            'rafaeltest'
        );
    }
    
    public function testInstanceOfConnection() 
	{
        $this->assertInstanceOf(Connection::class, $this->_conn1);
    }
    
    public function testTypeOfWebSite()
    {
        $this->assertInternalType('string', $this->_conn1->getWebsite());
    }

    public function testTypeOfLogin()
    {
        $this->assertInternalType('string', $this->_conn1->getLogin());
    }

    public function testTypeOfPassword()
    {
        $this->assertInternalType('string', $this->_conn1->getPassword());
    }

    public function testConnectErrorIsTrue()
    {
        $this->assertEquals(true, $this->_conn1->getConnectError());
    }

    public function testConnectErrorMessageExists()
    {
        $this->assertInternalType('string', $this->_conn1->getErrorMessage());
    }

    public function testStatusCode()
    {
        $this->assertEquals(500, $this->_conn1->getStatusCode());
    }

    /**
     * @expectedException Exception
     */
    public function verifyDebugException()
    {
        $conn = new Connection(
            'https://imaginaryurlhere.com.br',
            'sonic',
            'rafaeltest',
            true
        );
    }

    public function testConnectErrorIsFalse()
    {
        $this->assertEquals(false, $this->_conn2->getConnectError());
    }

    public function testStatusCodeIs200()
    {
        $this->assertEquals(200, $this->_conn2->getStatusCode());
    }

    public function testLogPathIsDir()
    {
        $dir = is_dir($this->_conn1->getLogPath());
        $this->assertEquals(true, $dir);
    }

    public function testLogIsString()
    {
        $name = $this->_conn1->getLogName();
        $this->assertInternalType('string',$name);
    }
}