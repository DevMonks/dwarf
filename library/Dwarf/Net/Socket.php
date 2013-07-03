<?php

namespace Dwarf\Net;

class Socket extends \Dwarf\Stream {
    
    protected $endPoint;
    protected $timeOut = 3;
    
    public function __construct( $host, $port = null, $timeOut = null ) {
        
        if( $host instanceof EndPoint )
            $this->endPoint = $host;
        else
            $this->endPoint = new EndPoint( $host, $port );
        
        if( $timeOut )
            $this->timeOut = $timeOut;
    }
    
    public function getEndPoint() {
        
        return $this->endPoint;
    }
    
    public function open() {
        
        $code = null;
        $msg = null;
        
        parent::__construct( fsockopen( 
            $this->endPoint->getHost(), 
            $this->endPoint->getPort(),
            $code,
            $msg,
            $this->timeOut
        ) );
    }
}