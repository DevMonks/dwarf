<?php

if( version_compare( PHP_VERSION, '5.5.0' ) < 0 ) {

	?>
	<h1>Time to upgrade!</h1>
	<p>
		Dwarf won't run with your PHP version (<?=PHP_VERSION?>). You need at least PHP 5.5.0.<br>
		<br>
		<strong>But why don't you make it compatible?</strong><br>
		Web is advancing, technology is advancing and thousands of people are waiting for everyone to upgrade in order to finally be able to use the new technologies. Making things compatible to old versions of software makes people stick to those old versions and we can never use the new stuff, because no one supports and uses it.<br>
		<strong>We don't want that</strong><br>
		It's as simple as that.
	</p>
	<?php
	exit;
}


if( file_exists( 'vendor/autoload.php' ) ) {
    
    //Use existing autoloader of Composer
    $loader = require_once( 'vendor/autoload.php' );
    $loader->add( 'Dwarf\\', __DIR__ );
    
} else {

    //Set the current directory as include path and initiate autoloader
    set_include_path( __DIR__.PATH_SEPARATOR.get_include_path() );
    include 'Loader.php';
    
    $loader = new Dwarf\Loader;
    $loader->enable();
}