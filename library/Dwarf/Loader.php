<?php

namespace Dwarf;


$required = [ 'Object', 'Container', 'Config', 'Configurable' ];

foreach( $required as $req )
    if( !class_exists( __NAMESPACE__."\\$req" ) && !trait_exists( __NAMESPACE__."\\$req" ) )
        require_once dirname( __FILE__ )."/$req.php";


class Loader extends Object {
    use Configurable;
    
    protected $enabled = false;
    
    public function __construct( $config = [] ) {
        
        $this->setConfig( [
            'seperators' => [ '\\', '_' ],
            'extension' => '.php'
        ] );
    }
    
    public function enable() {
        
        if( $this->enabled )
            return $this;
        
        spl_autoload_register( [ $this, 'load' ] );
        $this->enabled = true;
        
        return $this;
    }
    
    public function disable() {
        
        if( !$this->enabled )
            return $this;
        
        spl_autoload_unregister( [ $this, 'load' ] );
        $this->enabled = false;
        
        return $this;
    }
    
    public function isEnabled() {
        
        return $this->enabled;
    }
    
    public function load( $class ) {
        
        $path = str_replace( $this->getConfig( 'separators' ), DIRECTORY_SEPARATOR, $class ).$this->getConfig( 'extension' );
        
        if( stream_resolve_include_path( $path ) )
            include $path;
        
        return class_exists( $class, false );
    }
}