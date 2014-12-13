<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Pzdgmailcom_Commercebug_Model_Crossareaajax_Togglecblogging extends Pzdgmailcom_Commercebug_Model_Crossareaajax
{
    public function handleRequest()
    {
        $session = $this->_getSessionObject(); 
        $c = $session->getData(Pzdgmailcom_Commercebug_Model_Observer::CB_LOGGING_ON);
        $c = $c == 'on' ? 'off' : 'on';        
        $session->setData(Pzdgmailcom_Commercebug_Model_Observer::CB_LOGGING_ON,$c);
        $this->endWithHtml('Commerce Bug Logging ' . ucwords($c) .'');        
    }
}