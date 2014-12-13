<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Pzdgmailcom_Commercebug_Model_Crossareaajax_Toggletranslation extends Pzdgmailcom_Commercebug_Model_Crossareaajax
{
    public function handleRequest()
    {
        $session = $this->_getSessionObject();        
        $c = $session->getData(Pzdgmailcom_Commercebug_Model_Observer::INLINE_TRANSLATION_ON);
        $c = $c == 'on' ? 'off' : 'on';        
        $session->setData(Pzdgmailcom_Commercebug_Model_Observer::INLINE_TRANSLATION_ON, $c);        
        $this->endWithHtml('Inline Translation is ' . ucwords($c) .' -- Refresh Page to Access.');        
    }
}