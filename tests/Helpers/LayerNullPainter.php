<?php
namespace Xmontero\EpigraphInvalidation\Tests\Helpers;

use Xmontero\EpigraphInvalidation\IconPainter;
use Xmontero\EpigraphInvalidation\LayerPainter;

class LayerNullPainter extends LayerPainter
{
	public function paint( IconPainter $iconPainter )
	{
		//$icon = $iconPainter->getIcon();
		//$image = $iconPainter->getOriginalImage();
		//$image->setIcon( $icon );
	}
}
