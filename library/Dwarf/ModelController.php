<?php

namespace Dwarf;

class ModelController extends ConfigurableObject {
    
    protected $db;
    
    public function __construct( $config = [] ) {
        
        $this->setConfig( [
            'directory' => 'models',
            'namespace' => '',
            'check' => []
        ] );
        $this->setConfig( $config );
    }
    
    public function enable() {
        
        spl_autoload_register( [ $this, 'load' ] );
    }
    
    public function disable() {
        
        spl_autoload_unregister( [ $this, 'load' ] );
    }
    
    public function setDb( Db $db ) {
        
        $this->db = $db;
    }
    
    public function getDb() {
        
        return $this->db;
    }
    
    public function checkIntegrity() {
        
        //TODO: write logic, that pulls data from database and writes the models
        //accordingly (based on the "check" setting, true for ALL tables)
    }
    
    protected function load( $class ) {
        
        //strip the namespace
        $model = $class;
        $directory = $this->getConfig( 'directory' );
        $namespace = $this->getConfig( 'namespace' );
        
        if( !empty( $namespace ) && substr( $model, 0, strlen( $namespace ) ) === $namespace ) {
            
            $model = substr( $model, strlen( $namespace ) );
        }
        
        $path = Path::combine( 
            $directory, 
            str_replace( [ '_', '\\' ], DIRECTORY_SEPARATOR, $model ).'.php' 
        );
        
        if( $path->exists() )
            include $path;
        
        return class_exists( $class, false );
    }
}