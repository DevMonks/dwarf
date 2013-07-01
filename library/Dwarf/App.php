<?php

namespace Dwarf;

class App extends Object {
    use Configurable;
    
    protected $pageController;
    protected $modelController;
    protected $viewController;
    protected $env;
    
    public static function run( $appPath, $env ) {
        
        
    }
}