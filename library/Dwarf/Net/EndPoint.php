<?php

namespace Dwarf\Net;

class EndPoint extends \Dwarf\Object {
    
    const PORT_HTTP = 80;
    const PORT_HTTPS = 443;
    const PORT_FTP = 21;
    
    protected $host;
    protected $port;
    
    public function __construct( $host = null, $port = null ) {
        
        if( $host ) {
            
            if( strpos( $host, ':' ) !== false )
                $this->set( $host );
            else
                $this->setHost( $host );
        }
        
        if( $port )
            $this->setPort( $port );
    }
    
    public function set( $endPoint ) {
        
        $parts = explode( ':', $endPoint );
        
        if( count( $parts ) == 1 )
            $this->setHost( $parts[ 0 ] );
        else if( count( $parts ) == 2 ) {
            
            $this->setHost( $parts[ 0 ] );
            $this->setPort( $parts[ 1 ] );
        }
    }
    
    public function getHost() {
        
        return $this->host;
    }
    
    public function setHost( $host ) {
        
        $this->host = $host;
    }
    
    public function getPort() {
        
        return $this->port;
    }
    
    public function setPort( $port ) {
        
        $this->port = intval( $port );
    }
    
    public function getSocket() {
        
        return new Socket( $this );
    }
    
    public function __toString() {
        
        $ep = empty( $this->host ) ? '' : $this->host;
        
        if( !empty( $this->port ) )
            $ep .= ":$this->port";
        
        return $ep;
    }
}