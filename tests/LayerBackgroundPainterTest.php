<?php

use Xmontero\EpigraphInvalidation\LayerBackgroundPainter;

class LayerBackgroundPainterTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerBackgroundPainter;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testTrue()
	{
		$this->markTestIncomplete();
		
		$this->assertTrue( false );
	}
}
