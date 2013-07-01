<?php

require_once dirname( dirname( __DIR__ ) ).'/vendor/autoload.php';

define( 'TEST_FOLDER', __DIR__.'/test-folder' );

class StreamTest extends PHPUnit_Framework_TestCase {
    
    public function testTestFolderWritable() {
        
        $this->assertTrue( is_writable( TEST_FOLDER ) );
    }
    
    public function testFileOpening() {
        
        $s = new Dwarf\Stream( TEST_FOLDER.'/FileWithContent.log' );
        
        $this->assertInstanceOf( 'Dwarf\\Stream', $s );
    }
    
    public function testFileCreation() {
        
        $path = TEST_FOLDER.'/CreatedFile.txt';
        
        if( file_exists( $path ) )
            unlink( $path );
        
        $s = new Dwarf\Stream( $path, Dwarf\Stream::MODE_WRITE );
        
        $this->assertFileExists( $path );
    }
    
    public function testLineIteration() {
        
        $s = new Dwarf\Stream( TEST_FOLDER.'/FileWithContent.log' );
        
        $iterations = 0;
        foreach( $s->readLines() as $line )
            $iterations++;
        
        $this->assertEquals( 9, $iterations );
    }
    
    public function testNativeLineIteration() {
        
        $s = new Dwarf\Stream( TEST_FOLDER.'/FileWithContent.log' );
        
        $iterations = 0;
        $res = $s->toResource();
        
        while( !feof( $res ) ) {
            fgets( $res );
            $iterations++;
        }
        
        $this->assertEquals( 9, $iterations );
    }
}