<?php
namespace Xmontero\EpigraphInvalidation;

class LayerUpDot extends LayerDot
{
	private $leftBridge = false;
	private $rightBridge = false;
	private $topBridge = false;
	private $bottomBridge = false;
	
	//---------------------------------------------------------------------//
	// Direct neighbours.                                                  //
	//---------------------------------------------------------------------//
	
	public function setLeftNeighbour( LayerDot $leftNeighbour )
	{
		$leftNeighbour->setRightBridge( $this->getLeftBridge() );
		parent::setLeftNeighbour( $leftNeighbour );
	}
	
	public function setRightNeighbour( LayerDot $rightNeighbour )
	{
		$rightNeighbour->setLeftBridge( $this->getRightBridge() );
		parent::setRightNeighbour( $rightNeighbour );
	}
	
	public function setTopNeighbour( LayerDot $topNeighbour )
	{
		$topNeighbour->setBottomBridge( $this->getTopBridge() );
		parent::setTopNeighbour( $topNeighbour );
	}
	
	public function setBottomNeighbour( LayerDot $bottomNeighbour )
	{
		$bottomNeighbour->setTopBridge( $this->getBottomBridge() );
		parent::setBottomNeighbour( $bottomNeighbour );
	}
	
	//---------------------------------------------------------------------//
	// Bridges.                                                            //
	//---------------------------------------------------------------------//
	
	public function getLeftBridge()
	{
		return $this->leftBridge;
	}
	
	public function setLeftBridge( $leftBridge )
	{
		$this->leftBridge = $leftBridge;
		
		$leftNeighbour = $this->getLeftNeighbour();
		if( ! is_null( $leftNeighbour ) )
		{
			if( $leftNeighbour->getRightBridge() != $leftBridge )
			{
				$leftNeighbour->setRightBridge( $leftBridge );
			}
		}
	}
	
	public function getRightBridge()
	{
		return $this->rightBridge;
	}
	
	public function setRightBridge( $rightBridge )
	{
		$this->rightBridge = $rightBridge;
		
		$rightNeighbour = $this->getRightNeighbour();
		if( ! is_null( $rightNeighbour ) )
		{
			if( $rightNeighbour->getLeftBridge() != $rightBridge )
			{
				$rightNeighbour->setLeftBridge( $rightBridge );
			}
		}
	}
	
	public function getTopBridge()
	{
		return $this->topBridge;
	}
	
	public function setTopBridge( $topBridge )
	{
		$this->topBridge = $topBridge;
		
		$topNeighbour = $this->getTopNeighbour();
		if( ! is_null( $topNeighbour ) )
		{
			if( $topNeighbour->getBottomBridge() != $topBridge )
			{
				$topNeighbour->setBottomBridge( $topBridge );
			}
		}
	}
	
	public function getBottomBridge()
	{
		return $this->bottomBridge;
	}
	
	public function setBottomBridge( $bottomBridge )
	{
		$this->bottomBridge = $bottomBridge;
		
		$bottomNeighbour = $this->getBottomNeighbour();
		if( ! is_null( $bottomNeighbour ) )
		{
			if( $bottomNeighbour->getTopBridge() != $bottomBridge )
			{
				$bottomNeighbour->setTopBridge( $bottomBridge );
			}
		}
	}
}
