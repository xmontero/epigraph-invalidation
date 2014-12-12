<?php
namespace Xmontero\EpigraphInvalidation;

class IconModel
{
	public function getSize()
	{
		return 4;
	}
	
	public function getLayerDownDot( $x, $y )
	{
		return new Layer1Dot;
	}
	
	public function getLayerUpDot( $x, $y )
	{
		//
	}
}

class Layer1Dot
{
	public function isCornerFilled( $top, $left )
	{
		return false;
	}
	
	public function isCenterFilled()
	{
		return true;
	}
}
