<?php
namespace Xmontero\EpigraphInvalidation;

class LayerDownPainter extends LayerPainter
{
	private $iconPainter;
	private $icon;
	
	public function paint( IconPainter $iconPainter )
	{
		$this->iconPainter = $iconPainter;
		$icon = $iconPainter->getIcon();
		
		$this->colorSolid = imagecolorallocate( $this->iconPainter->getOriginalImage(), 233, 14, 91 );
		
		$iconSize = $icon->getSize();
		$layerDown = $icon->getLayerDown();
		
		for( $row = 0; $row < $iconSize; $row++ )
		{
			for( $column = 0; $column < $iconSize; $column++ )
			{
				$offsetX = $column * $this->iconPainter->getDotWidthInPixels() + $this->iconPainter->getMarginWidthInPixels();
				$offsetY = $row * $this->iconPainter->getDotWidthInPixels() + $this->iconPainter->getMarginWidthInPixels();
				
				$dot = $layerDown->getDot( $column, $row );
				$this->paintLayerDownDot( $dot, $iconPainter->getOriginalImage(), $offsetX, $offsetY );
			}
		}
	}
	
	//---------------------------------------------------------------------//
	// Layer down dot.                                                     //
	//---------------------------------------------------------------------//
	
	private function paintLayerDownDot( LayerDownDot $dot, $image, $offsetX, $offsetY )
	{
		$dotIsFilled = $dot->isFilled();
		
		$topLeft = $dot->getTopLeftVertex();
		$topRight = $dot->getTopRightVertex();
		$bottomLeft = $dot->getBottomLeftVertex();
		$bottomRight = $dot->getBottomRightVertex();
		
		$w = $this->iconPainter->getColumnWidthInPixels();
		
		$x1 = $offsetX;
		$y1 = $offsetY;
		$x2 = $x1 + $w * 3;
		$y2 = $y1 + $w * 3;
		
		$color = $dotIsFilled ? $this->colorSolid : $this->colorTransparent;
		imagefilledrectangle( $this->iconPainter->getOriginalImage(), $x1, $y1, $x2, $y2, $color );
		
		$this->paintLayerDownVertex( $topLeft, $dotIsFilled, $offsetX, $offsetY, true, true, $image );
		$this->paintLayerDownVertex( $topRight, $dotIsFilled, $offsetX, $offsetY, true, false, $image );
		$this->paintLayerDownVertex( $bottomLeft, $dotIsFilled, $offsetX, $offsetY, false, true, $image );
		$this->paintLayerDownVertex( $bottomRight, $dotIsFilled, $offsetX, $offsetY, false, false, $image );
	}
	
	private function paintLayerDownVertex( $vertex, $dot, $offsetX, $offsetY, $top, $left, $image )
	{
		$w = $this->iconPainter->getColumnWidthInPixels();
		$w2 = $w * 2;
		
		$rectangleX = $offsetX + ( $left ? 0 : ( $w * 2 ) );
		$rectangleY = $offsetY + ( $top ? 0 : ( $w * 2 ) );
		
		$arcCenterX = $offsetX + $w + ( $left ? 0 : $w );
		$arcCenterY = $offsetY + $w + ( $top ? 0 : $w );
		
		if( $top )
		{
			$arcStartAngle = $left ? 180 : 270;
		}
		else
		{
			$arcStartAngle = $left ? 90 : 0;
		}
		$arcStopAngle = $arcStartAngle + 90;
		
		$bgColor = $this->colorTransparent;
		$fgColor = $this->colorSolid;
		
		$dotColor = $dot ? $fgColor : $bgColor;
		$vertexColor = $vertex ? $fgColor : $bgColor;
		
		$originalImage = $this->iconPainter->getOriginalImage();
		
		imagefilledrectangle( $originalImage, $rectangleX, $rectangleY, $rectangleX + $w, $rectangleY + $w, $vertexColor );
		imagefilledarc( $originalImage, $arcCenterX, $arcCenterY, $w2, $w2, $arcStartAngle, $arcStopAngle, $dotColor, IMG_ARC_PIE );
	}
}
