<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Pzdgmailcom_Commercebug_Helper_Cacheclearer
{
    public function clearCache()
    {			
        $shim = $this->getShim()->cleanCache();     
    }
    public function getShim()
    {
        $shim = Pzdgmailcom_Commercebug_Model_Shim::getInstance();
        return $shim;
    }    
}