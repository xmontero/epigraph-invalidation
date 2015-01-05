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
	// Straight neighbours.                                                //
	//---------------------------------------------------------------------//
	
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
}
