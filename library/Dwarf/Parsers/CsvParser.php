<?php

namespace Dwarf\Parsers;

use Dwarf\Streams\MemoryStream;

class CsvParser extends \Dwarf\Parser {
    
    public function parseString( $string ) {
        
        $delimeter = $this->getConfig( 'delimter', ';' );
        $enclosure = $this->getConfig( 'enclosure', '"' );
        
        $ms = new MemoryStream( $string );
        
        //Quick note:
        //this will return a Generator, this means,
        //once you iterate this result, it will start to read
        //the lines from file. The results are piped, not buffered, cached
        //and re-wrapped
        return $ms->readCsvLines( $delimeter, $enclosure );
    }
}