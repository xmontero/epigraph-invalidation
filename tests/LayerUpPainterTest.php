<?php

use Xmontero\EpigraphInvalidation\LayerUpPainter;

class LayerUpPainterTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerUpPainter;
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
