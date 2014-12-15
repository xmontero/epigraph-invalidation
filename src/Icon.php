<?php
namespace Xmontero\EpigraphInvalidation;

class Icon
{
	private $size;
	
	public function __construct( $size = 4 )
	{
		$this->setSize( $size );
	}
	
	public function getSize()
	{
		return $this->size;
	}
	
	public function setSize( $size )
	{
		$this->size = $size;
	}
	
	public function getLayerDown()
	{
		return new LayerDown;
	}
	
	public function getLayerUpDot( $x, $y )
	{
		//
	}
}
