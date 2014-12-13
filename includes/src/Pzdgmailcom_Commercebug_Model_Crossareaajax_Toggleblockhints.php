<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Pzdgmailcom_Commercebug_Model_Crossareaajax_Toggleblockhints extends Pzdgmailcom_Commercebug_Model_Crossareaajax
{
    public function handleRequest()
    {        
        $session = $this->_getSessionObject();        
        $c = $session->getData(Pzdgmailcom_Commercebug_Model_Observer::BLOCK_HINTS_ON);
        $c = $c == 'on' ? 'off' : 'on';        
        $session->setData(Pzdgmailcom_Commercebug_Model_Observer::BLOCK_HINTS_ON, $c);        
        $this->endWithHtml('Block Hints ' . ucwords($c) .' -- Refresh to see Changes.');        
    }
}