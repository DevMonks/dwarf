<?php

namespace Dwarf\Parsers;

use Dwarf\UnsupportedFeatureException;

class YamlParser extends \Dwarf\Parser {
    
    public function parseString( $string ) {
        
        //maybe...just MAYBE...this one will be in-built one day..
        if( function_exists( 'yaml_parse' ) ) {

            return yaml_parse( $string );
        }
        
        //TODO: Implement a nice, simple and feature-less YAML parsing logic
        //Until that's done, we won't fall back to third party stuff.
        //It would throw the structure over.
        //You can still define an own parser based on another YAML parser
        //e.g. Zend's or Symfony's (Spyc may be the best)
        throw new UnsupportedFeatureException( 
            "YAML can only be used by installing the YAML extension for PHP (yet)"
        );
    }
}