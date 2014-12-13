<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_System_Config_Source_Filetypes
{
	public function getOptionArray()
    {
		$helper = Mage::helper('massimporterpro');
		$options = array();
		$options[] = array('value' => 'csv', 'label' => 'CSV');
		return $options;
    }
}