<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Pzdgmailcom_Commercebug_Model_Collectorfiles extends Pzdgmailcom_Commercebug_Model_Observingcollector
{
    static protected $_phpFiles=array();
    static protected $_otherInfo=false;
    public function collectInformation($observer)
    {
        $collector = $this->getCollector();
        self::$_phpFiles  = $observer->information;
        self::$_otherInfo = $observer->otherInformation;
    }
    
    public function addToObjectForJsonRender($json)
    {
        $json->phpFiles = self::$_phpFiles;     
        $json->phpFilesInfo = self::$_otherInfo;
        return $json;
    }    
    
    public function createKeyName()
    {
        return 'php';
    }
}