<?php
namespace Xmontero\EpigraphInvalidation\Tests\Layers\Painters;

use Xmontero\EpigraphInvalidation\Icon;
use Xmontero\EpigraphInvalidation\IconPainter;
use Xmontero\EpigraphInvalidation\Layers\Painters\BackgroundPainter;
use Xmontero\EpigraphInvalidation\Layers\Painters\Down;
use Xmontero\EpigraphInvalidation\Layers\Painters\Up;

class LayerDownPainterTest extends \PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Down;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testPaint()
	{
		$this->markTestIncomplete();
		
		$icon = new Icon;
		
		$layerBackgroundPainter = new Background;
		$layerDownPainter = new Down;
		$layerUpPainter = new Up;
		
		$iconPainter = new IconPainter( $icon, $layerBackgroundPainter, $layerDownPainter, $layerUpPainter );
		
		$this->sut->paint( $iconPainter );
	}
}
