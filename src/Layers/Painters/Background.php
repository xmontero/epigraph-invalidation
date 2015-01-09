<?php
namespace Xmontero\EpigraphInvalidation\Layers\Painters;

use Xmontero\EpigraphInvalidation\IconPainter;

class Background extends Painter
{
	private $colorBackground;
	
	public function paint( IconPainter $iconPainter )
	{
		$image = $iconPainter->getOriginalImage();
		$this->colorBackground = imagecolorallocate( $image, 255, 255, 255 );
		imagefill( $image, 0, 0, $this->colorBackground );
	}
}
