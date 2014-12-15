<?php

class LayerDownDotTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Xmontero\EpigraphInvalidation\LayerDownDot;
	}
	
	public function testFace()
	{
		$this->assertTrue( $this->sut->getFilled() );
		
		$this->sut->setFilled( false );
		$this->assertFalse( $this->sut->getFilled() );
		
		$this->sut->setFilled( true );
		$this->assertTrue( $this->sut->getFilled() );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
}
