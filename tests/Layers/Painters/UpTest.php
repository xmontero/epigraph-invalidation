<?php
namespace Xmontero\EpigraphInvalidation\Tests\Layers\Painters;

use Xmontero\EpigraphInvalidation\Layers\Painters\Up;

class LayerUpPainterTest extends \PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Up;
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
