<?php
namespace Xmontero\EpigraphInvalidation\Controller;

// Onscreen debug.
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

// Include the icon generator class.
require_once( "IconGenerator.php" );

// Controller class.
class DefaultController
{
	public function run()
	{
		$ig = new \Xmontero\EpigraphInvalidation\IconGenerator( "abcd" );
		$ig->output( 72 );
	}
}

// Invoke the controller.
$c = new DefaultController;
$c->run();
