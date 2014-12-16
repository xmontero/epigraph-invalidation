<?php
namespace Xmontero\EpigraphInvalidation;

class LayerDownDot
{
	private $filled;
	private $leftNeighbour;
	private $rightNeighbour;
	private $topNeighbour;
	private $bottomNeighbour;
	
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
	
	public function getLeftNeighbour()
	{
		return $this->leftNeighbour;
	}
	
	public function setLeftNeighbour( LayerDownDot $leftNeighbour)
	{
		$this->leftNeighbour = $leftNeighbour;
	}
	
	public function getRightNeighbour()
	{
		return $this->rightNeighbour;
	}
	
	public function setRightNeighbour( LayerDownDot $rightNeighbour)
	{
		$this->rightNeighbour = $rightNeighbour;
	}
	
	public function getTopNeighbour()
	{
		return $this->topNeighbour;
	}
	
	public function setTopNeighbour( LayerDownDot $topNeighbour)
	{
		$this->topNeighbour = $topNeighbour;
	}
	
	public function getBottomNeighbour()
	{
		return $this->bottomNeighbour;
	}
	
	public function setBottomNeighbour( LayerDownDot $bottomNeighbour)
	{
		$this->bottomNeighbour = $bottomNeighbour;
	}
	
	public function getTopLeftNeighbour()
	{
		$topLeft = $this->getCoherentStraightNeighbours( $this->getTopNeighbour(), $this->getLeftNeighbour() );
		
		if( is_null( $topLeft ) )
		{
			$result = null;
		}
		else
		{
			$top = $topLeft[ 'dot1' ];
			$left = $topLeft[ 'dot2' ];
			
			$result = $this->getSameDiagonalNeighbour( $top->getLeftNeighbour(), $left->getTopNeighbour() );
		}
		
		return $result;
	}
	
	public function getTopRightNeighbour()
	{
		$topRight = $this->getCoherentStraightNeighbours( $this->getTopNeighbour(), $this->getRightNeighbour() );
		
		if( is_null( $topRight ) )
		{
			$result = null;
		}
		else
		{
			$top = $topRight[ 'dot1' ];
			$right = $topRight[ 'dot2' ];
			
			$result = $this->getSameDiagonalNeighbour( $top->getRightNeighbour(), $right->getTopNeighbour() );
		}
		
		return $result;
	}
	
	public function getBottomLeftNeighbour()
	{
		$bottomLeft = $this->getCoherentStraightNeighbours( $this->getBottomNeighbour(), $this->getLeftNeighbour() );
		
		if( is_null( $bottomLeft ) )
		{
			$result = null;
		}
		else
		{
			$bottom = $bottomLeft[ 'dot1' ];
			$left = $bottomLeft[ 'dot2' ];
			
			$result = $this->getSameDiagonalNeighbour( $bottom->getLeftNeighbour(), $left->getBottomNeighbour() );
		}
		
		return $result;
	}
	
	public function getBottomRightNeighbour()
	{
		$bottomRight = $this->getCoherentStraightNeighbours( $this->getBottomNeighbour(), $this->getRightNeighbour() );
		
		if( is_null( $bottomRight ) )
		{
			$result = null;
		}
		else
		{
			$bottom = $bottomRight[ 'dot1' ];
			$right = $bottomRight[ 'dot2' ];
			
			$result = $this->getSameDiagonalNeighbour( $bottom->getRightNeighbour(), $right->getBottomNeighbour() );
		}
		
		return $result;
	}
	
	private function getCoherentStraightNeighbours( LayerDownDot $dot1 = null, LayerDownDot $dot2 = null )
	{
		if( is_null( $dot1 ) && is_null( $dot2 ) )
		{
			$result = null;
		}
		else
		{
			if( ( ! is_null( $dot1 ) ) && ( ! is_null( $dot2 ) ) )
			{
				$result = array( "dot1" => $dot1, "dot2" => $dot2 );
			}
			else
			{
				throw new \Exception( 'Incoherent LayerDownDots as straight neighbours.' );
			}
		}
		
		return $result;
	}
	
	private function getSameDiagonalNeighbour( LayerDownDot $dot1 = null, LayerDownDot $dot2 = null )
	{
		if( $dot1 !== $dot2 )
		{
			throw new \Exception( 'Not same LayerDownDots as diagonal neighbours.' );
		}
		
		return $dot1;
	}
}
