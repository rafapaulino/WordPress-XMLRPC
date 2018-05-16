<?php 
declare(strict_types = 1);
namespace BlogConnection\Tests;

use BlogConnection\Spintax;

class SpintaxTest extends \PHPUnit_Framework_TestCase
{
    protected $_obj;

    protected function setUp()
    {
		$this->_obj = new Spintax();
    }
    
    public function testInstanceOf() 
	{
        $this->assertInstanceOf(Spintax::class, $this->_obj);
    }
    
    public function testTypeOfProcess()
    {
        $title = '{{Inglês} É Bom? Vale {A} Pena? Bônus Extras |Carol Me Ensina {Como} Aprender {Inglês} }';
        $title = $this->_obj->process($title);
        $this->assertInternalType('string', $title);
    }
}