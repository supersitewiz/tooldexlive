<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Block_Adminhtml_Priceupdater_Edit_Tab_Price extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);

		$fieldset = $form->addFieldset('pricesettings_form', array('legend'=>Mage::helper('massimporterpro')->__('Price Settings')));

		$fieldset->addField('tier_price_import_type', 'select', array(
			'label'     => Mage::helper('massimporterpro')->__('Tier Price Import Type'),
			'required'  => false,
			'name'      => 'tier_price_import_type',
			'values'    => Mage::getSingleton('massimporterpro/system_config_source_importtypes')->toOptionArray(),
			'note'		=> '<strong>Merge</strong>: Merge with the existing data, <br /><strong>Replace (Group)</strong>: Delete existing data by sku & a group and insert new, <br /><strong>Replace (All)</strong>: Delete existing data by sku & all groups and insert new',
		));

		$fieldset->addField('group_price_import_type', 'select', array(
			'label'     => Mage::helper('massimporterpro')->__('Group Price Import Type'),
			'required'  => false,
			'name'      => 'group_price_import_type',
			'values'    => Mage::getSingleton('massimporterpro/system_config_source_importtypes')->toOptionArray(),
			'note'		=> 'The \'Group Price\' feature is only available in Magento 1.7 or higher.',
		));

		$fieldset->addField('reindex_after_import', 'select', array(
			'label'     => Mage::helper('massimporterpro')->__('Re-Index Product Prices After Import'),
			'required'  => false,
			'name'      => 'reindex_after_import',
			'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
			'note'		=> 'Re-Indexing may take couple of minutes or more, depending upon the no of catalog products.',
		));

		$form->setFieldNameSuffix('general');

		if ( Mage::getSingleton('adminhtml/session')->getPriceupdaterData() ) {
			$form->setValues(Mage::getSingleton('adminhtml/session')->getPriceupdaterData());
			Mage::getSingleton('adminhtml/session')->setPriceupdaterData(null);
		} elseif ( Mage::registry('priceupdater_data') ) {
			$form->setValues(Mage::registry('priceupdater_data')->getData());
		}
		return parent::_prepareForm();
	}
}