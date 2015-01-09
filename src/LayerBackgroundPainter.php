<?php
namespace Xmontero\EpigraphInvalidation;

class LayerBackgroundPainter extends LayerPainter
{
	private $colorBackground;
	
	public function paint( IconPainter $iconPainter )
	{
		$image = $iconPainter->getOriginalImage();
		$this->colorBackground = imagecolorallocate( $image, 255, 255, 255 );
		imagefill( $image, 0, 0, $this->colorBackground );
	}
}
