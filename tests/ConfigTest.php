<?php

require_once dirname( __DIR__ ).'/vendor/autoload.php';

class TestContainer extends Dwarf\Config {
    use Dwarf\Configurable;
    
    public function __construct( $config = null ) { $this->setConfig( $config ); }
}

class ConfigTest extends PHPUnit_Framework_TestCase {
    
    public function testInitialization() {
        
        $c = new TestContainer( [ 'test' => 'testValue' ] );
        
        $this->assertTrue( $c->getConfig() instanceof Dwarf\Config );
    }
    
    public function testEmptyInitialization() {
        
        $c = new TestContainer;
        
        $this->assertInstanceOf( 'Dwarf\Config', $c->getConfig() );
    }
    
    public function testValueFetching() {
        
        $c = new TestContainer( [ 'test' => 'testValue' ] );
        $c->setConfig( [ 'defaultKey' => 'internalDefaultValue' ], true );
        
        $this->assertEquals( 'testValue', $c->getConfig( 'test' ) );
        $this->assertEquals( 'testValue', $c->getConfig( 'test' ), 'evenIfDefaultIsSet' );
        $this->assertNull( $c->getConfig( 'notExistent' ) );
        $this->assertEquals( 'defaultValue', $c->getConfig( 'notExistent', 'defaultValue' ) );
        $this->assertEquals( 'internalDefaultValue', $c->getConfig( 'defaultKey' ) );
        $this->assertEquals( 'internalDefaultValue', $c->getConfig( 'defaultKey', 'iAmIgnored' ) );
    }
    
    public function testConfigChangeViaReference() {
        
        $c = new TestContainer( [ 'test' => 'testValue' ] );
        $c->setConfig( [ 'defaultKey' => 'internalDefaultValue' ], true );
        
        $cfg = $c->getConfig();
        
        $cfg->defaultKey = 'overwrittenValue';
        
        $this->assertSame( $cfg, $c->getConfig() );
        $this->assertEquals( 'overwrittenValue', $c->getConfig( 'defaultKey', 'evenWithForcedDefault' ) );
    }
}