<?php

$loader = require( 'vendor/autoload.php' );

define( 'DWARF_ENV', isset( $_ENV[ 'DWARF_ENV' ] ) ? $_ENV[ 'DWARF_ENV' ] : 'development' );


Dwarf\App::run( 'app/', DWARF_ENV );