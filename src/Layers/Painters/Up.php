<?php
namespace Xmontero\EpigraphInvalidation\Layers\Painters;

use Xmontero\EpigraphInvalidation\IconPainter;

class Up extends Painter
{
	public function paint( IconPainter $iconPainter )
	{
		
	}
	
	private function paintIconLayerUpOnImage()
	{
		$iconSize = $this->icon->getSize();
		$layerUp = $this->icon->getLayerUp();
		
		for( $row = 0; $row < $iconSize; $row++ )
		{
			for( $column = 0; $column < $iconSize; $column++ )
			{
				$offsetX = $column * $this->dotWidthInPixels + $this->marginWidthInPixels;
				$offsetY = $row * $this->dotWidthInPixels + $this->marginWidthInPixels;
				
				$dot = $layerUp->getDot( $column, $row );
				$this->paintLayerUpDot( $dot, $this->originalImage, $offsetX, $offsetY );
			}
		}
	}
	
	//---------------------------------------------------------------------//
	// Layer up dot.                                                       //
	//---------------------------------------------------------------------//
	
	private function paintLayerUpDot( \Xmontero\EpigraphInvalidation\Layers\Dots\Up $dot, $image, $offsetX, $offsetY )
	{
		$dotIsFilled = $dot->isFilled();
		
		$w = $this->columnWidthInPixels;
		$internalDotOffset = $w * 3 / 2;
		
		$centerX = $offsetX + $internalDotOffset;
		$centerY = $offsetY + $internalDotOffset;
		$radius = $w * 2;
		
		$color = $dotIsFilled ? $this->colorLayerUp : $this->colorTransparent;
		imagefilledellipse( $this->originalImage, $centerX, $centerY, $radius, $radius, $color );
	}
}
