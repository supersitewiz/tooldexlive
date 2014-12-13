<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Block_Adminhtml_Priceupdater_Edit_Tab_Data extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);

		$fieldset = $form->addFieldset('dataformat_form', array('legend'=>Mage::helper('massimporterpro')->__('Data Format')));

		$fieldset->addField('dataformat_label', 'text', array(
            'name'         => 'dataformat_label',
            'label'        => Mage::helper('massimporterpro')->__("Currently Price Importer supports only the CSV file type, using a comma (,) as delimiter and double quotes(\") as enclosure."),
        ));
        $form->getElement('dataformat_label')->setRenderer(Mage::app()->getLayout()->createBlock(
            'massimporterpro/adminhtml_priceupdater_edit_renderer_label'
        ));

		$fieldset->addField('import_file_type', 'select', array(
			'label'     => Mage::helper('massimporterpro')->__('File Type'),
			'required'  => false,
			'name'      => 'import_file_type',
			'disabled'	=> true,
			'values'    => Mage::getSingleton('massimporterpro/system_config_source_filetypes')->getOptionArray(),
		));

		$fieldset->addField('delimiter', 'text', array(
			'label'     => Mage::helper('massimporterpro')->__('Value Delimiter'),
			'required'  => true,
			'style'		=> 'width:3em',
			'name'      => 'delimiter',
			'value'		=> ',',
			'disabled'	=> true,
			'after_element_html' => '',
		));

		$fieldset->addField('enclosure', 'text', array(
			'label'     => Mage::helper('massimporterpro')->__('Enclose Values In'),
			'required'  => true,
			'style'		=> 'width:3em',
			'name'      => 'enclosure',
			'value'		=> '"',
			'disabled'	=> true,
			'after_element_html' => '',
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