<?php
namespace Xmontero\EpigraphInvalidation;

abstract class LayerPainter
{
	protected $colorTransparent;
	protected $colorSolid;
	
	abstract public function paint( IconPainter $iconPainter );
}
