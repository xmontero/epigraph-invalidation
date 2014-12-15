<?php
class IconTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Xmontero\EpigraphInvalidation\Icon;
	}
	
	public function testSize()
	{
		$defaultSize = 4;
		$customConstructorSize = 7;
		$customSetterSize = 9;
		
		$sut = $this->sut;
		$this->assertEquals( $defaultSize, $sut->getSize() );
		
		$sut = new Xmontero\EpigraphInvalidation\Icon( $customConstructorSize );
		$this->assertEquals( $customConstructorSize, $sut->getSize() );
		
		$sut->setSize( $customSetterSize );
		$this->assertEquals( $customSetterSize, $sut->getSize() );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
}
