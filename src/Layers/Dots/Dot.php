<?php
namespace Xmontero\EpigraphInvalidation\Layers\Dots;

class Dot
{
	private $filled;
	
	private $leftNeighbour = null;
	private $rightNeighbour = null;
	private $topNeighbour = null;
	private $bottomNeighbour = null;
	
	//---------------------------------------------------------------------//
	// Public                                                              //
	//---------------------------------------------------------------------//
	
	public function __construct()
	{
		$this->clear();
	}
	
	//---------------------------------------------------------------------//
	// Filled.                                                             //
	//---------------------------------------------------------------------//
	
	public function isFilled()
	{
		return $this->filled;
	}
	
	public function setFilled( $filled )
	{
		$this->filled = $filled;
	}
	
	public function fill()
	{
		$this->setFilled( true );
	}
	
	public function clear()
	{
		$this->setFilled( false );
	}
	
	//---------------------------------------------------------------------//
	// Direct neighbours.                                                  //
	//---------------------------------------------------------------------//
	
	public function getLeftNeighbour()
	{
		return $this->leftNeighbour;
	}
	
	public function setLeftNeighbour( Dot $leftNeighbour)
	{
		$this->leftNeighbour = $leftNeighbour;
		
		$previousReciprocalNeighbour = $leftNeighbour->getRightNeighbour();
		if( $previousReciprocalNeighbour !== $this )
		{
			$leftNeighbour->setRightNeighbour( $this );
		}
	}
	
	public function getRightNeighbour()
	{
		return $this->rightNeighbour;
	}
	
	public function setRightNeighbour( Dot $rightNeighbour)
	{
		$this->rightNeighbour = $rightNeighbour;
		
		$previousReciprocalNeighbour = $rightNeighbour->getLeftNeighbour();
		if( $previousReciprocalNeighbour !== $this )
		{
			$rightNeighbour->setLeftNeighbour( $this );
		}
	}
	
	public function getTopNeighbour()
	{
		return $this->topNeighbour;
	}
	
	public function setTopNeighbour( Dot $topNeighbour)
	{
		$this->topNeighbour = $topNeighbour;
		
		$previousReciprocalNeighbour = $topNeighbour->getBottomNeighbour();
		if( $previousReciprocalNeighbour !== $this )
		{
			$topNeighbour->setBottomNeighbour( $this );
		}
	}
	
	public function getBottomNeighbour()
	{
		return $this->bottomNeighbour;
	}
	
	public function setBottomNeighbour( Dot $bottomNeighbour)
	{
		$this->bottomNeighbour = $bottomNeighbour;
		
		$previousReciprocalNeighbour = $bottomNeighbour->getTopNeighbour();
		if( $previousReciprocalNeighbour !== $this )
		{
			$bottomNeighbour->setTopNeighbour( $this );
		}
	}
	
	//---------------------------------------------------------------------//
	// Diagonal neighbours.                                                //
	//---------------------------------------------------------------------//
	
	public function getTopLeftNeighbour()
	{
		$topLeft = $this->getIncoherentStraightNeighbours( $this->getTopNeighbour(), $this->getLeftNeighbour() );
		
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
		$topRight = $this->getIncoherentStraightNeighbours( $this->getTopNeighbour(), $this->getRightNeighbour() );
		
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
		$bottomLeft = $this->getIncoherentStraightNeighbours( $this->getBottomNeighbour(), $this->getLeftNeighbour() );
		
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
		$bottomRight = $this->getIncoherentStraightNeighbours( $this->getBottomNeighbour(), $this->getRightNeighbour() );
		
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
	
	//---------------------------------------------------------------------//
	// Private                                                             //
	//---------------------------------------------------------------------//
	
	private function getCoherentStraightNeighbours( Dot $dot1 = null, Dot $dot2 = null )
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
	
	private function getIncoherentStraightNeighbours( Dot $dot1 = null, Dot $dot2 = null )
	{
		try
		{
			$result = $this->getCoherentStraightNeighbours( $dot1, $dot2 );
		}
		catch( \Exception $e )
		{
			$result = null;
		}
		
		return $result;
	}
	
	private function getSameDiagonalNeighbour( Dot $dot1 = null, Dot $dot2 = null )
	{
		if( $dot1 !== $dot2 )
		{
			throw new \RuntimeException( 'Not same LayerDownDots as diagonal neighbours.' );
		}
		
		return $dot1;
	}
}
