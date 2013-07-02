<?php

namespace Dwarf;

class App extends ConfigurableObject {
    
    protected $controllerController;
    protected $modelController;
    protected $viewController;
    
    public function __construct( $config = null ) {
        
        $this->setConfig( [
            'directory' => 'app'
        ] );
        $this->setConfig( $config );
        
        $controllerConfig = $this->getConfig( 'controllers' );
        $modelConfig = $this->getConfig( 'models' );
        $viewConfig = $this->getConfig( 'views' );
        
        //extend the paths (make them relative to app path)
        if( $controllerConfig
         && $controllerConfig->directory )
            $controllerConfig->directory = Path::combine( 
                $this->getConfig( 'directory' ), 
                $controllerConfig->directory,
                false
        );
        
        if( $viewConfig
         && $viewConfig->directory )
            $viewConfig->directory = Path::combine( 
                $this->getConfig( 'directory' ), 
                $viewConfig->directory,
                false
        );
        
        if( $modelConfig
         && $modelConfig->directory )
            $modelConfig->directory = Path::combine( 
                $this->getConfig( 'directory' ), 
                $modelConfig->directory,
                false
        );
        
        $this->viewController = new ViewController( $viewConfig );
        $this->controllerController = new ControllerController( $controllerConfig );
        $this->modelController = new ModelController( $modelConfig );
        
        $this->controllerController->setViewController( $this->viewController );
    }
    
    public static function run( $directory, $config = null ) {
        
        $cfg = null;
        if( $config )
            $cfg = Config::load( Path::combine( $directory, $config ) );
        
        if( isset( $cfg->app ) && !isset( $cfg->app->directory ) )
            $cfg->app->directory = $directory;
        
        var_dump( $cfg );
        
        $app = new static( isset( $cfg->app ) ? $cfg->app : null );
        //TODO: Routing etc.
        
        return $app;
    }
}