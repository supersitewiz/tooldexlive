<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_System_Config_Source_Massimporterpro
{
    protected $_options;

	 public function getOptionArray()
    {
        $massimporterproCollection = Mage::getModel('massimporterpro/massimporterpro')->getCollection();
		$massimporterpro = array();
		foreach($massimporterproCollection as $_massimporterpro){
		  $massimporterpro[$_massimporterpro->getId()] = $_massimporterpro->getImportFile();
		}
		return $massimporterpro;
    }

    public function getFormOptionArray()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('massimporterpro/massimporterpro_collection')->loadData()->toOptionArray(false);
        }

        $options = $this->_options;
        array_unshift($options, array('value' => '', 'label' => Mage::helper('massimporterpro')->__('--Please Select--')));

        return $options;
    }
}