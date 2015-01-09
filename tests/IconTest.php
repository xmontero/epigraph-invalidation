<?php
namespace Xmontero\EpigraphInvalidation\Tests;

use Xmontero\EpigraphInvalidation\Icon;

class IconTest extends \PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Icon;
	}
	
	public function testSize()
	{
		$defaultSize = 4;
		$customConstructorSize = 7;
		$customSetterSize = 9;
		
		$sut = $this->sut;
		$this->assertEquals( $defaultSize, $sut->getSize() );
		
		$sut = new Icon( $customConstructorSize );
		$this->assertEquals( $customConstructorSize, $sut->getSize() );
		
		$sut->setSize( $customSetterSize );
		$this->assertEquals( $customSetterSize, $sut->getSize() );
	}
	
	public function testLayerDown()
	{
		$size = 13;
		
		$this->sut->setSize( $size );
		$layerDown = $this->sut->getLayerDown();
		
		$this->assertInstanceOf( 'Xmontero\EpigraphInvalidation\Layers\Down', $layerDown );
		$this->assertEquals( $size, $layerDown->getDotWidth() );
		$this->assertEquals( $size, $layerDown->getDotHeight() );
		$this->assertEquals( $size + 1, $layerDown->getVertexWidth() );
		$this->assertEquals( $size + 1, $layerDown->getVertexHeight() );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
}
