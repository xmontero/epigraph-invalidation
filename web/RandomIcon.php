<?php
namespace Xmontero\EpigraphInvalidation\Controller;

// Onscreen debug.
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

// Include the icon generator class.
require_once( "../src/IconGenerator.php" );

// Controller class.
class DefaultController
{
	public function run()
	{
		$key = $this->getKey();
		$size = $this->getSize();
		
		$ig = new \Xmontero\EpigraphInvalidation\IconGenerator( $key );
		$ig->output( $size );
	}
	
	public function getKey()
	{
		$key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_ENCODED );
		
		if( is_null( $key ) )
		{
			throw new \InvalidArgumentException( 'The parameter "key" must be present and be a string.' );
		}
		else
		{
			if( $key == false )
			{
				throw new \InvalidArgumentException( 'The parameter "key" must be a string.' );
			}
		}
		
		return $key;
	}
	
	public function getSize()
	{
		$minSize = 4;
		$maxSize = 1024;
		
		$sizeOptions = array( 'options' => array( 'min_range' => $minSize, 'max_range' => $maxSize ) );
		$size = filter_input( INPUT_GET, 'size', FILTER_VALIDATE_INT, $sizeOptions );
		
		if( is_null( $size ) )
		{
			throw new \InvalidArgumentException( 'The parameter "size" must be present and be an integer between ' . $minSize . ' and ' . $maxSize . '.' );
		}
		else
		{
			if( $size === false )
			{
				throw new \InvalidArgumentException( 'The parameter "size" must be an integer between ' . $minSize . ' and ' . $maxSize . '.' );
			}
		}
		
		return $size;
	}
}

// Invoke the controller.
$c = new DefaultController;
$c->run();
