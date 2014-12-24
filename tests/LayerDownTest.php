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
		
		$sut = new LayerDown( $width, $height );
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
		$sut = new LayerDown( $width, $height );
		
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
	
	public function testDotsAreNeighbours()
	{
		$x = 1;
		$y = 2;
		
		$topLeftNeighbour     = $this->sut->getDot( $x - 1, $y - 1 );
		$topNeighbour         = $this->sut->getDot( $x,     $y - 1 );
		$topRightNeighbour    = $this->sut->getDot( $x + 1, $y - 1 );
		
		$leftNeighbour        = $this->sut->getDot( $x - 1, $y );
		$dot                  = $this->sut->getDot( $x,     $y );
		$rightNeighbour       = $this->sut->getDot( $x + 1, $y );
		
		$bottomLeftNeighbour  = $this->sut->getDot( $x - 1, $y + 1 );
		$bottomNeighbour      = $this->sut->getDot( $x,     $y + 1 );
		$bottomRightNeighbour = $this->sut->getDot( $x + 1, $y + 1 );
		
		$this->assertSame( $topLeftNeighbour,     $dot->getTopLeftNeighbour()     );
		$this->assertSame( $topNeighbour,         $dot->getTopNeighbour()         );
		$this->assertSame( $topRightNeighbour,    $dot->getTopRightNeighbour()    );
		
		$this->assertSame( $leftNeighbour,        $dot->getLeftNeighbour()        );
		$this->assertSame( $rightNeighbour,       $dot->getRightNeighbour()       );
		
		$this->assertSame( $bottomLeftNeighbour,  $dot->getBottomLeftNeighbour()  );
		$this->assertSame( $bottomNeighbour,      $dot->getBottomNeighbour()      );
		$this->assertSame( $bottomRightNeighbour, $dot->getBottomRightNeighbour() );
	}
	
	/**
	 * @dataProvider providerUnderlyingVertexes
	 */
	public function testUnderlyingVertexes( $vertexX, $vertexY, $state)
	{
		$this->markTestIncomplete( "Missing to test all four dots around the vertex, only testing bottom-right dot around vertex currently." );
		
		$this->sut->setVertex( true, $vertexX, $vertexY );
		$dot = $this->sut->getDotOrNull( $vertexX, $vertexY );
		
		if( ! is_null( $dot ) )
		{
			$this->assertFalse( $dot->getTopLeftVertex() );
		}
		
		$top  = $this->sut->getDotOrNull( $vertexX - 1, $vertexY );
		$left = $this->sut->getDotOrNull( $vertexX, $vertexY - 1 );
		
		is_null( $top )  ? null : $top->fill();
		is_null( $left ) ? null : $left->fill();
		
		if( ! is_null( $dot ) )
		{
			$this->assertEquals( $state, $dot->getTopLeftVertex() );
		}
	}
	
	public function providerUnderlyingVertexes()
	{
		$data = array
		(
			array( 0, 0, false ),
			array( 1, 0, false ),
			array( 4, 0, false ),
			array( 0, 2, false ),
			array( 1, 2, true ),
			array( 3, 2, true ),
			array( 2, 3, true ),
			array( 3, 3, true ),
			array( 4, 2, false ),
			array( 0, 4, false ),
			array( 1, 4, false ),
			array( 4, 4, false ),
		);
		
		return $data;
	}
}
