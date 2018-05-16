<?php 
declare(strict_types = 1);
namespace BlogConnection\Tests;

use BlogConnection\XMLRPC;
use BlogConnection\WPObject;

class XMLRPCTest extends \PHPUnit_Framework_TestCase
{
    protected $_conn;

    protected function setUp()
    {
        $wordpress = new WPObject(
            'https://testugagua.com.br',
            'root',
            'root'
        );
        $wordpress->setTitle( 'test' );
        $wordpress->setContent( 'test' );

        $this->_conn = new XMLRPC( $wordpress, true );
        $this->_conn->insertPost();
    }
    
    public function testInstanceOf() 
	{
        $this->assertInstanceOf(XMLRPC::class, $this->_conn);
    }
  
    public function testConnectErrorIsTrue()
    {
        $this->assertEquals(true, $this->_conn->getConnectError());
    }

    public function testConnectErrorMessageExists()
    {
        $this->assertInternalType('string', $this->_conn->getErrorMessage());
    }

    public function testStatusCode()
    {
        $this->assertEquals(500, $this->_conn->getStatusCode());
    }

    /**
     * @expectedException Exception
     */
    public function verifyDebugException()
    {
        $wordpress = new WPObject(
            'https://urltestwpobject.com.br',
            'root',
            'root'
        );
        $wordpress->setTitle( 'test' );
        $wordpress->setContent( 'test' );

        $xml = new XMLRPC( $wordpress, true );
        $xml->insertPost();
    }

    public function testLogPathIsDir()
    {

        $dir = is_dir($this->_conn->getPath());
        $this->assertEquals(true, $dir);
    }

    public function testLogIsString()
    {
        $name = $this->_conn->getLogName();
        $this->assertInternalType('string',$name);
    }
}