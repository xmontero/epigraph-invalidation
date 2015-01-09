<?php
namespace Xmontero\EpigraphInvalidation\Layers\Painters;

use Xmontero\EpigraphInvalidation\IconPainter;

abstract class Painter
{
	protected $colorTransparent;
	protected $colorSolid;
	
	abstract public function paint( IconPainter $iconPainter );
}
