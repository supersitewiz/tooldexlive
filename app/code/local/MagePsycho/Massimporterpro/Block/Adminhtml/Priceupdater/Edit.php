<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Block_Adminhtml_Priceupdater_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId	= 'id';
        $this->_blockGroup	= 'massimporterpro';
        $this->_controller	= 'adminhtml_priceupdater';

        $this->removeButton('back');
		$helper				= Mage::helper('massimporterpro');
		$isValid			= $helper->isValid();
		$isActive			= $helper->isActive();

		$message			= '';
		if(!$isActive){
			$message = base64_decode('RXh0ZW5zaW9uIGhhcyBiZWVuIGRpc2FibGVkLiBQbGVhc2UgZW5hYmxlIGl0IGZyb20gTWFzcyBJbXBvcnRlciBQcm8gJnJhcXVvOyBNYW5hZ2UgU2V0dGluZ3M=');
		}else if ($isActive && !$isValid) {
			$message = base64_decode('UGxlYXNlIGVudGVyIGEgdmFsaWQgbGljZW5zZSBrZXkgaW4gb3JkZXIgdG8gcnVuIHRoZSBleHRlbnNpb24=');
		}
		if (!$isActive || ($isActive && !$isValid)) {
			$this->removeButton('save');
			$this->_addButton('dsave', array(
			'label'		=> Mage::helper('massimporterpro')->__('Run Import'),
			'onclick'	=> 'alert(\'' . $message . '.\')',
			'class'		=> 'disabled',
			), -100);
		}else{
			$this->_updateButton('save', 'label', Mage::helper('massimporterpro')->__('Run Import'));
		}


		$this->_formScripts[] = "
            function addUploadFile(){
				var importFile = $('import_file_upload').value;
				if(importFile == ''){
					alert('Please select some import file.');
					return;
				}

				var ext = importFile.substring(importFile.lastIndexOf('.') + 1);
				if(ext.toLowerCase() != 'csv'){
					alert('Please select valid import file (CSV).');
					return;
				}

				//tweak for required feilds: import file section, delimiter, enclosed
				$('import_file').removeClassName('required-entry');
				$('delimiter').removeClassName('required-entry');
				$('enclosure').removeClassName('required-entry');
                editForm.submit('".Mage::helper('adminhtml')->getUrl('massimporterpro/adminhtml_priceupdater/uploadCsv')."');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('priceupdater_data') && Mage::registry('priceupdater_data')->getId() ) {
            return Mage::helper('massimporterpro')->__('Price Importer');
        } else {
            return Mage::helper('massimporterpro')->__('Price Importer');
        }
    }
}