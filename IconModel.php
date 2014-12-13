<?php
namespace Xmontero\EpigraphInvalidation;

class IconModel
{
	public function getSize()
	{
		return 4;
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

class LayerDown
{
	private $dots =
	[
		[ true,  false, true,  true  ],
		[ false, true,  false, false ],
		[ false, false, true,  true  ],
		[ false, true,  true,  true  ]
	];
	
	private $vertices =
	[
		[ false, false, false, true, false ],
		[ false, false, true,  true, false ],
		[ false, false, false, true, false ],
		[ false, false, true,  true, true  ],
		[ false, false, true,  true, false ],
	];
	
	public function getDot( $x, $y )
	{
		return $this->dots[ $y ][ $x ];
	}
	
	public function getVertex( $x, $y )
	{
		return $this->vertices[ $y ][ $x ];
	}
}
