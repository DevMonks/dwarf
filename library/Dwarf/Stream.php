<?php

namespace Dwarf;

use Dwarf\InvalidArgumentException;

class Stream extends Object {
    
    const SEEK_SET = \SEEK_SET;
    const SEEK_CURRENT = \SEEK_CUR;
    const SEEK_END = \SEEK_END;
    
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
    
    protected $metaData;
    protected $resource;
    protected $seekable = false;
    protected $readable = false;
    protected $writable = false;

    public function __construct( $path, $mode = self::MODE_READ ) {
        
        if( is_resource( $path ) )
            $this->resource = $path;
        else if( $path instanceof Path || is_string( $path ) )
            $this->resource = fopen( (string)$path, $mode, true );
        else
            throw new InvalidArgumentException( 'path', 'resource, string or Path instance' );
        
        $this->metaData = stream_get_meta_data( $this->resource );
        
        //correct the mode, specific for streams that work without modes
        $mode = $this->metaData[ 'mode' ];
        
        if( in_array( $mode, [ 
            self::MODE_READ, 
            self::MODE_READ_WRITE,
            self::MODE_READ_WRITE_TRUNCATE,
            self::MODE_READ_APPEND,
            self::MODE_READ_WRITE_NEW,
            self::MODE_READ_WRITE_CREATE
        ] ) )
                $this->readable = true;
        
        if( in_array( $mode, [ 
            self::MODE_WRITE, 
            self::MODE_READ_WRITE,
            self::MODE_READ_WRITE_TRUNCATE,
            self::MODE_APPEND,
            self::MODE_READ_APPEND,
            self::MODE_WRITE_NEW,
            self::MODE_READ_WRITE_NEW,
            self::MODE_WRITE_CREATE,
            self::MODE_READ_WRITE_CREATE
        ] ) )
                $this->writable = true;
        
        $this->seekable = $this->metaData[ 'seekable' ];
    }
    
    public function __destruct() {
        
        $this->close();
    }
    
    public function getResource() {
        
        return $this->resource;
    }
    
    public function read( $length = 1 ) {
        
        return fread( $this->resource, $length );
    }
    
    public function readLine() {
        
        return fgets( $this->resource );
    }
    
    public function readLines( $rewind = false ) {
        
        $this->seekStart();
        while( !feof( $this->resource ) ) {
            
            yield $this->readLine();
        }
        
        //re-wind
        if( $rewind )
            $this->seekStart();
    }
    
    public function readCsvLine( $delimeter = ';', $enclosure = '"' ) {
        
        return fgetcsv( $this->resource, 0, $delimeter, $enclosure );
    }
    
    public function readCsvLines( $delimeter = ';', $enclosure = '"', $rewind = false ) {
        
        $this->seekStart();
        while( !feof( $this->resource ) )
            yield $this->readCsvLine( $delimeter, $enclosure );
        
        if( $rewind )
            $this>seekStart();
    }
    
    public function readAll( $rewind = false ) {
        
        $this->seekStart();
        $data = $this->read( $this->getLength() );
        
        if( $rewind )
            $this->seekStart();
        
        return $data;
    }
    
    public function write( $data ) {
        
        fwrite( $this->resource, $data );
        
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
    
    public function writeCsvLine( array $data, $delimeter = ';', $enclosure = '"' ) {
        
        fputcsv( $this->resource, $data, $delimeter, $enclosure );
    }
    
    public function writeCsvLines( array $data, $delimeter = ';', $enclosure = '"' ) {
        
        foreach( $data as $row )
            $this->writeCsvLine( $row, $delimeter, $enclosure );
        
        return $this;
    }
    
    public function setPosition( $position ) {
        
        return $this->seek( $position, static::SEEK_SET );
    }
    
    public function getPosition() {
        
        return ftell( $this->resource );
    }
    
    public function seek( $offset, $origin = self::SEEK_CURRENT ) {
        
        fseek( $this->resource, $offset, $origin );
        
        return $this;
    }
    
    public function seekStart( $offset = 0 ) {
        
        return $this->seek( $offset, static::SEEK_SET );
    }
    
    public function seekEnd( $offset = 0 ) {
        
        return $this->seek( $offset, static::SEEK_END );
    }
    
    public function getType() {
        
        return $this->metaData[ 'wrapper_type' ];
    }
    
    public static function openRead( $path ) {
        
        return new static( $path, self::MODE_READ );
    }
    
    public static function openWrite( $path ) {
        
        return new static( $path, self::MODE_WRITE );
    }
    
    public static function openAppend( $path ) {
        
        return new static( $path, self::MODE_APPEND );
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
        
        fclose( $this->resource );
        
        $this->seekable = false;
        $this->readable = false;
        $this->writable = false;
        
        return $this;
    }
}