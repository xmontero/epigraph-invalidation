<?php
namespace Xmontero\EpigraphInvalidation;

require_once( "Icon.php" );

class IconGenerator
{
	private $originalSquareSizeInPixels = 1024;
	private $dotCount = 4;
	private $dotWidthInColumns = 3;
	private $marginWidthInColumns = 0.5;
	
	private $seed;
	private $hash;
	private $outputSize;
	
	private $totalColumnsWithoutMargin;
	private $totalColumnsWithMargin;
	private $pixelsPerColumn;
	private $columnWidthInPixels;
	private $marginWidthInPixels;
	private $dotWidthInPixels;
	private $image;
	
	private $colorBackground;
	private $colorLayerDown;
	
	public function __construct( $seed )
	{
		$this->seed = $seed;
		$this->hash = sha1( $seed );
		
		$this->margin = $this->originalSquareSizeInPixels / 10;
		
		$this->totalColumnsWithoutMargin = $this->dotCount * $this->dotWidthInColumns;
		$this->totalColumnsWithMargin = $this->totalColumnsWithoutMargin + 2 * $this->marginWidthInColumns;
		$this->columnWidthInPixels = $this->originalSquareSizeInPixels / $this->totalColumnsWithMargin;
		$this->marginWidthInPixels = $this->marginWidthInColumns * $this->columnWidthInPixels;
		$this->dotWidthInPixels = $this->columnWidthInPixels * $this->dotWidthInColumns;
		
		$this->createIcon();
	}
	
	public function __destruct()
	{
		// Free up memory
		imagedestroy( $this->resampledImage );
	}
	
	// Public.
	
	public function output( $outputSize )
	{
		$resampledImage = imagecreatetruecolor( $outputSize, $outputSize );
		imagecopyresampled( $resampledImage , $this->image , 0, 0, 0, 0, $outputSize, $outputSize, $this->originalSquareSizeInPixels, $this->originalSquareSizeInPixels );
		
		// Output the image
		header( 'Content-type: image/png' );
		imagepng( $resampledImage );
	}
	
	// Private.
	
	private function createIcon()
	{
		$image = imagecreatetruecolor( $this->originalSquareSizeInPixels, $this->originalSquareSizeInPixels );
		$this->colorBackground = imagecolorallocate( $image, 255, 255, 255 );
		imagefill( $image, 0, 0, $this->colorBackground );
		
		$icon = new Icon();
		
		$this->colorLayerDown = imagecolorallocate( $image, 233, 14, 91 );
		//imagefilledellipse( $image, $this->originalSquareSizeInPixels / 2, $this->originalSquareSizeInPixels / 2, $this->originalSquareSizeInPixels * 2 / 3, $this->originalSquareSizeInPixels * 2 / 3, $red );
		
		$this->renderRandom( $icon );
		
		$layerDown = $icon->getLayerDown();
		for( $row = 0; $row < $this->dotCount; $row++ )
		{
			for( $column = 0; $column < $this->dotCount; $column++ )
			{
				$offsetX = $column * $this->dotWidthInPixels + $this->marginWidthInPixels;
				$offsetY = $row * $this->dotWidthInPixels + $this->marginWidthInPixels;
				
				$dot = $layerDown->getDot( $column, $row );
				$topLeft = $dot->getTopLeftVertex();
				$topRight = $dot->getTopRightVertex();
				$bottomLeft = $dot->getBottomLeftVertex();
				$bottomRight = $dot->getBottomRightVertex();
				
				$this->paintLayerDownDot( $dot->isFilled(), $topLeft, $topRight, $bottomLeft, $bottomRight, $image, $offsetX, $offsetY );
			}
		}
		
		$this->image = $image;
	}
	
	private function paintLayerDownDot( $dot, $topLeft, $topRight, $bottomLeft, $bottomRight, $image, $offsetX, $offsetY )
	{
		$w = $this->columnWidthInPixels;
		
		$x1 = $offsetX;
		$y1 = $offsetY;
		$x2 = $x1 + $w * 3;
		$y2 = $y1 + $w * 3;
		
		$color = $dot ? $this->colorLayerDown : $this->colorBackground;
		imagefilledrectangle( $image, $x1, $y1, $x2, $y2, $color );
		
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
		
		imagefilledrectangle( $image, $rectangleX, $rectangleY, $rectangleX + $w, $rectangleY + $w, $vertexColor );
		imagefilledarc( $image, $arcCenterX, $arcCenterY, $w2, $w2, $arcStartAngle, $arcStopAngle, $dotColor, IMG_ARC_PIE );
	}
	
	private function renderSample( LayerDown $layerDown )
	{
		if( ( $layerDown->getDotWidth() != 4 ) || ( $layerDown->getDotHeight() != 4 ) )
		{
			throw new \RuntimeException( 'Can\'t render on a non 4x4 layer.' );
		}
		
		$layerDown->getDot( 0, 0 )->fill();
		$layerDown->getDot( 2, 0 )->fill();
		$layerDown->getDot( 3, 0 )->fill();
		$layerDown->getDot( 1, 1 )->fill();
		$layerDown->getDot( 2, 2 )->fill();
		$layerDown->getDot( 3, 2 )->fill();
		$layerDown->getDot( 1, 3 )->fill();
		$layerDown->getDot( 2, 3 )->fill();
		$layerDown->getDot( 3, 3 )->fill();
		
		$layerDown->setVertex( true, 2, 1 );
	}
	
	private function renderRandom( Icon $icon )
	{
		$this->renderRandomLayerDown( $icon->getLayerDown() );
	}
	
	private function renderRandomLayerDown( LayerDown $layerDown )
	{
		$this->renderRandomLayerDownDots( $layerDown );
		$this->renderRandomLayerDownVertexes( $layerDown );
	}
	
	private function renderRandomLayerDownDots( LayerDown $layerDown )
	{
		$dotWidth = $layerDown->getDotWidth();
		$dotHeight = $layerDown->getDotHeight();
		
		for( $y = 0; $y < $dotHeight; $y++ )
		{
			for( $x = 0; $x < $dotWidth; $x++ )
			{
				$value = $this->getRandomBool( 0.5 );
				$layerDown->getDot( $x, $y )->setFilled( $value );
			}
		}
	}
	
	private function renderRandomLayerDownVertexes( LayerDown $layerDown )
	{
		$vertexWidth = $layerDown->getVertexWidth();
		$vertexHeight = $layerDown->getVertexHeight();
		
		for( $y = 0; $y < $vertexHeight; $y++ )
		{
			for( $x = 0; $x < $vertexWidth; $x++ )
			{
				$value = $this->getRandomBool( 0.5 );
				$layerDown->setVertex( $value, $x, $y );
			}
		}
	}
	
	private function getRandomBool()
	{
		$value = ( mt_rand( 0, 1 ) == 1 );
		return $value;
	}
}
