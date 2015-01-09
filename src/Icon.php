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
		$this->layerDown = new Layers\Down( $size, $size );
		$this->layerUp = new Layers\Up( $size, $size );
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
