<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_System_Config_Source_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('massimporterpro')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('massimporterpro')->__('Disabled')
        );
    }

	public function getFormOptionArray(){
		return array(
			 array(
				  'value'     => self::STATUS_ENABLED,
				  'label'     => Mage::helper('massimporterpro')->__('Enabled'),
			  ),

			  array(
				  'value'     => self::STATUS_DISABLED,
				  'label'     => Mage::helper('massimporterpro')->__('Disabled'),
			  ),
		);
	}
}