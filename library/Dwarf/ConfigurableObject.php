<?php

namespace Dwarf;

class ConfigurableObject extends Object {
    
    protected $config;
    
    public function getConfig( $key = null, $default = null ) {
        
        if( !isset( $this->config ) )
            return $this->config = new Config;
        
        if( $key ) {
            
            if( !isset( $this->config[ $key ] ) )
                return $default;
            
            return $this->config[ $key ];
        }
        
        return $this->config;
    }
    
    public function setConfig( $config, $value = null ) {
        
        if( !isset( $this->config ) )
            $this->config = new Config;
        
        if( $value !== null ) {
            
            $this->config[ (string)$config ] = $value;
            
            return $this;
        }
        
        $config = Config::box( $config );
        
        $this->config->merge( $config );
        
        return $this;
    }
}