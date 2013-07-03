<?php

namespace Dwarf\Net;

//TODO: Implement Encoding support (BASE64 conversion etc.)
class Credentials extends \Dwarf\Object {
    
    protected $user;
    protected $password;
    
    public function __construct( $user = null, $password = null ) {
        
        if( $user ) {
            
            if( strpos( $user, ':' ) !== false )
                $this->set( $user );
            else
                $this->setUser( $user );
        }
    }
    
    public function set( $cred ) {
        
        $parts = explode( ':', $cred );
        
        if( count( $parts ) == 1 )
            $this->setUser( $parts[ 0 ] );
        else if( count( $parts ) == 2 ) {
            
            $this->setUser( $parts[ 0 ] );
            $this->setPassword( $parts[ 1 ] );
        }
    }
    
    public function getUser() {
        
        return $this->user;
    }
    
    public function setUser( $user ) {
        
        $this->user = $user;
    }
    
    public function getPassword() {
        
        return $this->password;
    }
    
    public function setPassword( $password ) {
        
        $this->password = $password;
    }
    
    public function __toString() {
        
        $cred = empty( $this->user ) ? '' : $this->user;
        
        if( !empty( $this->password ) )
            $cred .= ":$this->password";
        
        return $cred;
    }
}