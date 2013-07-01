<?php

namespace Dwarf;

abstract class Stream extends Object {
    
    const SEEK_SET = \SEEK_SET;
    const SEEK_CURRENT = \SEEK_CUR;
    const SEEK_END = \SEEK_END;
    
    const MODE_READ = 'r';
    const MODE_READ_WRITE = 'r+';
    const MODE_WRITE = 'w';
    const MODE_READ_WRITE_TRUNCATE = 'w+';
    const MODE_APPEND = 'a';
    const MODE_READ_APPEND = 'a+';
    const MODE_WRITE_NEW = 'x';
    const MODE_READ_WRITE_NEW = 'x+';
    const MODE_WRITE_CREATE = 'c';
    const MODE_READ_WRITE_CREATE = 'c';
    
    protected $seekable = false;
    protected $readable = false;
    protected $writable = false;
    
    public function __destruct() {
        
        $this->close();
    }
    
    abstract public function setPosition( $position );
    abstract public function getPosition();
    abstract public function getLength();
    abstract public function read( $length = 1 );
    abstract public function write( $data );
    
    public function seek( $offset, $origin = self::SEEK_CURRENT ) {
        
        $len = $this->getLength();
        switch( $origin ) {
            case static::SEEK_SET:
                
                return $this->setPosition( max( 0, min( $offset, $len ) ) );
            case static::SEEK_CURRENT:
                
                return $this->setPosition( max( 0, min( $this->getPosition() + $offset, $len ) ) );
            case static::SEEK_END:
                
                return $this->setPosition( max( 0, min( $len - $offset, $len ) ) );
        }
        
        return $this;
    }
    
    public function seekStart( $offset = 0 ) {
        
        return $this->seek( $offset, static::SEEK_SET );
    }
    
    public function seekEnd( $offset = 0 ) {
        
        return $this->seek( $offset, static::SEEK_END );
    }
    
    public function readAll( $rewind = false ) {
        
        $this->seekStart();
        $data = $this->read( $this->getLength() );
        
        if( $rewind )
            $this->seekStart();
        
        return $data;
    }
    
    public function isSeekable() {
        
        return $this->seekable;
    }
    
    public function isReadable() {
        
        return $this->readable;
    }
    
    public function isWritable() {
        
        return $this->writable;
    }
    
    public function close() {
        
        $this->seekable = false;
        $this->readable = false;
        $this->writable = false;
        
        return $this;
    }
}