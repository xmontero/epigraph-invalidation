<?php
namespace Xmontero\EpigraphInvalidation\Tests\Layers\Painters;

$loader = require 'vendor/autoload.php';
$loader->addPsr4( 'Xmontero\\EpigraphInvalidation\\Tests\\Layers\\Painters\\', __DIR__ );

use Xmontero\EpigraphInvalidation\Icon;
use Xmontero\EpigraphInvalidation\IconPainter;
use Xmontero\EpigraphInvalidation\Layers\Painters\Background;
use Xmontero\EpigraphInvalidation\Layers\Painters\Down;
use Xmontero\EpigraphInvalidation\Layers\Painters\Up;

use Xmontero\EpigraphInvalidation\Tests\Layers\Painters\Helpers\Null;

class PainterTest extends \PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Null;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testPaint()
	{
		$icon = new Icon;
		
		$layerBackgroundPainter = new Background;
		$layerDownPainter = new Down;
		$layerUpPainter = new Up;
		
		$iconPainter = new IconPainter( $icon, $layerBackgroundPainter, $layerDownPainter, $layerUpPainter );
		
		$this->sut->paint( $iconPainter );
	}
}
