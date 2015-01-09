<?php
namespace Xmontero\EpigraphInvalidation\Tests\Layers\Painters\Helpers;

use Xmontero\EpigraphInvalidation\IconPainter;
use Xmontero\EpigraphInvalidation\Layers\Painters\Painter;

class Null extends Painter
{
	public function paint( IconPainter $iconPainter )
	{
		//$icon = $iconPainter->getIcon();
		//$image = $iconPainter->getOriginalImage();
		//$image->setIcon( $icon );
	}
}
