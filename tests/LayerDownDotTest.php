<?php

use Xmontero\EpigraphInvalidation\LayerDownDot;

class LayerDownDotTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerDownDot;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testFace()
	{
		$this->assertTrue( $this->sut->getFilled() );
		
		$this->sut->setFilled( false );
		$this->assertFalse( $this->sut->getFilled() );
		
		$this->sut->setFilled( true );
		$this->assertTrue( $this->sut->getFilled() );
	}
	
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
		
		$dotLeft = new LayerDownDot;
		$dotRight = new LayerDownDot;
		$dotTop = new LayerDownDot;
		$dotBottom = new LayerDownDot;
		
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
	
	public function testFullNeighboursAddedReturnObjectsOnEdgesAndDiagonals()
	{
		// +---+---+---+
		// |TL | T |TR |
		// +---+---+---+
		// | L |sut| R |
		// +---+---+---+
		// |BL | B |BR |
		// +---+---+---+
		
		$dotLeft = new LayerDownDot;
		$dotRight = new LayerDownDot;
		$dotTop = new LayerDownDot;
		$dotBottom = new LayerDownDot;
		
		$dotTopLeft = new LayerDownDot;
		$dotTopRight = new LayerDownDot;
		$dotBottomLeft = new LayerDownDot;
		$dotBottomRight = new LayerDownDot;
		
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
}
