<?php
namespace Xmontero\EpigraphInvalidation;

class IconPainter
{
	private $icon;
	
	private $layerBackgroundPainter;
	private $layerDownPainter;
	private $layerUpPainter;
	
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
	
	private $colorTransparent;
	private $colorLayerUp;
	
	public function __construct( Icon $icon, Layers\Painters\Background $layerBackgroundPainter, Layers\Painters\Down $layerDownPainter, Layers\Painters\Up $layerUpPainter )
	{
		$this->icon = $icon;
		
		$this->layerBackgroundPainter = $layerBackgroundPainter;
		$this->layerDownPainter = $layerDownPainter;
		$this->layerUpPainter = $layerUpPainter;
		
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
	
	public function getOriginalImage()
	{
		return $this->originalImage;
	}
	
	public function getIcon()
	{
		return $this->icon;
	}
	
	public function getDotWidthInPixels()
	{
		return $this->dotWidthInPixels;
	}
	
	public function getMarginWidthInPixels()
	{
		return $this->marginWidthInPixels;
	}
	
	public function getColumnWidthInPixels()
	{
		return $this->columnWidthInPixels;
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
		
		$this->colorTransparent = imagecolorallocatealpha( $this->originalImage, 0, 0, 255, 127 );
		$this->colorLayerUp = imagecolorallocate( $this->originalImage, 253, 200, 0 );
		
		$this->layerBackgroundPainter->paint( $this );
		$this->layerDownPainter->paint( $this );
		$this->layerUpPainter->paint( $this );
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
