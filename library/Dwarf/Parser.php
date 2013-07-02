<?php

namespace Dwarf;

abstract class Parser extends ConfigurableObject {
    
    public function __construct( $config = null ) {
        
        $this->setConfig( $config );
    }
    
    abstract public function parseString( $string );
    public function parseFile( $path ) {
        
        return $this->parseString( file_get_contents( (string)$path ) );
    }
    
    public static function load( $path, $parser = null ) {
        
        $path = Path::box( $path );
        
        if( !$parser ) {
            //Auto-assume parser
            switch( $path->getExtension() ) {
                case 'yml':
                case 'yaml':
                    
                    $parser = new Parsers\YamlParser;
                    break;
                case 'xml':
                    
                    $parser = new Parsers\XmlParser;
                    break;
                case 'ini':
                    
                    $parser = new Parsers\IniParser;
                    break;
                case 'csv':
                    
                    $parser = new Parsers\CsvParser;
                    break;
                case 'php':
                    
                    return include( (string)$path );
                    break;
            }
        } else if( is_string( $parser ) && is_subclass_of( $parser, __NAMESPACE__.'\\'.__CLASS__ ) )
            $parser = new $parser;
        
        if( !( $parser instanceof self ) )
            throw new InvalidArgumentException( 'parser', 'Unsupported file extension or invalid parser specified' );
        
        return $parser->parseFile( $path );
    }
    
    public static function loadFile( $path ) {
        
        $p = new static;
        return $p->parseFile( $path );
    }
    
    public static function loadString( $string ) {
        
        $p = new static;
        return $p->parseString( $string );
    }
}