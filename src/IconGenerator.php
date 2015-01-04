<?php
namespace Xmontero\EpigraphInvalidation;

class IconGenerator
{
	private $seed;
	
	private $icon;
	private $iconPainter;
	
	public function __construct( $seed, $numberOfDotsPerSquareSide )
	{
		$this->seed = $seed;
		//$this->hash = sha1( $seed );
		
		$this->numberOfDotsPerSquareSide = $numberOfDotsPerSquareSide;
		
		$this->icon = new Icon( $this->numberOfDotsPerSquareSide );
		//$this->renderSample();
		$this->renderRandom();
		
		$this->iconPainter = new IconPainter( $this->icon );
	}
	
	//---------------------------------------------------------------------//
	// Public.                                                             //
	//---------------------------------------------------------------------//
	
	public function output( $outputSize )
	{
		$this->iconPainter->resample( $outputSize );
		
		header( 'Content-type: image/png' );
		imagepng( $this->iconPainter->getResampledImage() );
	}
	
	//---------------------------------------------------------------------//
	// Private.                                                            //
	//---------------------------------------------------------------------//
	
	private function renderSample()
	{
		$layerDown = $this->icon->getLayerDown();
		
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
	
	private function renderRandom()
	{
		$this->renderRandomLayerDown();
	}
	
	private function renderRandomLayerDown()
	{
		$layerDown = $this->icon->getLayerDown();
		$this->renderRandomLayerDownDots( $layerDown );
		$this->renderRandomLayerDownVertexes( $layerDown );
		$this->renderRandomLayerUpDots( $layerDown );
	}
	
	private function renderRandomLayerDownDots()
	{
		$layerDown = $this->icon->getLayerDown();
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
	
	private function renderRandomLayerUpDots()
	{
		$layerUp = $this->icon->getLayerUp();
		$dotWidth = $layerUp->getDotWidth();
		$dotHeight = $layerUp->getDotHeight();
		
		for( $y = 0; $y < $dotHeight; $y++ )
		{
			for( $x = 0; $x < $dotWidth; $x++ )
			{
				$value = $this->getRandomBool( 0.5 );
				$layerUp->getDot( $x, $y )->setFilled( $value );
			}
		}
	}
	
	private function getRandomBool()
	{
		$value = ( mt_rand( 0, 1 ) == 1 );
		return $value;
	}
}
