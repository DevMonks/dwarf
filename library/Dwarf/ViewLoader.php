<?php

namespace Dwarf;

class ViewLoader extends Loader {
    
    public function enable() {
        
        $this->enabled = true;
    }
    
    public function disable() {
        
        $this->enabled = false;
    }
}