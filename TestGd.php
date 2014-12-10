<?php

// Code taken/inspired from here:
// http://www.zoopable.com/check-php-gd-library-installed-or-not/

if( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) )
{
	echo( "OK - PHP GD library is installed on your web server" );
}
else
{
	echo( "FAIL - PHP GD library is NOT installed on your web server" );
}
