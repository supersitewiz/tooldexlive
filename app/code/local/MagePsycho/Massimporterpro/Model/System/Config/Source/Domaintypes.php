<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_System_Config_Source_Domaintypes
{
    public function toOptionArray()
    {
        return array(
            '1'    => Mage::helper('massimporterpro')->__('Production'),
            '2'    => Mage::helper('massimporterpro')->__('Development'),
        );
    }
}