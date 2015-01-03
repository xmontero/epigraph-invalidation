<?php

use Xmontero\EpigraphInvalidation\LayerUpDot;

class LayerUpDotTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerUpDot;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	//---------------------------------------------------------------------//
	// Filled.                                                             //
	//---------------------------------------------------------------------//
	
	public function testDefaultIsNotFilled()
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
	
	/**
	 * @dataProvider providerSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridge
	 */
	public function testSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridgeSetAfterNeighbour( $leftBridge, $rightBridge, $topBridge, $bottomBridge )
	{
		$this->helperSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridge( false, $leftBridge, $rightBridge, $topBridge, $bottomBridge );
	}
		
	/**
	 * @dataProvider providerSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridge
	 */
	public function testSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridgeSetBeforeNeighbour( $leftBridge, $rightBridge, $topBridge, $bottomBridge )
	{
		$this->helperSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridge( true, $leftBridge, $rightBridge, $topBridge, $bottomBridge );
	}
		
	private function helperSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridge( $bridgeSetBeforeNeighbour, $leftBridge, $rightBridge, $topBridge, $bottomBridge )
	{
		//     +---+
		//     | T |
		// +---+---+---+
		// | L |sut| R |
		// +---+---+---+
		//     | B |
		//     +---+
		
		//$this->markTestIncomplete();
		
		$bridgeSetAfterNeighbour = ( ! $bridgeSetBeforeNeighbour );
		
		$dotLeft = new LayerUpDot;
		$dotRight = new LayerUpDot;
		$dotTop = new LayerUpDot;
		$dotBottom = new LayerUpDot;
		
		if( $bridgeSetBeforeNeighbour )
		{
			$this->sut->setLeftBridge( $leftBridge );
			$this->sut->setRightBridge( $rightBridge );
			$this->sut->setTopBridge( $topBridge );
			$this->sut->setBottomBridge( $bottomBridge );
		}
		
		$this->sut->setLeftNeighbour( $dotLeft );
		$this->sut->setRightNeighbour( $dotRight );
		$this->sut->setTopNeighbour( $dotTop );
		$this->sut->setBottomNeighbour( $dotBottom );
		
		if( $bridgeSetAfterNeighbour )
		{
			$this->sut->setLeftBridge( $leftBridge );
			$this->sut->setRightBridge( $rightBridge );
			$this->sut->setTopBridge( $topBridge );
			$this->sut->setBottomBridge( $bottomBridge );
		}
		
		$this->assertSame( $dotLeft, $this->sut->getLeftNeighbour() );
		$this->assertSame( $dotRight, $this->sut->getRightNeighbour() );
		$this->assertSame( $dotTop, $this->sut->getTopNeighbour() );
		$this->assertSame( $dotBottom, $this->sut->getBottomNeighbour() );
		
		$this->assertNull( $this->sut->getTopLeftNeighbour() );
		$this->assertNull( $this->sut->getTopRightNeighbour() );
		$this->assertNull( $this->sut->getBottomLeftNeighbour() );
		$this->assertNull( $this->sut->getBottomRightNeighbour() );
		
		$this->assertEquals( $leftBridge, $this->sut->getLeftBridge() );
		$this->assertEquals( $rightBridge, $this->sut->getRightBridge() );
		$this->assertEquals( $topBridge, $this->sut->getTopBridge() );
		$this->assertEquals( $bottomBridge, $this->sut->getBottomBridge() );
		
		$this->assertEquals( $leftBridge, $dotLeft->getRightBridge() );
		$this->assertEquals( $rightBridge, $dotRight->getLeftBridge() );
		$this->assertEquals( $topBridge, $dotTop->getBottomBridge() );
		$this->assertEquals( $bottomBridge, $dotBottom->getTopBridge() );
	}
	
	public function providerSimpleNeighboursAddedReturnObjectsOnEdgesAndNullOnDiagonalsAndProperBridge()
	{
		$data = array
		(
			array( false, false, false, false ),
			array( false, false, false, true  ),
			array( false, false, true,  false ),
			array( false, false, true,  true  ),
			
			array( false, true,  false, false ),
			array( false, true,  false, true  ),
			array( false, true,  true,  false ),
			array( false, true,  true,  true  ),
			
			array( true,  false, false, false ),
			array( true,  false, false, true  ),
			array( true,  false, true,  false ),
			array( true,  false, true,  true  ),
			
			array( true,  true,  false, false ),
			array( true,  true,  false, true  ),
			array( true,  true,  true,  false ),
			array( true,  true,  true,  true  ),
		);
		
		return $data;
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
		
		$dotLeft = new LayerUpDot;
		$dotRight = new LayerUpDot;
		$dotTop = new LayerUpDot;
		$dotBottom = new LayerUpDot;
		
		$dotTopLeft = new LayerUpDot;
		$dotTopRight = new LayerUpDot;
		$dotBottomLeft = new LayerUpDot;
		$dotBottomRight = new LayerUpDot;
		
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
		
		$dotTop = new LayerUpDot;
		$dotTopLeft = new LayerUpDot;
		
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
		
		$dotLeft = new LayerUpDot;
		$dotRight = new LayerUpDot;
		$dotTop = new LayerUpDot;
		$dotBottom = new LayerUpDot;
		
		$dotTopLeftTop = new LayerUpDot;
		$dotTopLeftLeft = new LayerUpDot;
		$dotTopRightTop = new LayerUpDot;
		$dotTopRightRight = new LayerUpDot;
		$dotBottomLeftBottom = new LayerUpDot;
		$dotBottomLeftLeft = new LayerUpDot;
		$dotBottomRightBottom = new LayerUpDot;
		$dotBottomRightRight = new LayerUpDot;
		
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
		
		$dotLeft = new LayerUpDot;
		$dotRight = new LayerUpDot;
		$dotTop = new LayerUpDot;
		$dotBottom = new LayerUpDot;
		
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
