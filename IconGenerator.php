<?php
namespace Xmontero\EpigraphInvalidation;

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
		$white = imagecolorallocate( $image, 255, 255, 255 );
		imagefill( $image, 0, 0, $white );
		
		$color = imagecolorallocate( $image, 233, 14, 91 );
		//imagefilledellipse( $image, $this->originalSquareSizeInPixels / 2, $this->originalSquareSizeInPixels / 2, $this->originalSquareSizeInPixels * 2 / 3, $this->originalSquareSizeInPixels * 2 / 3, $red );
		
		for( $row = 0; $row < $this->dotCount; $row++ )
		{
			for( $column = 0; $column < $this->dotCount; $column++ )
			{
				$x1 = $column * $this->dotWidthInPixels + $this->marginWidthInPixels;
				$y1 = $row * $this->dotWidthInPixels + $this->marginWidthInPixels;
				$x2 = $x1 + $this->dotWidthInPixels;
				$y2 = $y1 + $this->dotWidthInPixels;
				imagefilledrectangle( $image, $x1, $y1, $x2, $y2, $color );
			}
		}
		
		$this->image = $image;
	}
}

// jolín, están diciendo en la tele, que google va a cerrar el servicio "google news"