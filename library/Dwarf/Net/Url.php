<?php

namespace Dwarf\Net;

use Dwarf\Path;

class Url extends \Dwarf\Object {
    
    const SCHEME_HTTP = 'http';
    const SCHEME_HTTPS = 'https';
    const SCHEME_FTP = 'ftp';
    
    protected $scheme;
    protected $endPoint;
    protected $credentials;
    protected $path;
    protected $query;
    protected $fragment;
    
    public function __construct( $url = null ) {
        
        $this->endPoint = new EndPoint;
        $this->credentials = new Credentials;
        $this->query = new Query;
        $this->path = new Path;
        
        if( !empty( $url ) )
            $this->set( $url );
    }

    public function set( $url ) {
        
        $data = parse_url( $url );
        
        if( !empty( $data[ 'scheme' ] ) )
            $this->setScheme( $data[ 'scheme' ] );
        
        if( !empty( $data[ 'host' ] ) )
            $this->setHost( $data[ 'host' ] );
        
        if( !empty( $data[ 'port' ] ) )
            $this->setPort( $data[ 'port' ] );
        
        if( !empty( $data[ 'user' ] ) )
            $this->setUser( $data[ 'user' ] );
        
        if( !empty( $data[ 'pass' ] ) )
            $this->setPassword( $data[ 'pass' ] );
        
        if( !empty( $data[ 'path' ] ) )
            $this->setPath( $data[ 'path' ] );
        
        if( !empty( $data[ 'query' ] ) )
            $this->setQuery( $data[ 'query' ] );
        
        if( !empty( $data[ 'fragment' ] ) )
            $this->setFragment( $data[ 'fragment' ] );
    }
    
    public function getScheme() {
        
        return $this->scheme;
    }
    
    public function setScheme( $scheme ) {
        
        $this->scheme = $scheme;
    }
    
    public function getEndPoint() {
        
        return $this->endPoint;
    }
    
    public function setEndPoint( $endPoint ) {
        
        $endPoint = EndPoint::box( $endPoint );
        
        $this->endPoint = $endPoint;
    }
    
    public function getHost() {
        
        return $this->endPoint->getHost();
    }
    
    public function setHost( $host ) {
        
        $this->endPoint->setHost( $host );
    }
    
    public function getPort() {
        
        return $this->endPoint->getPort();
    }
    
    public function setPort( $port ) {
        
        $this->endPoint->setPort( $port );
    }
    
    public function getCredentials() {
        
        return $this->credentials;
    }
    
    public function setCredentials( $cred ) {
        
        $cred = Credentials::box( $cred );
        
        $this->credentials = $cred;
    }
    
    public function getUser() {
        
        return $this->credentials->getUser();
    }
    
    public function setUser( $user ) {
        
        $this->credentials->setUser( $user );
    }
    
    public function getPassword() {
        
        return $this->credentials->getPassword();
    }
    
    public function setPassword( $pass ) {
        
        $this->credentials->setPassword( $pass );
    }
    
    public function getPath( $asString = false ) {
        
        return $asString ? (string)$this->path : $this->path;
    }
    
    public function setPath( $path ) {
        
        $path = Path::box( $path );
        
        $this->path = $path;
    }
    
    public function getQuery( $asString = false ) {
        
        return $asString ? (string)$this->query : $this->query;
    }
    
    public function setQuery( $query ) {
        
        $query = Query::box( $query );
        
        $this->query = $query;
    }
    
    public function getQueryVar( $var ) {
        
        return $this->query->$var;
    }
    
    public function setQueryVar( $var, $value ) {
        
        $this->query->$var = $value;
    }
    
    public function getFragment() {
        
        return $this->fragment;
    }
    
    public function setFragment( $fragment ) {
        
        $this->fragment = $fragment;
    }
    
    public function __toString() {
        
        $url = "$this->scheme://";
        $cred = (string)$this->credentials;
        $ep = (string)$this->endPoint;
        $path = (string)$this->path;
        $qry = (string)$this->query;
        
        if( !empty( $cred ) )
            $url .= "$cred@";
        
        if( !empty( $ep ) )
            $url .= "$ep/";
        
        if( !empty( $path ) )
            $url .= $path;
        
        if( !empty( $qry ) )
            $url .= "?$qry";
        
        if( !empty( $this->fragment ) )
            $url .= "#$this->fragment";
        
        return $url;
    }
}