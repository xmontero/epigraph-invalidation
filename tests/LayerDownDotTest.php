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
	
	//---------------------------------------------------------------------//
	// Vertexes.                                                           //
	//---------------------------------------------------------------------//
	
	//---------------------------------------------------------------------//
	// A dot with no neighbours has clear vertexes both when the face is   //
	// filled or is clear.                                                 //
	//                                                                     //
	//      |       |       |       |                                      //
	//                                                                     //
	// ---             ---    +---+    ---                                 //
	//                      +       +                                      //
	//         sut          |  sut  |                                      //
	//                      +       +                                      //
	// ---             ---    +---+    ---                                 //
	//                                                                     //
	//      |       |       |       |                                      //
	//---------------------------------------------------------------------//
	
	public function testIsolatedDotHasClearVertexes()
	{
		$this->sut->clear();
		$this->assertFalse( $this->sut->getTopLeftVertex() );
		$this->assertFalse( $this->sut->getTopRightVertex() );
		$this->assertFalse( $this->sut->getBottomLeftVertex() );
		$this->assertFalse( $this->sut->getBottomRightVertex() );
		
		$this->sut->fill();
		$this->assertFalse( $this->sut->getTopLeftVertex() );
		$this->assertFalse( $this->sut->getTopRightVertex() );
		$this->assertFalse( $this->sut->getBottomLeftVertex() );
		$this->assertFalse( $this->sut->getBottomRightVertex() );
	}
	
	//---------------------------------------------------------------------//
	// A dot with a single neighbour in the tested vertex, but no neigbour //
	// in the other direction (and therefore no neigbour in diagonal)      //
	// has the vertex filled if and only if both faces are filled.         //
	//                                                                     //
	//      |       |       |       |       |       |                      //
	//                                                                     //
	// ---                     ---            +---+    ---                 //
	//                                      +       +                      //
	//         sut1    sut2            sut1 |  sut2 |                      //
	//                                      +       +                      //
	// ---                     ---            +---+    ---                 //
	//                                                                     //
	//      |       |       |       |       |       |                      //
	//                                                                     //
	// ---    +---+            ---    +-----+-----+    ---                 //
	//      +       +               +       |       +                      //
	//      |  sut1 |  sut2         |  sut1 |  sut2 |                      //
	//      +       +               +       |       +                      //
	// ---    +---+            ---    +-----+-----+    ---                 //
	//                                                                     //
	//      |       |       |       |       |       |                      //
	//---------------------------------------------------------------------//
	// The diagrams represent 2 dots in horizontal. The same applies in    //
	// vertical.                                                           //
	//---------------------------------------------------------------------//
	
	/**
	 * @dataProvider providerTwoDots
	 */
	public function testTwoDots
	(
		$orientation,
		$sut1Fill,
		$sut2Fill,
		$expectedSut1TopLeftVertex,
		$expectedSut1TopRightVertex,
		$expectedSut1BottomLeftVertex,
		$expectedSut1BottomRightVertex,
		$expectedSut2TopLeftVertex,
		$expectedSut2TopRightVertex,
		$expectedSut2BottomLeftVertex,
		$expectedSut2BottomRightVertex
	)
	{
		$sut1 = new LayerDownDot;
		$sut2 = new LayerDownDot;
		
		if( $orientation == 'horizontal' )
		{
			$sut1->setRightNeighbour( $sut2 );
		}
		else
		{
			$sut1->setBottomNeighbour( $sut2 );
		}
		
		$sut1->setFilled( $sut1Fill );
		$sut2->setFilled( $sut2Fill );
		
		$this->assertEquals( $expectedSut1TopLeftVertex, $sut1->getTopLeftVertex() );
		$this->assertEquals( $expectedSut1TopRightVertex, $sut1->getTopRightVertex() );
		$this->assertEquals( $expectedSut1BottomLeftVertex, $sut1->getBottomLeftVertex() );
		$this->assertEquals( $expectedSut1BottomRightVertex, $sut1->getBottomRightVertex() );
		$this->assertEquals( $expectedSut2TopLeftVertex, $sut2->getTopLeftVertex() );
		$this->assertEquals( $expectedSut2TopRightVertex, $sut2->getTopRightVertex() );
		$this->assertEquals( $expectedSut2BottomLeftVertex, $sut2->getBottomLeftVertex() );
		$this->assertEquals( $expectedSut2BottomRightVertex, $sut2->getBottomRightVertex() );
	}
	
	public function providerTwoDots()
	{
		$data = array
		(
			array( 'horizontal', false, false, false, false, false, false, false, false, false, false ),
			array( 'horizontal', false,  true, false, false, false, false, false, false, false, false ),
			array( 'horizontal',  true, false, false, false, false, false, false, false, false, false ),
			array( 'horizontal',  true,  true, false,  true, false,  true,  true, false,  true, false ),
			array( 'vertical',   false, false, false, false, false, false, false, false, false, false ),
			array( 'vertical',   false,  true, false, false, false, false, false, false, false, false ),
			array( 'vertical',    true, false, false, false, false, false, false, false, false, false ),
			array( 'vertical',    true,  true, false, false,  true,  true,  true,  true, false, false ),
		);
		
		return $data;
	}
	
	//---------------------------------------------------------------------//
	// A dot with both straight neighbours in the tested vertex has        //
	// combinations in function of the surrounding dots. There are a total //
	// of 16 combinations, assuming the diagonal is not null. If diagonal  //
	// is null will be treated as cleared.                                 //
	//---------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                     //
	//      |       |       |       |       |       |       |       |       |       |       |       |                      //
	//                                                                                                                     //
	// ---                     ---            +---+    ---    +---+            ---    +-----+-----+    ---                 //
	//                                      +       +       +       +               +       |       +                      //
	//         sut1    sut2            sut1 |  sut2 |       |  sut1 |  sut2         |  sut1 |  sut2 +                      //
	//         off     off             off  +  off  +       +  off  +  off          +  on   |  on   +                      //
	// ---                     ---            +---+    ---    +---+                   +-----+-----+                        //
	//                                                                                                                     //
	//         sut3    sut4            sut3    sut4            sut3    sut4            sut3    sut4                        //
	//         off     off             off     off             off     off             off     off                         //
	// ---                     ---                     ---                     ---                     ---                 //
	//                                                                                                                     //
	//      |       |       |       |       |       |       |       |       |       |       |       |                      //
	//                                                                                                                     //
	// ---                     ---            +---+    ---    +---+            ---    +-----+-----+    ---                 //
	//                                      +       +       +       +               +       |       +                      //
	//         sut1    sut2            sut1 |  sut2 |       |  sut1 |  sut2         |  sut1 |  sut2 |                      //
	//         off     off             off  |  on   |       +  ???  +  ???          +  on   |  on   |                      //
	// ---            +---+    ---          +-------+  ---    +---+ ? +---+           +---+-+-------+                      //
	//              +       +               |       |               +       +               +       |                      //
	//         sut3 |  sut4 |          sut3 |  sut4 |          sut3 |  sut4 |          sut3 |  sut4 |                      //
	//         off  +  off  +          off  +  on   +          ???  +  ???  +          on   +  on   +                      //
	// ---            +---+    ---            +---+    ---            +---+    ---            +---+    ---                 //
	//                                                                                                                     //
	//      |       |       |       |       |       |       |       |       |       |       |       |                      //
	//                                                                                                                     //
	// ---                     ---            +---+    ---    +---+            ---    +-----+-----+    ---                 //
	//                                      +       +       +       +               +       |       +                      //
	//         sut1    sut2            sut1 |  sut2 |       |  sut1 |  sut2         |  sut1 |  sut2 |                      //
	//         off     off             ???  +  ???  +       |  on   |  off          |  on   |  on   +                      //
	// ---    +---+            ---    +---+ ? +---+    ---  +-------+               +-------+-+---+                        //
	//      +       +               +       +               |       |               |       +                              //
	//      |  sut3 |  sut4         |  sut3 |  sut4         |  sut3 |  sut4         |  sut3 |  sut4                        //
	//      +  off  +  off          +  ???  +  ???          +  on   +  off          +  on   +  on                          //
	// ---    +---+            ---    +---+            ---    +---+            ---    +---+            ---                 //
	//                                                                                                                     //
	//      |       |       |       |       |       |       |       |       |       |       |       |                      //
	//                                                                                                                     //
	// ---                     ---            +---+    ---    +---+            ---    +-----+-----+    ---                 //
	//                                      +       +       +       +               +       |       +                      //
	//         sut1    sut2            sut1 |  sut2 |       |  sut1 |  sut2         |  sut1 |  sut2 |                      //
	//         off     off             on   +  on   |       |  on   +  on           |  on   |  on   |                      //
	// ---    +-----+-----+    ---    +---+-+-------+  ---  +-------+-+---+         +-------+-------+                      //
	//      +       |       +       +       |       |       |       |       +       |       |       |                      //
	//      |  sut3 |  sut4 |       |  sut3 |  sut4 |       |  sut3 |  sut4 |       |  sut3 |  sut4 |                      //
	//      +  on   |  on   +       +  on   |  on   +       +  on   |  on   +       +  on   |  on   +                      //
	// ---    +-----+-----+    ---    +-----+-----+    ---    +-----+-----+    ---    +-----+-----+    ---                 //
	//                                                                                                                     //
	//      |       |       |       |       |       |       |       |       |       |       |       |                      //
	//---------------------------------------------------------------------------------------------------------------------//
	// For this example, the tested vertex is the central one where all of //
	// the 4 SUTs confluence.                                              //
	//---------------------------------------------------------------------//
	
	/**
	 * @dataProvider providerCentralVertexInQuadrupleConfiguration
	 */
	public function testCentralVertexInQuadrupleConfiguration( $sut1Filled, $sut2Filled, $sut3Filled, $sut4Filled, $commonVertex, $sut1Expected, $sut2Expected, $sut3Expected, $sut4Expected )
	{
		$sut1 = new LayerDownDot;
		$sut2 = new LayerDownDot;
		$sut3 = new LayerDownDot;
		$sut4 = new LayerDownDot;
		
		$sut1->setRightNeighbour( $sut2 );
		$sut1->setBottomNeighbour( $sut3 );
		$sut2->setBottomNeighbour( $sut4 );
		$sut3->setRightNeighbour( $sut4 );		// Will not be necessary to be added when the fact of adding diagonals provokes symmetricals to be added too.
		
		if( ! is_null( $commonVertex ) )
		{
			$sut1->setBottomRightUnderlyingVertex( $commonVertex );
			$sut2->setBottomLeftUnderlyingVertex( $commonVertex );
			$sut3->setTopRightUnderlyingVertex( $commonVertex );
			$sut4->setTopLeftUnderlyingVertex( $commonVertex );
		}
		
		$sut1->setFilled( $sut1Filled );
		$sut2->setFilled( $sut2Filled );
		$sut3->setFilled( $sut3Filled );
		$sut4->setFilled( $sut4Filled );
		
		$this->assertEquals( $sut1Expected, $sut1->getBottomRightVertex() );
		$this->assertEquals( $sut2Expected, $sut2->getBottomLeftVertex() );
		$this->assertEquals( $sut3Expected, $sut3->getTopRightVertex() );
		$this->assertEquals( $sut4Expected, $sut4->getTopLeftVertex() );
	}
	
	public function providerCentralVertexInQuadrupleConfiguration()
	{
		$data = array
		(
			array( false, false, false, false,  null, false, false, false, false ), /* ok, minority, all result in false. */
			array( false, false, false,  true,  null, false, false, false, false ), /* ok, minority, all result in false. */
			array( false, false,  true, false,  null, false, false, false, false ), /* ok, minority, all result in false. */
			array( false, false,  true,  true,  null, false, false,  true,  true ), /* ok, two of each, inline. */
			
			array( false,  true, false, false,  null, false, false, false, false ), /* ok, minority, all result in false. */
			array( false,  true, false,  true,  null, false,  true, false,  true ), /* ok, two of each, inline. */
			array( false,  true,  true, false, false, false, false, false, false ), /* ok, two of each, alternated. */
			array( false,  true,  true, false,  true,  true,  true,  true,  true ), /* ok, two of each, alternated. */
			array( false,  true,  true,  true,  null,  true,  true,  true,  true ), /* ok, majority, all result in true. */
			
			array(  true, false, false, false,  null, false, false, false, false ), /* ok, minority, all result in false. */
			array(  true, false, false,  true, false, false, false, false, false ), /* ok, two of each, alternated. */
			array(  true, false, false,  true,  true,  true,  true,  true,  true ), /* ok, two of each, alternated. */
			array(  true, false,  true, false,  null,  true, false,  true, false ), /* ok, two of each, inline. */
			array(  true, false,  true,  true,  null,  true,  true,  true,  true ), /* ok, majority, all result in true. */
			
			array(  true,  true, false, false,  null,  true,  true, false, false ), /* ok, two of each, inline. */
			array(  true,  true, false,  true,  null,  true,  true,  true,  true ), /* ok, majority, all result in true. */
			array(  true,  true,  true, false,  null,  true,  true,  true,  true ), /* ok, majority, all result in true. */
			array(  true,  true,  true,  true,  null,  true,  true,  true,  true ), /* ok, majority, all result in true. */
		);
		
		return $data;
	}
}
