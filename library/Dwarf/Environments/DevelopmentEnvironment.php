<?php

namespace Dwarf\Environments;

class DevelopmentEnvironment extends \Dwarf\Environment {
    
    protected $logHandle;
    
    public function log( $message, $severity = self::LOG_INFO ) {
        
        
    }
    
    public function bind() {
        parent::bind();
        
        $this->logHandle = fopen( $this->getConfig( 'logFile', 'log.txt' ) )
    }
    
    public function handleError( $code, $message, $file, $line ) {
        
        throw new \ErrorException( $message, 0, $code, $file, $line );
    }
    
    public function handleException( \Exception $e ) {
        
        echo $e->getMessage();
    }
}