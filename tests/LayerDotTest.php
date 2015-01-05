<?php

use Xmontero\EpigraphInvalidation\LayerDot;

class LayerDotTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerDot;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	//---------------------------------------------------------------------//
	// Filled.                                                             //
	//---------------------------------------------------------------------//
	
	public function testDefaultFilled()
	{
		$this->assertFalse( $this->sut->isFilled() );
	}
		
	public function testSetFilled()
	{
		$this->sut->setFilled( true );
		$this->assertTrue( $this->sut->isFilled() );
		
		$this->sut->setFilled( false );
		$this->assertFalse( $this->sut->isFilled() );
	}
	
	public function testFillClear()
	{
		$this->sut->fill();
		$this->assertTrue( $this->sut->isFilled() );
		
		$this->sut->clear();
		$this->assertFalse( $this->sut->isFilled() );
	}
	
	//---------------------------------------------------------------------//
	// Straight neighbours.                                                //
	//---------------------------------------------------------------------//
	
	public function testNoNeighboursAddedReturnsNull()
	{
		// +---+
		// |sut|
		// +---+
		
		$this->assertNull( $this->sut->getLeftNeighbour() );
		$this->assertNull( $this->sut->getRightNeighbour() );
		$this->assertNull( $this->sut->getTopNeighbour() );
		$this->assertNull( $this->sut->getBottomNeighbour() );
	}
	
	public function testSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonals()
	{
		//     +---+
		//     | T |
		// +---+---+---+
		// | L |sut| R |
		// +---+---+---+
		//     | B |
		//     +---+
		
		$dotLeft = new LayerDot;
		$dotRight = new LayerDot;
		$dotTop = new LayerDot;
		$dotBottom = new LayerDot;
		
		$this->sut->setLeftNeighbour( $dotLeft );
		$this->sut->setRightNeighbour( $dotRight );
		$this->sut->setTopNeighbour( $dotTop );
		$this->sut->setBottomNeighbour( $dotBottom );
		
		$this->assertSame( $dotLeft, $this->sut->getLeftNeighbour() );
		$this->assertSame( $dotRight, $this->sut->getRightNeighbour() );
		$this->assertSame( $dotTop, $this->sut->getTopNeighbour() );
		$this->assertSame( $dotBottom, $this->sut->getBottomNeighbour() );
		
		$this->assertNull( $this->sut->getTopLeftNeighbour() );
		$this->assertNull( $this->sut->getTopRightNeighbour() );
		$this->assertNull( $this->sut->getBottomLeftNeighbour() );
		$this->assertNull( $this->sut->getBottomRightNeighbour() );
	}
	
	//---------------------------------------------------------------------//
	// Diagonal neighbours.                                                //
	//---------------------------------------------------------------------//
	
	public function testFullNeighboursAddedReturnObjectsOnEdgesAndDiagonals()
	{
		// +---+---+---+
		// |TL | T |TR |
		// +---+---+---+
		// | L |sut| R |
		// +---+---+---+
		// |BL | B |BR |
		// +---+---+---+
		
		$dotLeft = new LayerDot;
		$dotRight = new LayerDot;
		$dotTop = new LayerDot;
		$dotBottom = new LayerDot;
		
		$dotTopLeft = new LayerDot;
		$dotTopRight = new LayerDot;
		$dotBottomLeft = new LayerDot;
		$dotBottomRight = new LayerDot;
		
		$dotLeft->setTopNeighbour( $dotTopLeft );
		$dotLeft->setBottomNeighbour( $dotBottomLeft );
		$dotRight->setTopNeighbour( $dotTopRight );
		$dotRight->setBottomNeighbour( $dotBottomRight );
		$dotTop->setLeftNeighbour( $dotTopLeft );
		$dotTop->setRightNeighbour( $dotTopRight );
		$dotBottom->setLeftNeighbour( $dotBottomLeft );
		$dotBottom->setRightNeighbour( $dotBottomRight );
		
		$this->sut->setLeftNeighbour( $dotLeft );
		$this->sut->setRightNeighbour( $dotRight );
		$this->sut->setTopNeighbour( $dotTop );
		$this->sut->setBottomNeighbour( $dotBottom );
		
		$this->assertSame( $dotLeft, $this->sut->getLeftNeighbour() );
		$this->assertSame( $dotRight, $this->sut->getRightNeighbour() );
		$this->assertSame( $dotTop, $this->sut->getTopNeighbour() );
		$this->assertSame( $dotBottom, $this->sut->getBottomNeighbour() );
		
		$this->assertSame( $dotTopLeft, $this->sut->getTopLeftNeighbour() );
		$this->assertSame( $dotTopRight, $this->sut->getTopRightNeighbour() );
		$this->assertSame( $dotBottomLeft, $this->sut->getBottomLeftNeighbour() );
		$this->assertSame( $dotBottomRight, $this->sut->getBottomRightNeighbour() );
	}
	
	public function testStraightNeighboursAreAddedByReferenceByTestingDiagonals()
	{
		// +---+---+
		// |TL | T |
		// +---+---+
		//     |sut|
		//     +---+
		
		$dotTop = new LayerDot;
		$dotTopLeft = new LayerDot;
		
		$this->sut->setTopNeighbour( $dotTop );
		
		// Add to the external dotLeft reference.
		$dotTop->setLeftNeighbour( $dotTopLeft );
		
		// Test object was also added in the internel dotLeftReference.
		$this->assertSame( $dotTopLeft, $this->sut->getTopNeighbour()->getLeftNeighbour() );
	}
	
	/**
	 * @dataProvider providerIncoherentDiagonalNeighboursThrowException
	 */
	public function testIncoherentDiagonalNeighboursThrowException( $corner )
	{
		//   +---+---+---+
		//   |TLT|   |TRT|
		//   +---+   +---+
		// +---+ |   | +---+
		// |TLL| | T | |TRR|
		// +---+-+---+-----+
		// |   L |sut| R   |
		// +---+-+---+-+---+
		// |BLL| | B | |BRR|
		// +---+ |   | +---+
		//   +---+   +---+
		//   |BLB|   |BRB|
		//   +---+   +---+
		
		$dotLeft = new LayerDot;
		$dotRight = new LayerDot;
		$dotTop = new LayerDot;
		$dotBottom = new LayerDot;
		
		$dotTopLeftTop = new LayerDot;
		$dotTopLeftLeft = new LayerDot;
		$dotTopRightTop = new LayerDot;
		$dotTopRightRight = new LayerDot;
		$dotBottomLeftBottom = new LayerDot;
		$dotBottomLeftLeft = new LayerDot;
		$dotBottomRightBottom = new LayerDot;
		$dotBottomRightRight = new LayerDot;
		
		$dotLeft->setTopNeighbour( $dotTopLeftLeft );
		$dotLeft->setBottomNeighbour( $dotBottomLeftLeft );
		$dotRight->setTopNeighbour( $dotTopRightRight );
		$dotRight->setBottomNeighbour( $dotBottomRightRight );
		$dotTop->setLeftNeighbour( $dotTopLeftTop );
		$dotTop->setRightNeighbour( $dotTopRightTop );
		$dotBottom->setLeftNeighbour( $dotBottomLeftBottom );
		$dotBottom->setRightNeighbour( $dotBottomRightBottom );
		
		$this->sut->setLeftNeighbour( $dotLeft );
		$this->sut->setRightNeighbour( $dotRight );
		$this->sut->setTopNeighbour( $dotTop );
		$this->sut->setBottomNeighbour( $dotBottom );
		
		$this->assertSame( $dotLeft, $this->sut->getLeftNeighbour() );
		$this->assertSame( $dotRight, $this->sut->getRightNeighbour() );
		$this->assertSame( $dotTop, $this->sut->getTopNeighbour() );
		$this->assertSame( $dotBottom, $this->sut->getBottomNeighbour() );
		
		$this->setExpectedException( 'RuntimeException' );
		
		switch( $corner )
		{
			case 'topLeft':
				
				$dummy = $this->sut->getTopLeftNeighbour();
				break;
				
			case 'topRight':
				
				$dummy = $this->sut->getTopRightNeighbour();
				break;
				
			case 'bottomLeft':
				
				$dummy = $this->sut->getBottomLeftNeighbour();
				break;
				
			case 'bottomRight':
				
				$dummy = $this->sut->getBottomRightNeighbour();
				break;
		}
	}
	
	public function providerIncoherentDiagonalNeighboursThrowException()
	{
		$data = array
		(
			array( 'topLeft' ),
			array( 'topRight' ),
			array( 'bottomLeft' ),
			array( 'bottomRight' ),
		);
		
		return $data;
	}
	
	//---------------------------------------------------------------------//
	// Neighbours of neighbours.                                           //
	//---------------------------------------------------------------------//
	
	public function testSutIsAutomaticallyAddedAsNeighbourOfTheNeighbour()
	{
		//     +---+
		//     | T |
		// +---+---+---+
		// | L |sut| R |
		// +---+---+---+
		//     | B |
		//     +---+
		
		$dotLeft = new LayerDot;
		$dotRight = new LayerDot;
		$dotTop = new LayerDot;
		$dotBottom = new LayerDot;
		
		$this->sut->setLeftNeighbour( $dotLeft );
		$this->sut->setRightNeighbour( $dotRight );
		$this->sut->setTopNeighbour( $dotTop );
		$this->sut->setBottomNeighbour( $dotBottom );
		
		$this->assertSame( $this->sut, $this->sut->getLeftNeighbour()->getRightNeighbour() );
		$this->assertSame( $this->sut, $this->sut->getRightNeighbour()->getLeftNeighbour() );
		$this->assertSame( $this->sut, $this->sut->getTopNeighbour()->getBottomNeighbour() );
		$this->assertSame( $this->sut, $this->sut->getBottomNeighbour()->getTopNeighbour() );
	}
}
