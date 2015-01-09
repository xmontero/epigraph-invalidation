<?php

$loader = require 'vendor/autoload.php';
$loader->addPsr4( 'Xmontero\\EpigraphInvalidation\\Tests\\', __DIR__ );

use Xmontero\EpigraphInvalidation\Icon;
use Xmontero\EpigraphInvalidation\IconPainter;
use Xmontero\EpigraphInvalidation\LayerBackgroundPainter;
use Xmontero\EpigraphInvalidation\LayerDownPainter;
use Xmontero\EpigraphInvalidation\LayerUpPainter;

use Xmontero\EpigraphInvalidation\Tests\Helpers\LayerNullPainter;

class LayerPainterTest extends PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new LayerNullPainter;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testPaint()
	{
		$icon = new Icon;
		
		$layerBackgroundPainter = new LayerBackgroundPainter;
		$layerDownPainter = new LayerDownPainter;
		$layerUpPainter = new LayerUpPainter;
		
		$iconPainter = new IconPainter( $icon, $layerBackgroundPainter, $layerDownPainter, $layerUpPainter );
		
		$this->sut->paint( $iconPainter );
	}
}
