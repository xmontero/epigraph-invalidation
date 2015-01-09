<?php
namespace Xmontero\EpigraphInvalidation\Tests;

use Xmontero\EpigraphInvalidation\IconPainter;

class IconPainterTest extends \PHPUnit_Framework_TestCase
{
	private $sut = null;
	private $layerBackgroundPainterMock = null;
	private $layerDownPainterMock = null;
	private $layerUpPainterMock = null;
	private $iconMock = null;
	
	public function setUp()
	{
		$this->layerBackgroundPainterMock = $this->getMock( 'Xmontero\EpigraphInvalidation\Layers\Painters\Background' );
		$this->layerDownPainterMock = $this->getMock( 'Xmontero\EpigraphInvalidation\Layers\Painters\Down' );
		$this->layerUpPainterMock = $this->getMock( 'Xmontero\EpigraphInvalidation\Layers\Painters\Up' );
		$this->iconMock = $this->getMock( 'Xmontero\EpigraphInvalidation\Icon' );
		
		$this->sut = new IconPainter( $this->iconMock, $this->layerBackgroundPainterMock, $this->layerDownPainterMock, $this->layerUpPainterMock );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
		unset( $this->layerBackgroundPainterMock );
		unset( $this->layerDownPainterMock );
		unset( $this->layerUpPainterMock );
		unset( $this->iconMock );
	}
	
	public function testBlankIconPaintsBackground()
	{
		$this->markTestIncomplete();
		
		$this->sut->resample( 260 );
		$image = $this->sut->getResampledImage();
		
		$this->assertColorAtPixel( $image, 2, 2, 255, 255, 255 );
		$this->assertColorAtPixel( $image, 40, 40, 255, 255, 255 );
	}
	
	//---------------------------------------------------------------------//
	// Private.                                                            //
	//---------------------------------------------------------------------//
	
	private function assertColorAtPixel( $image, $x, $y, $expectedR, $expectedG, $expectedB )
	{
		$rgb = imagecolorat( $image, $x, $y );
		$r = ( $rgb >> 16 ) & 0xFF;
		$g = ( $rgb >> 8 ) & 0xFF;
		$b = $rgb & 0xFF;
		
		$this->assertEquals( $expectedR, $r );
		$this->assertEquals( $expectedG, $g );
		$this->assertEquals( $expectedB, $b );
	}
}
