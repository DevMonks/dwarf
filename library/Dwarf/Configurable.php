<?php

namespace Dwarf;

trait Configurable {
    
    protected $configDefaults = array();
    protected $config = array();
    
    public function getConfig( $key = null, $default = null ) {
        
        if( $key ) {
            
            if( !isset( $this->config->$key ) )
                return !$default 
                       ? ( !isset( $this->configDefaults[ $key ] ) 
                         ? $default 
                         : $this->configDefaults[ $key ] )
                       : $default;
            
            return $this->config->$key;
        }
        
        return $this->config;
    }
    
    public function setConfig( Config $config ) {
        
        $this->config = $config;
    }
}