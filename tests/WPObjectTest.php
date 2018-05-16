<?php 
declare(strict_types = 1);
namespace BlogConnection\Tests;

use BlogConnection\WPObject;

class WPOBjectTest extends \PHPUnit_Framework_TestCase
{
    protected $_wp;

    protected function setUp()
    {
		$this->_wp = new WPObject(
            'https://imaginaryurlhere.com.br',
            'sonic',
            'rafaeltest'
        );

        $this->_wp->setTitle( 'test title' );
        $this->_wp->setContent( '   test content' );
    }
    
    public function testInstanceOf() 
	{
        $this->assertInstanceOf(WPObject::class, $this->_wp);
    }
    
    public function testType()
    {
        $this->assertInternalType('array', $this->_wp->getPost());
    }

    public function testLength()
    {
        $this->assertCount(7, $this->_wp->getPost());
    }

    public function testHasTitle()
    {
        $this->assertArrayHasKey('title', $this->_wp->getPost());
    }

    public function testHasDescrition()
    {
        $this->assertArrayHasKey('description', $this->_wp->getPost());
    }

    public function testTypeOfLogin()
    {
        $this->assertInternalType('string', $this->_wp->getLogin());
    }

    public function testTypeOfWebSite()
    {
        $this->assertInternalType('string', $this->_wp->getWebsite());
    }

    public function testTypeOfPassword()
    {
        $this->assertInternalType('string', $this->_wp->getPassword());
    }
}