<?php
namespace Xmontero\EpigraphInvalidation;

class LayerDownDot extends LayerDot
{
	private $topLeftUnderlyingVertex = false;
	private $topRightUnderlyingVertex = false;
	private $bottomLeftUnderlyingVertex = false;
	private $bottomRightUnderlyingVertex = false;
	
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
