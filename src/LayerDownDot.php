<?php
namespace Xmontero\EpigraphInvalidation;

class LayerDownDot
{
	private $filled;
	private $leftNeighbour = null;
	private $rightNeighbour = null;
	private $topNeighbour = null;
	private $bottomNeighbour = null;
	private $topLeftUnderlyingVertex = false;
	private $topRightUnderlyingVertex = false;
	private $bottomLeftUnderlyingVertex = false;
	private $bottomRightUnderlyingVertex = false;
	
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
	
	public function setLeftNeighbour( LayerDownDot $leftNeighbour)
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
	
	public function setRightNeighbour( LayerDownDot $rightNeighbour)
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
	
	public function setTopNeighbour( LayerDownDot $topNeighbour)
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
	
	public function setBottomNeighbour( LayerDownDot $bottomNeighbour)
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
	// Underlying vertexes.                                                //
	//---------------------------------------------------------------------//
	
	public function getTopLeftUnderlyingVertex()
	{
		return $this->topLeftUnderlyingVertex;
	}
	
	public function setTopLeftUnderlyingVertex( $newUnderlyingVertex )
	{
		$this->topLeftUnderlyingVertex = $newUnderlyingVertex;
	}
	
	public function getTopRightUnderlyingVertex()
	{
		return $this->topRightUnderlyingVertex;
	}
	
	public function setTopRightUnderlyingVertex( $newUnderlyingVertex )
	{
		$this->topRightUnderlyingVertex = $newUnderlyingVertex;
	}
	
	public function getBottomLeftUnderlyingVertex()
	{
		return $this->bottomLeftUnderlyingVertex;
	}
	
	public function setBottomLeftUnderlyingVertex( $newUnderlyingVertex )
	{
		$this->bottomLeftUnderlyingVertex = $newUnderlyingVertex;
	}
	
	public function getBottomRightUnderlyingVertex()
	{
		return $this->bottomRightUnderlyingVertex;
	}
	
	public function setBottomRightUnderlyingVertex( $newUnderlyingVertex )
	{
		$this->bottomRightUnderlyingVertex = $newUnderlyingVertex;
	}
	
	//---------------------------------------------------------------------//
	// Computed vertexes.                                                  //
	//---------------------------------------------------------------------//
	
	public function getTopLeftVertex()
	{
		$topNeighbour = $this->getTopNeighbour();
		$leftNeighbour = $this->getLeftNeighbour();
		$topLeftNeighbour = $this->getTopLeftNeighbour();
		$topLeftUnderlyingVertex = $this->getTopLeftUnderlyingVertex();
		
		$result = $this->getGenericVertexFromGenericNeighbours( $topNeighbour, $leftNeighbour, $topLeftNeighbour, $topLeftUnderlyingVertex );
		return $result;
	}
	
	public function getTopRightVertex()
	{
		$topNeighbour = $this->getTopNeighbour();
		$rightNeighbour = $this->getRightNeighbour();
		$topRightNeighbour = $this->getTopRightNeighbour();
		$topRightUnderlyingVertex = $this->getTopRightUnderlyingVertex();
		
		$result = $this->getGenericVertexFromGenericNeighbours( $topNeighbour, $rightNeighbour, $topRightNeighbour, $topRightUnderlyingVertex );
		return $result;
	}
	
	public function getBottomLeftVertex()
	{
		$bottomNeighbour = $this->getBottomNeighbour();
		$leftNeighbour = $this->getLeftNeighbour();
		$bottomLeftNeighbour = $this->getBottomLeftNeighbour();
		$bottomLeftUnderlyingVertex = $this->getBottomLeftUnderlyingVertex();
		
		$result = $this->getGenericVertexFromGenericNeighbours( $bottomNeighbour, $leftNeighbour, $bottomLeftNeighbour, $bottomLeftUnderlyingVertex );
		return $result;
	}
	
	public function getBottomRightVertex()
	{
		$bottomNeighbour = $this->getBottomNeighbour();
		$rightNeighbour = $this->getRightNeighbour();
		$bottomRightNeighbour = $this->getBottomRightNeighbour();
		$bottomRightUnderlyingVertex = $this->getBottomRightUnderlyingVertex();
		
		$result = $this->getGenericVertexFromGenericNeighbours( $bottomNeighbour, $rightNeighbour, $bottomRightNeighbour, $bottomRightUnderlyingVertex );
		return $result;
	}
	
	//---------------------------------------------------------------------//
	// Private                                                             //
	//---------------------------------------------------------------------//
	
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
	
	private function getIncoherentStraightNeighbours( LayerDownDot $dot1 = null, LayerDownDot $dot2 = null )
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
	
	private function getSameDiagonalNeighbour( LayerDownDot $dot1 = null, LayerDownDot $dot2 = null )
	{
		if( $dot1 !== $dot2 )
		{
			throw new \RuntimeException( 'Not same LayerDownDots as diagonal neighbours.' );
		}
		
		return $dot1;
	}
	
	private function getGenericVertexFromGenericNeighbours( $directNeighbour1, $directNeighbour2, $diagonalNeighbour, $underlyingVertex )
	{
		$case = $this->getCaseFromGenericNeighbours( $directNeighbour1, $directNeighbour2, $diagonalNeighbour );
		$result = $this->getVertexResultFromGenericNeighboursAndCase( $case, $directNeighbour1, $directNeighbour2, $diagonalNeighbour, $underlyingVertex );
		return $result;
	}
	
	private function getCaseFromGenericNeighbours( $directNeighbour1, $directNeighbour2, $diagonalNeighbour )
	{
		if( is_null( $directNeighbour1 ) )
		{
			if( is_null( $directNeighbour2 ) )
			{
				$case = 'bothNull';
			}
			else
			{
				$case = 'neighbour1Null';
			}
		}
		else
		{
			if( is_null( $directNeighbour2 ) )
			{
				$case = 'neighbour2Null';
			}
			else
			{
				$case = 'noneNull';
			}
		}
		
		return $case;
	}
	
	private function getVertexResultFromGenericNeighboursAndCase( $case, $directNeighbour1, $directNeighbour2, $diagonalNeighbour, $underlyingVertex )
	{
		$result = null;
		
		switch( $case )
		{
			case 'bothNull':
				
				$result = false;
				break;
				
			case 'neighbour1Null':
				
				$result = $this->isFilled() ? $directNeighbour2->isFilled() : false;
				break;
				
			case 'neighbour2Null':
				
				$result = $this->isFilled() ? $directNeighbour1->isFilled() : false;
				break;
				
			case 'noneNull':
				
				$result = $this->getVertexResultFromGenericNeighboursBothNonNull( $directNeighbour1, $directNeighbour2, $diagonalNeighbour, $underlyingVertex );
				break;
		}
		
		return $result;
	}
	
	private function getVertexResultFromGenericNeighboursBothNonNull( $directNeighbour1, $directNeighbour2, $diagonalNeighbour, $underlyingVertex )
	{
		$fillCount = $this->countFilledFromGenericNeighbours( $directNeighbour1, $directNeighbour2, $diagonalNeighbour );
		
		$minority = 1;
		$majority = 3;
		if( $fillCount <= $minority )
		{
			$result = false;
		}
		elseif( $fillCount >= $majority )
		{
			$result = true;
		}
		else // $fillCount == 2
		{
			if( $this->isFilled() == $diagonalNeighbour->isFilled() )
			{
				// + -  or  - +
				// - +  or  + -
				$result = $underlyingVertex;
			}
			else
			{
				// + +  or  + -  or  - -  or  - +
				// - -  or  + -  or  + +  or  - +
				$result = $this->isFilled();
			}
		}
		
		return $result;
	}
	
	private function countFilledFromGenericNeighbours( $directNeighbour1, $directNeighbour2, $diagonalNeighbour )
	{
		$fillCount = 0;
		
		if( $this->isFilled() )
		{
			$fillCount++;
		}
		
		if( $directNeighbour1->isFilled() )
		{
			$fillCount++;
		}
		
		if( $directNeighbour2->isFilled() )
		{
			$fillCount++;
		}
		
		if( $diagonalNeighbour->isFilled() )
		{
			$fillCount++;
		}
		
		return $fillCount;
	}
}
