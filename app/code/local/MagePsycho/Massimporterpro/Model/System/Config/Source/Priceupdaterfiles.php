<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_System_Config_Source_Priceupdaterfiles
{
	public function getOptionArray()
    {
		$helper = Mage::helper('massimporterpro');
		$files = $helper->getImportedFiles('price_updater');
		$options = array();
		foreach($files as $key => $_file){
			$options[] = array('value' => $_file, 'label' => '/' . $_file);
		}
		array_unshift($options, array('value' => '', 'label' => Mage::helper('massimporterpro')->__('')));
		return $options;
    }
}