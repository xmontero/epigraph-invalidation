<?php

use Xmontero\EpigraphInvalidation\Icon;
use Xmontero\EpigraphInvalidation\IconPainter;
use Xmontero\EpigraphInvalidation\LayerBackgroundPainter;
use Xmontero\EpigraphInvalidation\LayerDownPainter;
use Xmontero\EpigraphInvalidation\LayerUpPainter;

class LayerDownPainterTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerDownPainter;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testPaint()
	{
		$this->markTestIncomplete();
		
		$icon = new Icon;
		
		$layerBackgroundPainter = new LayerBackgroundPainter;
		$layerDownPainter = new LayerDownPainter;
		$layerUpPainter = new LayerUpPainter;
		
		$iconPainter = new IconPainter( $icon, $layerBackgroundPainter, $layerDownPainter, $layerUpPainter );
		
		$this->sut->paint( $iconPainter );
	}
}
