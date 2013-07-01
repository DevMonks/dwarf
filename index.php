<?php

define( 'LIBRARY_PATH',     __DIR__.'/library'  );
define( 'APP_PATH',         __DIR__.'/app'      );
define( 'APP_ENV', empty( $_ENV[ 'DWARF_ENV' ] ) ? 'development' : $_ENV[ 'DWARF_ENV' ] );

include LIBRARY_PATH.'/Dwarf.inc';


App::run( APP_PATH, APP_ENV );