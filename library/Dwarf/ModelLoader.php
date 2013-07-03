<?php

namespace Dwarf;

class ModelLoader extends Loader {
    
    protected $db;
    
    public function __construct( $config = [] ) {
        
        $this->setConfig( [
            'directory' => 'models',
            'namespace' => '',
            'check' => []
        ] );
        $this->setConfig( $config );
        
        if( !empty( $this->getConfig( 'check' ) ) )
            $this->checkIntegrity();
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
    
    public function load( $class ) {
        
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