<?php

namespace Dwarf;

class ControllerController extends ConfigurableObject {
    
    protected $db;
    protected $viewController;
    
    public function __construct( $config = [] ) {
        
        $this->setConfig( [
            'directory' => 'controllers',
            'namespace' => '',
            'startController' => 'home',
            'defaultAction' => 'index'
        ] );
        $this->setConfig( $config );
    }
    
    public function setViewController( ViewController $viewController ) {
        
        $this->viewController = $viewController;
    }
    
    public function getViewController() {
        
        if( !isset( $this->viewController ) )
            $this->viewController = new ViewController( $this->getConfig( 'views' ) );
        
        return $this->viewController;
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
    
    public function dispatch( $controller = null, $action = null, $id = null ) {
        
        //TODO: write logic
    }
    
    protected function load( $class ) {
        
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
                preg_replace( '/Controller$/', $controller ).'.php'
            )
        );
        
        if( $path->exists() )
            include $path;
        
        return class_exists( $class, false );
    }
}