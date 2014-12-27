<?php
namespace Xmontero\EpigraphInvalidation;

class LayerUp
{
	private $width;
	private $height;
	private $dots;
	
	private $vertices;
	
	public function __construct( $width = 4, $height = 4 )
	{
		$this->setSize( $width, $height );
	}
	
	public function setSize( $width, $height )
	{
		$this->width = $width;
		$this->height = $height;
		
		$this->dots = array();
		for( $y = 0; $y < $height; $y++ )
		{
			$this->dots[ $y ] = array();
			for( $x = 0; $x < $width; $x++ )
			{
				$currentDot = new LayerDownDot;
				$this->dots[ $y ][ $x ] = $currentDot;
				
				if( $x >= 1 )
				{
					$left = $this->dots[ $y ][ $x - 1 ];
					$currentDot->setLeftNeighbour( $left );
				}
				
				if( $y >= 1 )
				{
					$top = $this->dots[ $y - 1 ][ $x ];
					$currentDot->setTopNeighbour( $top );
				}
			}
		}
	}
	
	public function getDotWidth()
	{
		return $this->width;
	}
	
	public function getDotHeight()
	{
		return $this->height;
	}
	
	public function getDot( $x, $y )
	{
		if( ( $x < 0 ) || ( $x >= $this->getDotWidth() ) || ( $y < 0 ) || ( $y >= $this->getDotHeight() ) )
		{
			throw new \OutOfBoundsException( 'x: ' . $x . ', y: ' . $y );
		}
		
		$result = $this->dots[ $y ][ $x ];
		return $result;
	}
}
