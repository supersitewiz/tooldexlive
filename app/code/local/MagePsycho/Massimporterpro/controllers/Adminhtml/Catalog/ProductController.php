<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Controller_Action
{
	protected function _getCsv($priceType, $productIds = array())
	{
		$helper		= Mage::helper('massimporterpro');
		$csv		= '';
		$_columns	= array();
		$_columns[] = "sku";

		if( in_array($priceType, array(0, 1, 2, 3)) ){
			$_columns[] = "store_id";
		}
		if(in_array($priceType, array(0, 1, 3))){
			$_columns[] = "cost";
			$_columns[] = "price";
			$_columns[] = "msrp";
		}
		if(in_array($priceType, array(0, 2, 3))){
			$_columns[] = "special_price";
			$_columns[] = "special_from_date";
			$_columns[] = "special_to_date";
		}
		if(in_array($priceType, array(0, 4, 5, 6))){
			$_columns[] = "website_id";
		}

		$customerGroups = $helper->getCustomerGroups();
		if(in_array($priceType, array(0, 4, 6))){
			$_columns[] = "tier_price:_all_";
			foreach($customerGroups as $customerGroup){
				$_columns[] = "tier_price:" . $customerGroup['label'];
			}
		}

		if($helper->checkVersion('1.7')){
			if(in_array($priceType, array(0, 5, 6))){
				foreach($customerGroups as $customerGroup){
					$_columns[] = "group_price:" . $customerGroup['label'];
				}
			}
		}

        $data = array();
        foreach ($_columns as $column) {
            $data[] = '"'.$column.'"';
        }
        $csv .= implode(',', $data)."\n";

		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection->addAttributeToFilter('entity_id', array('in' => $productIds));

        foreach ($collection as $item) {
			$_product = Mage::getModel('catalog/product')->load($item->getId());

			$data = array();
			$data[] = '"'.$_product->getSku().'"'; #sku
			if(in_array($priceType, array(0, 1, 2, 3))){
				$data[] = '"0"'; #store_id
			}
			if(in_array($priceType, array(0, 1, 3))){
				$data[] = '"'.sprintf("%.2f", $_product->getCost()).'"';
				$data[] = '"'.sprintf("%.2f", $_product->getPrice()).'"';
				$data[] = '"'.sprintf("%.2f", $_product->getMsrp()).'"';
			}
			if(in_array($priceType, array(0, 2, 3))){
				$data[] = '"'.sprintf("%.2f", $_product->getSpecialPrice()).'"';
				$data[] = '"'.$_product->getSpecialFromDate().'"';
				$data[] = '"'.$_product->getSpecialToDate().'"';
			}
			if(in_array($priceType, array(0, 4, 5, 6))){
				$data[] = '"0"'; #website_id
			}

			#TIER PRICES
			if( in_array($priceType, array(0, 4, 6)) ){
				$tierPrices	 = $_product->getData('tier_price');
				$tierPriceAll = array();
				if(!empty($tierPrices)){
					foreach($tierPrices as $_tierPriceAll){
						if($_tierPriceAll['all_groups'] == 1 && $_tierPriceAll['website_id'] == 0){ //@FIXME
							$tierPriceAll[] = intval($_tierPriceAll['price_qty']) . ':' . sprintf("%.2f", $_tierPriceAll['price']); //@FIXME $qty = floatval($qty); if getIsQtyDecimal
						}
					}
				}
				$data[] = '"' . implode(';', $tierPriceAll) . '"'; #tier_price:_all_

				foreach($customerGroups as $customerGroup){

					$tierPrice	 = array();
					if(!empty($tierPrices)){
						foreach($tierPrices as $_tierPrice){
							if($_tierPrice['cust_group'] == $customerGroup['value'] && $_tierPrice['website_id'] == 0){ //@FIXME
								$tierPrice[] = intval($_tierPrice['price_qty']) . ':' . sprintf("%.2f", $_tierPrice['price']);
							}
						}
					}
					$data[] = '"' . implode(';', $tierPrice) . '"';
				}
			}

			#GROUP PRICES
			if($helper->checkVersion('1.7')){
				if(in_array($priceType, array(0, 5, 6))){
					$groupPrices = $_product->getData('group_price');
					foreach($customerGroups as $customerGroup){

						$groupPrice = '';
						if(!empty($groupPrices)){
							foreach($groupPrices as $_groupPrice){
								if($_groupPrice['cust_group'] == $customerGroup['value'] && $_groupPrice['website_id'] == 0){ //@FIXME
									$groupPrice = sprintf("%.2f", $_groupPrice['price']);
								}
							}
						}
						$data[] = '"'.$groupPrice.'"';
					}
				}
			}

			$csv.= implode(',', $data)."\n";

        }
        return $csv;
	}

	public function massExportPricesAction()
    {
		$helper			= Mage::helper('massimporterpro');
		$productIds		= (array)$this->getRequest()->getParam('product');
		$priceType		= $this->getRequest()->getParam('price_type');
		switch ($priceType) {
			case 0:
				$filePrefix = 'all-';
				break;
			case 1:
				$filePrefix = 'regular-';
				break;
			case 2:
				$filePrefix = 'special-';
				break;
			case 3:
				$filePrefix = 'regular-special-';
				break;
			case 4:
				$filePrefix = 'tier-';
				break;
			case 5:
				$filePrefix = 'group-';
				break;
			case 6:
				$filePrefix = 'tier-group-';
				break;

			default:
				$filePrefix = 'all-';
				break;
		}

		$fileName		= $filePrefix . 'prices-' . date('Y-m-d') . '.csv';
		$content		= $this->_getCsv($priceType, $productIds);
		$this->_prepareDownloadResponse($fileName, $content);
    }
}