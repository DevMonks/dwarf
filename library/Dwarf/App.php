<?php

namespace Dwarf;

class App extends Object {
    use Configurable;
    
    protected $controllerLoader;
    protected $modelLoader;
    protected $viewLoader;
    
    public function __construct( $config = [] ) {
        
        $this->setConfig( [
            'directory' => 'app'
        ] );
        $this->setConfig( $config );
        
        $controllerConfig = $this->getConfig( 'controllers' );
        $modelConfig = $this->getConfig( 'models' );
        $viewConfig = $this->getConfig( 'views' );
        
        //extend the paths (make them relative to app path)
        foreach( [ $controllerConfig, $modelConfig, $viewConfig ] as $cfg )
                
            if( $cfg
             && $cfg->directory )
                $cfg->directory = Path::combine( 
                    $this->getConfig( 'directory' ), 
                    $cfg->directory,
                    false
            );
        
        $this->viewLoader = new ViewLoader( $viewConfig );
        $controllerConfig->viewLoader = $this->viewLoader;
        $this->controllerLoader = new ControllerLoader( $controllerConfig );
        $this->modelLoader = new ModelLoader( $modelConfig );
        
        $this->enableLoaders();
    }
    
    public function enableLoaders() {
        
        foreach( [ $this->viewLoader, $this->controllerLoader, $this->modelLoader ] as $loader )
            $loader->enable();
    }
    
    public function disableLoaders() {
        
        foreach( [ $this->viewLoader, $this->controllerLoader, $this->modelLoader ] as $loader )
            $loader->disable();
    }
    
    public static function run( $directory, $config = null ) {
        
        $cfg = null;
        if( $config )
            $cfg = Config::load( Path::combine( $directory, $config ) );
        
        if( isset( $cfg->app ) && !isset( $cfg->app->directory ) )
            $cfg->app->directory = $directory;
        
        $app = new static( isset( $cfg->app ) ? $cfg->app : [] );
        //TODO: Routing etc.
        
        return $app;
    }
}