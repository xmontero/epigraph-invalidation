<?php
class IconTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Xmontero\EpigraphInvalidation\IconModel;
	}
	
	public function testSize()
	{
		$this->assertEquals( 4, $this->sut->getSize() );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
}
