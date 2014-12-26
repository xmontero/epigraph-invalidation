<?php
namespace Xmontero\EpigraphInvalidation;

class LayerDown
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
	
	public function getVertexWidth()
	{
		return $this->width + 1;
	}
	
	public function getVertexHeight()
	{
		return $this->height + 1;
	}
	
	public function getDot( $x, $y )
	{
		if( $x < 0 || $x >= $this->getDotWidth() || $y < 0 || $y >= $this->getDotHeight() )
		{
			throw new \OutOfBoundsException;
		}
		
		$result = $this->dots[ $y ][ $x ];
		return $result;
	}
	
	public function setVertex( $state, $x, $y )
	{
		$topLeft     = $this->getDotOrNull( $x - 1, $y - 1 );
		$topRight    = $this->getDotOrNull( $x,     $y - 1 );
		$bottomLeft  = $this->getDotOrNull( $x - 1, $y     );
		$bottomRight = $this->getDotOrNull( $x,     $y     );
		
		is_null( $topLeft )     ? null : $topLeft->setBottomRightUnderlyingVertex( $state );
		is_null( $topRight )    ? null : $topRight->setBottomLeftUnderlyingVertex( $state );
		is_null( $bottomLeft )  ? null : $bottomLeft->setTopRightUnderlyingVertex( $state );
		is_null( $bottomRight ) ? null : $bottomRight->setTopLeftUnderlyingVertex( $state );
	}
	
	public function getVertex( $x, $y )
	{
		return $this->vertices[ $y ][ $x ];
	}
	
	//---------------------------------------------------------------------//
	// Private.                                                            //
	//---------------------------------------------------------------------//
	
	public function getDotOrNull( $x, $y )
	{
		try
		{
			$result = $this->getDot( $x, $y );
		}
		catch( \OutOfBoundsException $e )
		{
			$result = null;
		}
		
		return $result;
	}
	
}
