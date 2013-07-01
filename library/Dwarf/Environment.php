<?php

namespace Dwarf;

abstract class Environment extends Object {
    use Configurable;
    
    const LOG_INFO = 0;
    const LOG_WARNING = 1;
    const LOG_ERROR = 2;
    const LOG_DEBUG = 3;
    
    protected $previousErrorHandler;
    protected $previousExceptionHandler;
    protected $previousIncludePaths;
    protected $includePaths;
    protected $isBound = false;

    abstract public function log( $message, $severity = self::LOG_INFO );
    abstract public function handleError( $code, $message, $file, $line );
    abstract public function handleException( \Exception $e );
    
    public function bind() {
        
        if( $this->isBound )
            return $this;
        
        error_reporting( E_ALL | E_STRICT );
        $this->previousErrorHandler = set_error_handler( array( $this, 'handleError' ) );
        $this->previousExceptionHandler = set_exception_handler( array( $this, 'handleException' ) );
        $this->previousIncludePaths = explode( PATH_SEPARATOR, get_include_path() );
        $this->includePaths = $this->previousIncludePaths;
        
        //rearrange include paths and re-assign keys to keep order
        krsort( $this->previousIncludePaths );
        $this->previousIncludePaths = array_values( $this->previousIncludePaths );
        
        $this->setIncludePath();
        $this->isBound = true;
        
        return $this;
    }
    
    public function unbind() {
        
        if( !$this->isBound )
            return $this;
        
        if( is_callable( $this->previousErrorHandler ) )
            set_error_handler( $this->previousErrorHandler );
        else
            restore_error_handler();
        
        if( is_callable( $this->previousExceptionHandler ) )
            set_exception_handler( $this->previousExceptionHandler );
        else
            restore_exception_handler();
        
        if( is_array( $this->previousIncludePaths ) )
            set_include_path( implode( PATH_SEPARATOR, $this->previousIncludePaths ) );
        else
            restore_include_path();
        
        $this->isBound = false;
        
        return $this;
    }
    
    public function addIncludePath( $path, $priority = null ) {
        
        if( !$this->isBound )
            throw new EnvironmentException( "Please bind the environment before setting include paths" );
        
        if( is_null( $priority ) )
            $this->includePaths[] = $path;
        else
            $this->includePaths[ $priority ] = $path;
        
        $this->setIncludePath();
        
        return $this;
    }
    
    public function removeIncludePath( $path ) {
        
        $k = null;
        if( ( $k = array_search( $this->includePaths, $path ) ) !== false ) {
            
            unset( $this->includePaths[ $k ] );
        }
        
        $this->setIncludePath();
        
        return $this;
    }
    
    protected function setIncludePath() {
        
        //sort our paths
        krsort( $this->includePaths );
        
        set_include_path( implode( PATH_SEPARATOR, $this->includePaths ) );
        
        return $this;
    }
    
    public static function load( $env, Config $config = null ) {
        
        $instance = null;
        switch( $env ) {
            case 'development':
                
                $instance = new Environments\DevelopmentEnvironment;
                break;
            case 'production':
                
                $instance = new Environments\ProductionEnvironment;
                break;
            default:
                
                if( class_exists( $env ) && is_subclass_of( $env, __NAMESPACE__.'\\'.__CLASS__ ) )
                    $instance = new $env;
        }
        
        if( !$instance )
            throw new EnvironmentException( "$env is not a valid environment" );
        
        if( $config )
            $instance->setConfig( $config );
        
        return $instance;
    }
}