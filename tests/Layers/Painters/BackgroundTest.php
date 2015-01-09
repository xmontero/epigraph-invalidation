<?php
namespace Xmontero\EpigraphInvalidation\Tests\Layers\Painters;

use Xmontero\EpigraphInvalidation\Layers\Painters\Background;

class LayerBackgroundPainterTest extends \PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Background;
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testTrue()
	{
		$this->markTestIncomplete();
		
		$this->assertTrue( false );
	}
}
