<?php

namespace Dwarf\Environments;

class DevelopmentEnvironment extends Dwarf\Environment {
    
    protected $logStream;
    
    public function log( $message, $severity = self::LOG_INFO ) {
        
        $this->logStream->writeLine( $message );
    }
    
    public function bind() {
        parent::bind();
        
        $this->logStream = Dwarf\Stream::openWrite( $this->getConfig( 'logFile', 'default.log' ) );
    }
    
    public function unbind() {
        parent::unbind();
        
        //will (hopefully, but still testing) close and destruct correctly
        //(even though PHP does it in the end, anyways)
        $this->logStream = null;
    }
    
    public function handleError( $code, $message, $file, $line ) {
        
        throw new \ErrorException( $message, 0, $code, $file, $line );
    }
    
    public function handleException( \Exception $e ) {
        
        echo $e->getMessage();
    }
}