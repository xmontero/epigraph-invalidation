<?php
namespace Xmontero\EpigraphInvalidation;

class LayerDownDot
{
	private $filled;
	
	public function __construct()
	{
		$this->setFilled( true );
	}
	
	public function getFilled()
	{
		return $this->filled;
	}
	
	public function setFilled( $filled )
	{
		$this->filled = $filled;
	}
}
