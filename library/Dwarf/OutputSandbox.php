<?php

namespace Dwarf;

class OutputSandbox extends Container {
    
    public function render( $path ) {
        
        ob_start();
        include $path;
        
        return ob_get_clean();
    }
}