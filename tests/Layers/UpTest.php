<?php
namespace Xmontero\EpigraphInvalidation\Tests\Layers;

use Xmontero\EpigraphInvalidation\Layers\Up;

class UpTest extends \PHPUnit_Framework_TestCase
{
	private $sut = null;
	
	public function setUp()
	{
		$this->sut = new Up( 4, 4 );
	}
	
	public function tearDown()
	{
		unset( $this->sut );
	}
	
	public function testSizes()
	{
		$width = 4;
		$height = 5;
		
		$sut = new Up( $width, $height );
		$this->assertEquals( $width, $sut->getDotWidth() );
		$this->assertEquals( $height, $sut->getDotHeight() );
	}
	
	public function testSetGetFaceOnDot()
	{
		$this->sut->getDot( 2, 2 )->setFilled( true );
		$this->sut->getDot( 3, 0 )->setFilled( false );
		$this->sut->getDot( 0, 2 )->setFilled( true );
		$this->sut->getDot( 1, 3 )->setFilled( false );
		
		$this->assertTrue( $this->sut->getDot( 2, 2 )->isFilled() );
		$this->assertFalse( $this->sut->getDot( 3, 0 )->isFilled() );
		$this->assertNotEquals( $this->sut->getDot( 0, 2 )->isFilled(), $this->sut->getDot( 1, 3 )->isFilled() );
	}
}
