<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Pzdgmailcom_Commercebug_Model_Graphviz
{
    public function capture()
    {    
        $collector  = new Pzdgmailcom_Commercebug_Model_Collectorgraphviz; 
        $o = new stdClass();
        $o->dot = Pzdgmailcom_Commercebug_Model_Observer_Dot::renderGraph();
        $collector->collectInformation($o);
    }
    
    public function getShim()
    {
        $shim = Pzdgmailcom_Commercebug_Model_Shim::getInstance();        
        return $shim;
    }    
}