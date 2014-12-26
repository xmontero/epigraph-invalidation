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
				$topLeft = $dot->getTopLeftVertex();
				$topRight = $dot->getTopRightVertex();
				$bottomLeft = $dot->getBottomLeftVertex();
				$bottomRight = $dot->getBottomRightVertex();
				
				$this->paintLayerDownDot( $dot->isFilled(), $topLeft, $topRight, $bottomLeft, $bottomRight, $this->originalImage, $offsetX, $offsetY );
			}
		}
	}
	
	private function paintIconLayerUpOnImage()
	{
		//imagefilledellipse( $this->originalImage, $this->originalSquareSizeInPixels / 2, $this->originalSquareSizeInPixels / 2, $this->originalSquareSizeInPixels * 2 / 3, $this->originalSquareSizeInPixels * 2 / 3, $this->colorLayerUp );
	}
	
	private function paintLayerDownDot( $dot, $topLeft, $topRight, $bottomLeft, $bottomRight, $image, $offsetX, $offsetY )
	{
		$w = $this->columnWidthInPixels;
		
		$x1 = $offsetX;
		$y1 = $offsetY;
		$x2 = $x1 + $w * 3;
		$y2 = $y1 + $w * 3;
		
		$color = $dot ? $this->colorLayerDown : $this->colorBackground;
		imagefilledrectangle( $this->originalImage, $x1, $y1, $x2, $y2, $color );
		
		$this->paintLayerDownVertex( $topLeft, $dot, $offsetX, $offsetY, true, true, $image );
		$this->paintLayerDownVertex( $topRight, $dot, $offsetX, $offsetY, true, false, $image );
		$this->paintLayerDownVertex( $bottomLeft, $dot, $offsetX, $offsetY, false, true, $image );
		$this->paintLayerDownVertex( $bottomRight, $dot, $offsetX, $offsetY, false, false, $image );
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
