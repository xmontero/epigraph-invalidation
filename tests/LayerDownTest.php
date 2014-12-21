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
	
	public function testSetGetFaceOnDot()
	{
		$this->sut->getDot( 2, 2 )->setFilled( true );
		$this->sut->getDot( 3, 0 )->setFilled( false );
		$this->sut->getDot( 0, 2 )->setFilled( true );
		$this->sut->getDot( 1, 3 )->setFilled( false );
		
		$this->assertTrue( $this->sut->getDot( 2, 2 )->isFilled() );
		$this->assertFalse( $this->sut->getDot( 3, 0 )->isFilled() );
		$this->assertNotEquals( $this->sut->getDot( 0, 2 )->isFilled(), $this->sut->getDot( 1, 3 )->isFilled() );
	}
	
	public function testGetDotReturnsProperType()
	{
		$dot = $this->sut->getDot( 0, 2 );
		$this->assertInstanceOf( 'Xmontero\EpigraphInvalidation\LayerDownDot', $dot );
	}
	
	/**
	 * @dataProvider providerGetDotOutOfBoundsThrowsException
	 */
	public function testGetDotOutOfBoundsThrowsException( $width, $height, $x, $y )
	{
		$sut = new LayerDown( $width, $height, false );
		
		$this->setExpectedException( 'OutOfBoundsException' );
		$sut->getDot( $x, $y );
	}
	
	public function providerGetDotOutOfBoundsThrowsException()
	{
		$data = array
		(
			array( 4, 5, -1,  0 ),
			array( 4, 5, -1, -3 ),
			array( 4, 5,  0, -1 ),
			array( 4, 5,  4,  0 ),
			array( 4, 5,  5,  7 ),
			array( 4, 5,  0,  5 ),
			array( 4, 5, -2,  7 ),
			array( 4, 5,  5, -3 ),
		);
		
		return $data;
	}
}
