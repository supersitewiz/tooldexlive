<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Adminhtml_PriceupdaterController extends Mage_Adminhtml_Controller_Action
{
	protected function _title($text = null, $resetIfExists = true){
		$helper			= Mage::helper('massimporterpro');
		if($helper->checkVersion('1.3.2.4', '<=')){
			return $this;
		}else{
			return parent::_title($text, $resetIfExists);
		}
	}

	protected function _initAction() {
		$this->_title($this->__('Price Importer'))
             ->_title($this->__('Mass Importer Pro'));
		$this->loadLayout()
			 ->_setActiveMenu('massimporterpro/priceupdater')
			 ->_addBreadcrumb(Mage::helper('adminhtml')->__('Price Importer'), Mage::helper('adminhtml')->__('Price Importer'));

		return $this;
	}

	public function indexAction() {

		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('massimporterpro/massimporterpro')->load($id);

		//set default values
		$helper = Mage::helper('massimporterpro');
		$model->setData('delimiter', $helper->getConfig('delimiter', 'data_format'));
		$model->setData('enclosure', $helper->getConfig('enclosure', 'data_format'));
		$model->setData('tier_price_import_type', $helper->getConfig('tier_price_import_type', 'price_settings'));
		$model->setData('group_price_import_type', $helper->getConfig('group_price_import_type', 'price_settings'));
		$model->setData('reindex_after_import', $helper->getConfig('reindex_after_import', 'price_settings'));

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('priceupdater_data', $model);

			$this->_initAction();

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('massimporterpro/adminhtml_priceupdater_edit'))
				 ->_addLeft($this->getLayout()->createBlock('massimporterpro/adminhtml_priceupdater_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('massimporterpro')->__('Priceupdater does not exist.'));
			$this->_redirect('*/*/');
		}
	}

	public function viewLogAction(){
		if($logId = $this->getRequest()->getParam('id', 0)){
			$helper = Mage::helper('massimporterpro');
			$model		= Mage::getModel('massimporterpro/massimporterpro')->load($logId);
			$logData	= unserialize($model->getLogData());
			$helper->displayFormattedArray($logData);
		}else{
			echo 'Log Id is incorrect!';
		}
	}

	public function uploadCsvAction(){
		$helper				= Mage::helper('massimporterpro');
		$isValid			= $helper->isValid();
		$isActive			= $helper->isActive();
		if (!$isActive || ($isActive && !$isValid)) {
			Mage::getSingleton('adminhtml/session')->addNotice("Could complete your operation.");
			$this->_redirect('*/*/');
			return;
		}
		if ($data = $this->getRequest()->getPost()) {
			$helper = Mage::helper('massimporterpro');
			/********************************* CSV UPLOAD OPERATION *********************************/
			if(isset($_FILES['general']['name']['import_file_upload']) && $_FILES['general']['name']['import_file_upload'] != '') {
				try {
					$fileName		= $_FILES['general']['name']['import_file_upload'];
					$fileExt		= strtolower(substr(strrchr($fileName, ".") ,1));
					$fileNamewoe	= rtrim($fileName, $fileExt);
					$fileName		= $helper->slugify($fileNamewoe) . '.' . $fileExt;

					$uploader		= new Varien_File_Uploader('general[import_file_upload]');
	           		$uploader->setAllowedExtensions(array('csv'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('var') . DS . 'massimporterpro' . DS . 'price_updater';
					if(!is_dir($path)){
						mkdir($path, 0777, true);
					}
					$uploader->save($path . DS, $fileName );
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('massimporterpro')->__('CSV File: ' . $fileName .' has been successfully uploaded.'));
					$this->_redirect('*/*/');
					return;
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('*/*/');
					return;
		        }
			}
			/********************************* CSV UPLOAD OPERATION *********************************/
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('massimporterpro')->__('Unable to find CSV file for uploading.'));
        $this->_redirect('*/*');
	}

	public function checkAction(){

	}

	public function saveAction() {
		$helper				= Mage::helper('massimporterpro');
		$isValid			= $helper->isValid();
		$isActive			= $helper->isActive();
		if (!$isActive || ($isActive && !$isValid)) {
			Mage::getSingleton('adminhtml/session')->addNotice("Could complete your operation.");
			$this->_redirect('*/*/');
			return;
		}
		if ($data = $this->getRequest()->getPost('general')) {

			/************************\ START IMPORT \************************/
			$helper = Mage::helper('massimporterpro');
			set_time_limit(0);
			ini_set('memory_limit','1024M');

			$reindexAfterImport = isset($data['reindex_after_import']) ? $data['reindex_after_import'] : 0;
			$delimiter		= ',';
			$enclosure		= '"';
			$importFile		= $data['import_file'];
			$importFileType	= 'csv';
			$importFilePath = Mage::getBaseDir('var') . DS . 'massimporterpro' . DS . 'price_updater' . DS . $importFile;
			$fileOptions	= array(
				'source'	=> $importFilePath,
				'delimiter' => $delimiter,
				'enclosure' => $enclosure,
			);
			$importOptions = array(
				'tier_price_import_type'	=> $data['tier_price_import_type'],
				'group_price_import_type'	=> $data['group_price_import_type'],
			);

			$importData		= MagePsycho_Massimporterpro_Model_Import_Adapter::factory($importFileType, $fileOptions);

			$priceUpdater	= Mage::getModel('massimporterpro/priceupdater');
			$importStartAt	= $helper->getMicroTime();
			$priceUpdater->importData($importData, $importOptions);
			$importStopAt	= $helper->getMicroTime();
            $resultData		= $priceUpdater->getResults();
			$priceUpdater->log($resultData, 'price_updater');
			/************************\ END IMPORT \************************/

			$model = Mage::getModel('massimporterpro/massimporterpro');
			$totalCountRows				= $priceUpdater->getTotalCount();
			$successCountRows			= $priceUpdater->getSuccessCount();
			$errorCountRows				= $priceUpdater->getErrorCount();
			$skipCountRows				= $priceUpdater->getSkipCount();
			$data['created_at']			= now();
			$data['updated_at']			= now();
			$data['import_type']		= 'price_updater';
			$data['import_file_type']	= $importFileType;
			$data['import_file']		= '/' . $importFile;
			$data['log_data']			= serialize($resultData);
			$data['total_rows']			= $totalCountRows;
			$data['success_rows']		= $successCountRows;
			$data['error_rows']			= $errorCountRows;
			$data['skipped_rows']		= $skipCountRows;
			$data['import_duration']	= (float) number_format($importStopAt - $importStartAt, 6);

			$model->setData($data)
				  ->setId($this->getRequest()->getParam('id'));
			try {
				$model->save();

				$reindexMessage = '';
				if($reindexAfterImport){
					try{
						Mage::getSingleton('index/indexer')->getProcessByCode('catalog_product_price')->reindexAll();
						$reindexMessage = '<br />...<br />Product Prices index was rebuilt successfully.';
					}catch(Exception $e){
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('massimporterpro')->__('There was an error while rebuilding Product prices index:<br />' . $e->getMessage()));
					}

				}

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('massimporterpro')->__('Total %d no of rows were processed.<br /> Successfully updated rows#: %d <br />Skipped rows#: %d <br />Error occurred rows#: %d' . $reindexMessage . '<br />...<br />(Click \'Import History\' tab for more details.)', $totalCountRows, $successCountRows, $skipCountRows, $errorCountRows));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('massimporterpro')->__('Unable to find Importer to save.'));
        $this->_redirect('*/*/');
	}

	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('massimporterpro/priceupdater');

				$model->setId($this->getRequest()->getParam('id'))
					  ->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The Log has been deleted.'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function historyAction(){
		$this->getLayout();
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('massimporterpro/adminhtml_priceupdater_edit_tab_history')->toHtml()
		);
	}

    public function massDeleteAction() {
        $priceupdaterIds = $this->getRequest()->getParam('priceupdater');
        if(!is_array($priceupdaterIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($priceupdaterIds as $priceupdaterId) {
                    $priceupdater = Mage::getModel('massimporterpro/massimporterpro')->load($priceupdaterId);
                    $priceupdater->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were deleted.', count($priceupdaterIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'priceupdater.csv';
        $content    = $this->getLayout()->createBlock('massimporterpro/adminhtml_priceupdater_edit_tab_history')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

	public function exportXmlAction()
    {
        $fileName   = 'priceupdater.xml';
        $content    = $this->getLayout()->createBlock('massimporterpro/adminhtml_priceupdater_edit_tab_history')->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

	public function exportExcelAction()
    {
        $fileName   = 'priceupdater_excel.xml';
        $content    = $this->getLayout()->createBlock('massimporterpro/adminhtml_priceupdater_edit_tab_history')->getExcel($fileName);
        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

	protected function _isAllowed() {
		return Mage::getSingleton('admin/session')->isAllowed('massimporterpro/priceupdater');
	}
}