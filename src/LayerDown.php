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
				$this->dots[ $y ][ $x ] = new LayerDownDot;
			}
		}
	}
	
	public function renderSample()
	{
		if( ( $this->getDotWidth() != 4 ) || ( $this->getDotHeight() != 4 ) )
		{
			throw new \RuntimeException( 'Can\'t render on a non 4x4 layer.' );
		}
		
		$this->getDot( 0, 0 )->fill();
		$this->getDot( 2, 0 )->fill();
		$this->getDot( 3, 0 )->fill();
		$this->getDot( 1, 1 )->fill();
		$this->getDot( 2, 2 )->fill();
		$this->getDot( 3, 2 )->fill();
		$this->getDot( 1, 3 )->fill();
		$this->getDot( 2, 3 )->fill();
		$this->getDot( 3, 3 )->fill();
		
		$this->vertices =
		[
			[ false, false, false, true, false ],
			[ false, false, true,  true, false ],
			[ false, false, false, true, false ],
			[ false, false, true,  true, true  ],
			[ false, false, true,  true, false ],
		];
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
		$this->vertices[ $y ][ $x ] = $state;
	}
	
	public function getVertex( $x, $y )
	{
		return $this->vertices[ $y ][ $x ];
	}
}
