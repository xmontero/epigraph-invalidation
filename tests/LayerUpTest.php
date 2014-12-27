<?php

use Xmontero\EpigraphInvalidation\LayerUp;

class LayerUpTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerUp( 4, 4 );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testSizes()
	{
		$width = 4;
		$height = 5;
		
		$sut = new LayerUp( $width, $height );
		$this->assertEquals( $width, $sut->getDotWidth() );
		$this->assertEquals( $height, $sut->getDotHeight() );
	}
}
