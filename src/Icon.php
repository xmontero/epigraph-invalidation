<?php
namespace Xmontero\EpigraphInvalidation;

class Icon
{
	private $size;
	private $layerDown;
	private $layerUp;
	
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
		$this->layerDown = new LayerDown( $size, $size );
		$this->layerUp = new LayerUp( $size, $size );
	}
	
	public function getLayerDown()
	{
		return $this->layerDown;
	}
	
	public function getLayerUp()
	{
		return $this->layerUp;
	}
}
