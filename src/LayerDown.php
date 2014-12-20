<?php
namespace Xmontero\EpigraphInvalidation;

class LayerDown
{
	private $width;
	private $height;
	private $dots =
		[
			[ false, false, false, false ],
			[ false, false, false, false ],
			[ false, false, false, false ],
			[ false, false, false, false ]
		];
		
	private $vertices;
	
	public function __construct( $width = 4, $height = 4, $renderSample = true )
	{
		$this->width = $width;
		$this->height = $height;
		
		if( $renderSample )
		{
			$this->renderSample();
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
		
		/*$this->dots =
		[
			[ true,  false, true,  true  ],
			[ false, true,  false, false ],
			[ false, false, true,  true  ],
			[ false, true,  true,  true  ]
		];*/
		
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
		if( ! is_object( $this->dots[ $y ][ $x ] ) )
		{
			$this->dots[ $y ][ $x ] = new LayerDownDot;
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
	
	public function getTopLeftVertexForDot( $x, $y )
	{
		return $this->getVertex( $x, $y );
	}
	
	public function getTopRightVertexForDot( $x, $y )
	{
		return $this->getVertex( $x + 1, $y );
	}
	
	public function getBottomLeftVertexForDot( $x, $y )
	{
		return $this->getVertex( $x, $y + 1 );
	}
	
	public function getBottomRightVertexForDot( $x, $y )
	{
		return $this->getVertex( $x + 1, $y + 1 );
	}
}
