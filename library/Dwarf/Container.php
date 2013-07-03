<?php

namespace Dwarf;

class Container extends Object implements \IteratorAggregate, \ArrayAccess {
    
    protected $data = [];
    
    public function __construct( $data = null ) {
        
        if( !is_null( $data ) )
            $this->merge( $data );
    }
    
    public function getArray() {
        
        return $this->data;
    }
    
    public function setArray( array $data ) {
        
        $this->data = $data;
    }
    
    public function getIterator() {
        
        return new \ArrayIterator( $this->data );
    }
    
    public function __get( $key ) {
        
        return $this->data[ $key ];
    }
    
    public function __set( $key, $value ) {
        
        $this->data[ $key ] = $value;
    }
    
    public function __isset( $key ) {
        
        return isset( $this->data[ $key ] );
    }
    
    public function __unset( $key ) {
        
        unset( $this->data[ $key ] );
    }
    
    public function offsetExists( $key ) {
        
        return isset( $this->data[ $key ] );
    }
    
    public function offsetGet( $key ) {
        
        return $this->data[ $key ];
    }
    
    public function offsetSet( $key, $value ) {
        
        $this->data[ $key ] = $value;
    }
    
    public function offsetUnset( $key ) {
        
        unset( $this->data[ $key ] );
    }
    
    public function merge( $data ) {
        
        if( !is_array( $data ) && !( $data instanceof \Traversable ) )
            throw new InvalidArgumentException( 'data', 'array or instance of Traversable' );
        
        foreach( $data as $key => $val ) {
            
            if( is_array( $val ) || $val instanceof \Traversable ) {
                
                if( !isset( $this[ $key ] ) )
                    $this[ $key ] = new static;
                else if( !( $this[ $key ] instanceof self ) ) {
                    
                    if( !is_array( $this[ $key ] ) && !( $this[ $key ] instanceof \Traversable ) )
                        $this[ $key ] = [ '__remainder' => $this[ $key ] ];
                    
                    $this[ $key ] = new static( $this[ $key ] );
                }
                    
                
                $this[ $key ]->merge( $val );
            } else
                $this[ $key ] = $val;
        }
        
        return $this;
    }
}