<?php

namespace Dwarf;

class Path extends Object implements \IteratorAggregate, \Countable {
    
    protected $path;
    
    public function __construct( $path ) {
        
        $this->path = $path;
    }
    
    public function exists() {
        
        return $this->isDirectory() 
             ? true 
             : stream_resolve_include_path( $this->path );
    }
    
    public function createDirectory( $chmod = 0775 ) {
        
        mkdir( $this->path, $chmod, true );
    }
    
    public function createFile( $returnStream = true ) {
        
        $fs = $this->openWrite();
        
        if( $returnStream )
            return $fs;
        
        $fs->close();
        
        return $this;
    }
    
    public function isWritable() {
        
        return is_writable( $this->path );
    }
    
    public function isReadable() {
        
        return is_readable( $this->path );
    }
    
    public function isExecutable() {
        
        return is_executable( $this->path );
    }
    
    public function isFile() {
        
        return is_file( $this->path );
    }
    
    public function isDirectory() {
        
        return is_dir( $this->path );
    }
    
    public function isLink() {
        
        return is_link( $this->path );
    }
    
    public function getExtension() {
        
        return pathinfo( $this->path, PATHINFO_EXTENSION );
    }
    
    public function getFilename() {
        
        return pathinfo( $this->path, PATHINFO_FILENAME );
    }
    
    public function getDirectory( $asObject = true ) {
        
        return $asObject 
             ? new static( pathinfo( $this->path, PATHINFO_DIRNAME ) ) 
             : pathinfo( $this->path, PATHINFO_DIRNAME );
    }
    
    public function getBasename() {
        
        return pathinfo( $this->path, PATHINFO_BASENAME );
    }
    
    public function getAccessGroup() {
        
        return filegroup( $this->path );
    }
    
    public function getAccessOwner() {
        
        return fileowner( $this->path );
    }
    
    public function getSize() {
        
        return filesize( $this->path );
    }
    
    public function getModificationTime() {
        
        return new DateTime( filemtime( $this->path ) );
    }
    
    public function getCreationTime() {
        
        return new DateTime( filectime( $this->path ) );
    }
    
    public function getAccessTime() {
        
        return new DateTime( fileatime( $this->path ) );
    }
    
    public function getRealPath( $asObject = true ) {
        
        return $asObject 
             ? new static( realpath( $this->path ) ) 
             : realpath( $this->path );
    }
    
    public function __toString() {
        
        return $this->path;
    }
    
    public function getFiles( $asObject = true ) {
        
        if( !$this->isDirectory() )
            throw new Exception( "$this->path is not a directory and thus " 
                . "doesn't have files. Please check with Path->isDir() first" );
        
        $dir = opendir( $this->path );
        
        while( $file = readdir( $dir ) ) {
            
            if( $file !== '.' && $file !== '..' )
                yield static::combine( $this->path, $file, $asObject );
        }
        
        closedir( $dir );
    }
    
    public function getIterator() {
        
        return $this->getFiles();
    }
    
    public function count() {
        
        return iterator_count( $this->getIterator() );
    }
    
    public function openRead() {
        
        return Streams/FileStream::openRead( $this );
    }
    
    public function openWrite() {
        
        return Streams/FileStream::openWrite( $this );
    }
    
    public function openAppend() {
        
        return Streams/FileStream::openAppend( $this );
    }
    
    public static function combine( $folder, $path, $asObject = true ) {
        
        //both can be Path instances, so lets make them a string first
        $folder = (string)$folder;
        $path = (string)$path;
        
        //do the slashes sit right?
        if( $folder[ strlen( $folder ) - 1 ] !== DIRECTORY_SEPARATOR )
            $folder .= DIRECTORY_SEPARATOR;
        
        if( $path[ 0 ] === DIRECTORY_SEPARATOR )
            $path = substr( $path, 1 );
        
        return $asObject ? new static( $folder.$path ) : $folder.$path;
    }
}
