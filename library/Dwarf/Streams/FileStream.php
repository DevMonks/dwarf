<?php

namespace Dwarf\Streams;

use \Dwarf\Path;

class FileStream extends \Dwarf\Stream {
    
    /* Copy from PHP Documentation:
     * 
     * 'r'	 Open for reading only; place the file pointer at the beginning of the file.
     * 'r+'	 Open for reading and writing; place the file pointer at the beginning of the file.
     * 'w'	 Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
     * 'w+'	 Open for reading and writing; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
     * 'a'	 Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
     * 'a+'	 Open for reading and writing; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
     * 'x'	 Create and open for writing only; place the file pointer at the beginning of the file. If the file already exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING. If the file does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call.
     * 'x+'	 Create and open for reading and writing; otherwise it has the same behavior as 'x'.
     * 'c'	 Open the file for writing only. If the file does not exist, it is created. If it exists, it is neither truncated (as opposed to 'w'), nor the call to this function fails (as is the case with 'x'). The file pointer is positioned on the beginning of the file. This may be useful if it's desired to get an advisory lock (see flock()) before attempting to modify the file, as using 'w' could truncate the file before the lock was obtained (if truncation is desired, ftruncate() can be used after the lock is requested).
     * 'c+'	 Open the file for reading and writing; otherwise it has the same behavior as 'c'.
     * 
     */

    protected $path;
    protected $handle;
    
    public function __construct( $path, $mode = self::MODE_READ ) {
        
        $this->path = !( $path instanceof Path ) ? new Path( $path ) : $path;
        $this->handle = fopen( (string)$this->path, $mode, true );
        
        if( in_array( $mode, [ 
            static::MODE_READ, 
            static::MODE_READ_WRITE,
            static::MODE_READ_WRITE_TRUNCATE,
            static::MODE_READ_APPEND,
            static::MODE_READ_WRITE_NEW,
            static::MODE_READ_WRITE_CREATE
        ] ) )
                $this->readable = true;
        
        if( in_array( $mode, [ 
            static::MODE_WRITE, 
            static::MODE_READ_WRITE,
            static::MODE_READ_WRITE_TRUNCATE,
            static::MODE_APPEND,
            static::MODE_READ_APPEND,
            static::MODE_WRITE_NEW,
            static::MODE_READ_WRITE_NEW,
            static::MODE_WRITE_CREATE,
            static::MODE_READ_WRITE_CREATE
        ] ) )
                $this->writable = true;
        
        $this->seekable = true;
    }
    
    public function getLength() {
        
        return $this->path->getSize();
    }
    
    public function read( $length = 1 ) {
        
        return fread( $this->handle, $length );
    }
    
    public function readLine() {
        
        return fgets( $this->handle );
    }
    
    public function readLines( $rewind = false ) {
        
        $this->seekStart();
        while( !feof( $this->handle ) ) {
            
            yield fgets( $this->handle );
        }
        
        //re-wind
        if( $rewind )
            $this->seekStart();
    }
    
    public function write( $data ) {
        
        fwrite( $this->handle, $data );
        
        return $this;
    }
    
    public function writeLine( $line ) {
        
        $this->write( "$line\n" );
        
        return $this;
    }
    
    //TODO: Typehint to Iterator/array (at the same time), 
    //but doesn't work as of PHP5.5
    public function writeLines( $lines ) {
        
        foreach( $lines as $line )
            if( $line[ strlen( $line ) - 1 ] === "\n" )
                $this->write( $line );
            else
                $this->writeLine( $line );
        
        return $this;
    }
    
    public function setPosition( $position ) {
        
        return $this->seek( $position, static::SEEK_SET );
    }
    
    public function getPosition() {
        
        return ftell( $this->handle );
    }
    
    public function seek( $offset, $origin = self::SEEK_CURRENT ) {
        
        fseek( $this->handle, $offset, $origin );
        
        return $this;
    }
    
    public static function openRead( $path ) {
        
        return new FileStream( $path, static::MODE_READ );
    }
    
    public static function openWrite( $path ) {
        
        return new FileStream( $path, static::MODE_WRITE );
    }
    
    public static function openAppend( $path ) {
        
        return new FileStream( $path, static::MODE_APPEND );
    }
    
    public function close() {
        
        fclose( $this->handle );
        
        return parent::close();
    }
}