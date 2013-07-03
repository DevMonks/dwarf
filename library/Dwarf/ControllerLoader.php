<?php

namespace Dwarf;

class ControllerLoader extends Loader {
    
    protected $db;
    protected $viewLoader;
    
    public function __construct( $config = [] ) {
        
        $this->setConfig( [
            'directory' => 'controllers',
            'namespace' => '',
            'startController' => 'home',
            'defaultAction' => 'index',
            'viewController' => null
        ] );
        $this->setConfig( $config );
        
        if( $this->getConfig( 'viewController' ) )
            $this->viewController = $this->getConfig( 'viewController' );
    }
    
    public function setViewLoader( ViewLoader $viewLoader ) {
        
        $this->viewLoader = $viewLoader;
        $this->setConfig( 'viewLoader', $this->viewLoader );
    }
    
    public function getViewLoader() {
        
        if( !isset( $this->viewLoader ) )
            $this->viewLoader = new ViewLoader( $this->getConfig( 'views' ) );
        
        return $this->viewLoader;
    }
    
    public function setDb( Db $db ) {
        
        $this->db = $db;
    }
    
    public function getDb() {
        
        return $this->db;
    }
    
    public function dispatch( $controller = null, $action = null, $id = null ) {
        
        //TODO: write logic
    }
    
    public function load( $class ) {
        
        if( !preg_match( '/Controller$/', $class ) )
                return false;
        
        //strip the namespace
        $controller = $class;
        $directory = $this->getConfig( 'directory' );
        $namespace = $this->getConfig( 'namespace' );
        
        if( !empty( $namespace ) && substr( $controller, 0, strlen( $namespace ) ) === $namespace ) {
            
            $controller = substr( $controller, strlen( $namespace ) );
        }
        
        $path = Path::combine( 
            $directory, 
            str_replace( 
                [ '_', '\\' ], 
                DIRECTORY_SEPARATOR, 
                preg_replace( '/Controller$/', '', $controller ).'.php'
            )
        );
        
        if( $path->exists() )
            include $path;
        
        return class_exists( $class, false );
    }
}