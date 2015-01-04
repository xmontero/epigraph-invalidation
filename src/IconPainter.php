<?php
namespace Xmontero\EpigraphInvalidation;

class IconPainter
{
	private $icon;
	
	private $originalSquareSizeInPixels = 1024;
	private $dotWidthInColumns = 3;
	private $marginWidthInColumns = 0.5;
	
	private $totalColumnsWithoutMargin;
	private $totalColumnsWithMargin;
	private $columnWidthInPixels;
	private $marginWidthInPixels;
	private $dotWidthInPixels;
	
	private $resampledImage;
	private $originalImage;
	
	private $colorBackground;
	private $colorTransparent;
	private $colorLayerDown;
	private $colorLayerUp;
	
	public function __construct( Icon $icon )
	{
		$this->icon = $icon;
		
		$this->margin = $this->originalSquareSizeInPixels / 10;
		
		$this->totalColumnsWithoutMargin = $icon->getSize() * $this->dotWidthInColumns;
		$this->totalColumnsWithMargin = $this->totalColumnsWithoutMargin + 2 * $this->marginWidthInColumns;
		$this->columnWidthInPixels = $this->originalSquareSizeInPixels / $this->totalColumnsWithMargin;
		$this->marginWidthInPixels = $this->marginWidthInColumns * $this->columnWidthInPixels;
		$this->dotWidthInPixels = $this->columnWidthInPixels * $this->dotWidthInColumns;
		
		$this->paintIconOnImage();
	}
	
	public function __destruct()
	{
		$this->destroyResampledImageIfNeeded();
		$this->destroyOriginalImageIfNeeded();
	}
	
	//---------------------------------------------------------------------//
	// Public.                                                             //
	//---------------------------------------------------------------------//
	
	public function resample( $outputSize )
	{
		$this->destroyResampledImageIfNeeded();
		
		$this->resampledImage = imagecreatetruecolor( $outputSize, $outputSize );
		imagecopyresampled( $this->resampledImage , $this->originalImage , 0, 0, 0, 0, $outputSize, $outputSize, $this->originalSquareSizeInPixels, $this->originalSquareSizeInPixels );
	}
	
	public function getResampledImage()
	{
		return $this->resampledImage;
	}
	
	//---------------------------------------------------------------------//
	// Private.                                                            //
	//---------------------------------------------------------------------//
	
	//---------------------------------------------------------------------//
	// Image painters.                                                     //
	//---------------------------------------------------------------------//
	
	private function paintIconOnImage()
	{
		$this->originalImage = imagecreatetruecolor( $this->originalSquareSizeInPixels, $this->originalSquareSizeInPixels );
		
		$this->colorBackground = imagecolorallocate( $this->originalImage, 255, 255, 255 );
		$this->colorTransparent = imagecolorallocatealpha( $this->originalImage, 0, 0, 255, 127 );
		$this->colorLayerDown = imagecolorallocate( $this->originalImage, 233, 14, 91 );
		$this->colorLayerUp = imagecolorallocate( $this->originalImage, 253, 200, 0 );
		
		$this->paintIconBackgroundOnImage();
		$this->paintIconLayerDownOnImage();
		$this->paintIconLayerUpOnImage();
	}
	
	private function paintIconBackgroundOnImage()
	{
		imagefill( $this->originalImage, 0, 0, $this->colorBackground );
	}
	
	private function paintIconLayerDownOnImage()
	{
		$iconSize = $this->icon->getSize();
		$layerDown = $this->icon->getLayerDown();
		
		for( $row = 0; $row < $iconSize; $row++ )
		{
			for( $column = 0; $column < $iconSize; $column++ )
			{
				$offsetX = $column * $this->dotWidthInPixels + $this->marginWidthInPixels;
				$offsetY = $row * $this->dotWidthInPixels + $this->marginWidthInPixels;
				
				$dot = $layerDown->getDot( $column, $row );
				$this->paintLayerDownDot( $dot, $this->originalImage, $offsetX, $offsetY );
			}
		}
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
	// Layer down dot.                                                     //
	//---------------------------------------------------------------------//
	
	private function paintLayerDownDot( LayerDownDot $dot, $image, $offsetX, $offsetY )
	{
		$dotIsFilled = $dot->isFilled();
		
		$topLeft = $dot->getTopLeftVertex();
		$topRight = $dot->getTopRightVertex();
		$bottomLeft = $dot->getBottomLeftVertex();
		$bottomRight = $dot->getBottomRightVertex();
		
		$w = $this->columnWidthInPixels;
		
		$x1 = $offsetX;
		$y1 = $offsetY;
		$x2 = $x1 + $w * 3;
		$y2 = $y1 + $w * 3;
		
		$color = $dotIsFilled ? $this->colorLayerDown : $this->colorBackground;
		imagefilledrectangle( $this->originalImage, $x1, $y1, $x2, $y2, $color );
		
		$this->paintLayerDownVertex( $topLeft, $dotIsFilled, $offsetX, $offsetY, true, true, $image );
		$this->paintLayerDownVertex( $topRight, $dotIsFilled, $offsetX, $offsetY, true, false, $image );
		$this->paintLayerDownVertex( $bottomLeft, $dotIsFilled, $offsetX, $offsetY, false, true, $image );
		$this->paintLayerDownVertex( $bottomRight, $dotIsFilled, $offsetX, $offsetY, false, false, $image );
	}
	
	private function paintLayerDownVertex( $vertex, $dot, $offsetX, $offsetY, $top, $left, $image )
	{
		$w = $this->columnWidthInPixels;
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
		
		$bgColor = $this->colorBackground;
		$fgColor = $this->colorLayerDown;
		
		$dotColor = $dot ? $fgColor : $bgColor;
		$vertexColor = $vertex ? $fgColor : $bgColor;
		
		imagefilledrectangle( $this->originalImage, $rectangleX, $rectangleY, $rectangleX + $w, $rectangleY + $w, $vertexColor );
		imagefilledarc( $this->originalImage, $arcCenterX, $arcCenterY, $w2, $w2, $arcStartAngle, $arcStopAngle, $dotColor, IMG_ARC_PIE );
	}
	
	//---------------------------------------------------------------------//
	// Layer up dot.                                                       //
	//---------------------------------------------------------------------//
	
	private function paintLayerUpDot( LayerUpDot $dot, $image, $offsetX, $offsetY )
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
	
	//---------------------------------------------------------------------//
	// Image destroying.                                                   //
	//---------------------------------------------------------------------//
	
	private function destroyResampledImageIfNeeded()
	{
		is_null( $this->resampledImage ) ?: imagedestroy( $this->resampledImage );
	}
	
	private function destroyOriginalImageIfNeeded()
	{
		is_null( $this->originalImage ) ?: imagedestroy( $this->originalImage );
	}
}
