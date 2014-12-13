<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_System_Config_Source_Importtypes
{
	public function toOptionArray()
    {
		$helper		= Mage::helper('massimporterpro');
		$options	= array();
		$options[]	= array('value' => 'merge', 'label' => 'Merge');
		$options[]	= array('value' => 'replace_group', 'label' => 'Replace (Group)');
		$options[]	= array('value' => 'replace_all', 'label' => 'Replace (All)');
		return $options;
    }
}