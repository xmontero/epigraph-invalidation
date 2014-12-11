<?php
namespace Xmontero\EpigraphInvalidation;

class IconGenerator
{
	private $originalSize = 1024;
	private $dotCount = 4;
	
	private $hash;
	private $outputSize;
	
	private $margin;
	private $resampledImage;
	
	public function __construct( $hash, $outputSize )
	{
		$this->hash = $hash;
		$this->outputSize = $outputSize;
		
		//$this->margin = $this->originalSize / 10;
		
		$this->createIcon();
	}
	
	private function createIcon()
	{
		
		$image = imagecreatetruecolor( $this->originalSize, $this->originalSize );
		$white = imagecolorallocate( $image, 255, 255, 255 );
		imagefill( $image, 100, 50, $white );
		
		$red = imagecolorallocate( $image, 233, 14, 91 );
		imagefilledellipse( $image, $this->originalSize / 2, $this->originalSize / 2, $this->originalSize * 2 / 3, $this->originalSize * 2 / 3, $red );
		
		$this->resampleAndStore( $image );
	}
	
	private function resampleAndStore( $image )
	{
		$this->resampledImage = imagecreatetruecolor( $this->outputSize, $this->outputSize );
		imagecopyresampled( $this->resampledImage , $image , 0, 0, 0, 0, $this->outputSize, $this->outputSize, $this->originalSize, $this->originalSize );
	}
	
	public function __destruct()
	{
		// Free up memory
		imagedestroy( $this->resampledImage );
	}
	
	public function output()
	{
		// Output the image
		header( 'Content-type: image/png' );
		imagepng( $this->resampledImage );
	}
}
