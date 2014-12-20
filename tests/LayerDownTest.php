<?php

use Xmontero\EpigraphInvalidation\LayerDown;

class LayerDownTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerDown( 4, 4 );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testSizes()
	{
		$width = 4;
		$height = 5;
		
		$sut = new LayerDown( $width, $height, false );
		$this->assertEquals( $width, $sut->getDotWidth() );
		$this->assertEquals( $height, $sut->getDotHeight() );
		$this->assertEquals( $width + 1, $sut->getVertexWidth() );
		$this->assertEquals( $height + 1, $sut->getVertexHeight() );
	}
	
	public function testSetGetDot()
	{
		$this->sut->setDot( true, 3, 3 );
		$this->sut->setDot( false, 4, 1 );
		
		$this->assertEquals( true, $this->sut->getDot( 3, 3 ) );
		$this->assertEquals( false, $this->sut->getDot( 4, 1 ) );
	}
}
