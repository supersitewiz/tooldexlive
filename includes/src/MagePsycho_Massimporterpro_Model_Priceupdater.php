<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_Priceupdater extends MagePsycho_Massimporterpro_Model_Import_Abstract
{
	const ALL_GROUP_ID				 = -2;
	const NON_LOGGED_IN_GROUP_ID	 = 0;
	const NO_GROUP_ID				 = -1;

	protected $_importType = 'price_updater';

	public function __construct(){
		parent::__construct();
	}

	public function getCustomerGroupId($groupName){
		$connection = $this->getConnection('core_read');
		$sql		= "SELECT customer_group_id FROM " . $this->getTableName('customer_group') . " WHERE customer_group_code = ?";
		$customerId = $connection->fetchOne($sql, array($groupName));
		if(strlen($customerId)){
			return $customerId;
		}else{
			return MagePsycho_Massimporterpro_Model_Priceupdater::NO_GROUP_ID;
		}
	}

	public function deleteAllTierPrices($productId, $websiteId = 0){
		$connection			= $this->getConnection('core_write');
		$sql = "DELETE FROM " . $this->getTableName('catalog_product_entity_tier_price') . " WHERE entity_id = ? AND website_id = ?";
		$connection->query($sql, array($productId, $websiteId));
	}

	public function deleteTierPrices($productId, $customerGroupId, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql = "DELETE FROM " . $this->getTableName('catalog_product_entity_tier_price') . " WHERE entity_id = ? AND all_groups = ? AND customer_group_id = ? AND website_id = ?";
		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}
		$connection->query($sql, array($productId, $allGroups, $customerGroupId, $websiteId));
	}

	public function deleteTierPrice($productId, $customerGroupId, $qty, $websiteId = 0){
		$connection = $this->getConnection('core_write');

		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}

		$sql = "DELETE FROM " . $this->getTableName('catalog_product_entity_tier_price') . " WHERE entity_id = ? AND all_groups = ? AND customer_group_id = ? AND qty = ? AND website_id = ?";
		$connection->query($sql, array($productId, $allGroups, $customerGroupId, $qty, $websiteId));
	}

	public function deleteAllGroupPrices($productId, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql = "DELETE FROM " . $this->getTableName('catalog_product_entity_group_price') . " WHERE entity_id = ? AND website_id = ?";
		$connection->query($sql, array($productId, $websiteId));
	}

	public function deleteGroupPrices($productId, $customerGroupId, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql = "DELETE FROM " . $this->getTableName('catalog_product_entity_group_price') . " WHERE entity_id = ? AND customer_group_id = ? AND website_id = ?";
		$connection->query($sql, array($productId, $customerGroupId, $websiteId));
	}

	public function deleteGroupPrice($productId, $customerGroupId, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql = "DELETE FROM " . $this->getTableName('catalog_product_entity_group_price') . " WHERE entity_id = ? AND customer_group_id = ? AND website_id = ?";
		$connection->query($sql, array($productId, $customerGroupId, $websiteId));
	}

	public function checkIfTierPriceExists($productId, $customerGroupId, $qty, $websiteId = 0){
		$connection = $this->getConnection('core_read');
		$sql		= "SELECT COUNT(*) AS count_no FROM " . $this->getTableName('catalog_product_entity_tier_price') . " WHERE
			entity_id = ? AND
			all_groups = ? AND
			customer_group_id = ? AND
			qty = ? AND
			website_id = ?";

		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}

		$count			= $connection->fetchOne($sql, array($productId, $allGroups, $customerGroupId, $qty, $websiteId));
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

	public function updateTierPrices($productId, $customerGroupId, $qty, $value, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql		= "UPDATE " . $this->getTableName('catalog_product_entity_tier_price') . " SET `value` = ? WHERE
			entity_id = ? AND
			all_groups = ? AND
			customer_group_id = ? AND
			qty = ? AND
			website_id = ?";

		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}
		$connection->query($sql, array($value, $productId, $allGroups, $customerGroupId, $qty, $websiteId));
	}

	public function insertTierPrices($productId, $customerGroupId, $qty, $value, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql		= "INSERT INTO " . $this->getTableName('catalog_product_entity_tier_price') . " SET
			entity_id = ?,
			all_groups = ?,
			customer_group_id = ?,
			qty = ?,
			`value` = ?,
			website_id = ? ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)";

		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}

		$connection->query($sql, array($productId, $allGroups, $customerGroupId, $qty, $value, $websiteId));
	}

	public function checkIfGroupPriceExists($productId, $customerGroupId, $websiteId = 0){
		$connection = $this->getConnection('core_read');
		$sql		= "SELECT COUNT(*) AS count_no FROM " . $this->getTableName('catalog_product_entity_group_price') . " WHERE
			entity_id = ? AND
			all_groups = ? AND
			customer_group_id = ? AND
			website_id = ?";

		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}

		$count			= $connection->fetchOne($sql, array($productId, $allGroups, $customerGroupId, $websiteId));

		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

	public function updateGroupPrices($productId, $customerGroupId, $value, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql		= "UPDATE " . $this->getTableName('catalog_product_entity_group_price') . " SET `value` = ? WHERE
			entity_id = ? AND
			all_groups = ? AND
			customer_group_id = ? AND
			website_id = ?";

		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}

		$connection->query($sql, array($value, $productId, $allGroups, $customerGroupId, $websiteId));
	}

	public function insertGroupPrices($productId, $customerGroupId, $value, $websiteId = 0){
		$connection = $this->getConnection('core_write');
		$sql		= "INSERT INTO " . $this->getTableName('catalog_product_entity_group_price') . " SET
			entity_id = ?,
			all_groups = ?,
			customer_group_id = ?,
			`value` = ?,
			website_id = ? ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)";

		$allGroups  = 0;
		if($customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID){
			$allGroups		 = 1;
			$customerGroupId = 0;
		}

		$connection->query($sql, array($productId, $allGroups, $customerGroupId, $value, $websiteId));
	}

	protected function _updatePrices($count, $sku, $storeId, $data, $options = array()){
		$helper					= Mage::helper('massimporterpro');
		$connection				= $this->getConnection('core_write');

		$productId				= $this->getIdFromSku($sku);

		$websiteId				= isset($data['website_id']) ? trim($data['website_id']) : 0;

		$cost					= isset($data['cost']) ? trim($data['cost']) : '';
		$price  				= isset($data['price']) ? trim($data['price']) : '';
		$msrp					= isset($data['msrp']) ? trim($data['msrp']) : '';
		$specialPrice			= isset($data['special_price']) ? trim($data['special_price']) : '';
		$fromDate				= isset($data['special_from_date']) ? trim($data['special_from_date']) : '';
		$toDate					= isset($data['special_to_date']) ? trim($data['special_to_date']) : '';

		$this->_results[$count]['DATA'] = array(
			'sku'				=> $sku,
			'store_id'			=> $storeId,
			'cost'				=> $cost,
			'price'				=> $price,
			'msrp'				=> $msrp,
			'special_price'		=> $specialPrice,
			'special_from_date' => $fromDate,
			'special_to_date'	=> $toDate,
		);

		//cost
		if(strlen($cost)){
			if(Zend_Validate::is($cost, 'NotEmpty')){
				if($this->checkIfRowExists('cost', $productId, $storeId)){
					$this->updateRow('cost', $cost, $productId, $storeId);
				}else{
					$this->insertRow('cost', $cost, $productId, $storeId);
				}
			}else{
				$this->_results[$count]['DATA']['cost'] = $cost . '(INVALID)';
			}
		}

		//regular price
		if(strlen($price)){
			if(Zend_Validate::is($price, 'NotEmpty')){
				if($this->checkIfRowExists('price', $productId, $storeId)){
					$this->updateRow('price', $price, $productId, $storeId);
				}else{
					$this->insertRow('price', $price, $productId, $storeId);
				}
			}else{
				$this->_results[$count]['DATA']['price'] = $price . '(INVALID)';
			}
		}

		//msrp price
		if(strlen($msrp)){
			if(Zend_Validate::is($msrp, 'NotEmpty')){
				if($this->checkIfRowExists('msrp', $productId, $storeId)){
					$this->updateRow('msrp', $msrp, $productId, $storeId);
				}else{
					$this->insertRow('msrp', $msrp, $productId, $storeId);
				}
			}else{
				$this->_results[$count]['DATA']['msrp'] = $msrp . '(INVALID)';
			}
		}


		//special price
		if(strlen($specialPrice)){
			if(Zend_Validate::is($specialPrice, 'NotEmpty')){
				if($this->checkIfRowExists('special_price', $productId, $storeId)){
					$this->updateRow('special_price', $specialPrice, $productId, $storeId);
				}else{
					$this->insertRow('special_price', $specialPrice, $productId, $storeId);
				}
			}else{
				$this->_results[$count]['DATA']['special_price'] = $specialPrice . '(INVALID)';
			}
		}

		//special from date
		if(strlen($fromDate)){
			if(Zend_Validate::is($fromDate, 'Date')){
				if($this->checkIfRowExists('special_from_date', $productId, $storeId)){
					$this->updateRow('special_from_date', $fromDate, $productId, $storeId);
				}else{
					$this->insertRow('special_from_date', $fromDate, $productId, $storeId);
				}
			}else{
				$this->_results[$count]['DATA']['special_from_date'] = $fromDate . '(INVALID)';
			}
		}
		//special to date
		if(strlen($toDate)){
			if(Zend_Validate::is($toDate, 'Date')){
				if($this->checkIfRowExists('special_to_date', $productId, $storeId)){
					$this->updateRow('special_to_date', $toDate, $productId, $storeId);
				}else{
					$this->insertRow('special_to_date', $toDate, $productId, $storeId);
				}
			}else{
				$this->_results[$count]['DATA']['special_to_date'] = $toDate . '(INVALID)';
			}
		}

		//tier price
		$fields = array_keys($data);
		$tierPriceImportType = isset($options['tier_price_import_type']) ? $options['tier_price_import_type'] : 'merge';

		if($tierPriceImportType == 'replace_all'){
			//delete all tier prices by product id
			$this->deleteAllTierPrices($productId, $websiteId);
		}

		foreach($fields as $_field){
			if(preg_match("/tier_price:(.*)/", $_field, $matches)){
				$customerGroup = isset($matches[1]) ? trim($matches[1]) : '';
				if($customerGroup != '_all_'){
					$customerGroupId = $this->getCustomerGroupId($customerGroup);
				}else{
					$customerGroupId = MagePsycho_Massimporterpro_Model_Priceupdater::ALL_GROUP_ID;
				}

				$tierPrices = $data[$_field];
				$tierPrices = preg_replace('/\s+/', '', $tierPrices); //filtering - removing spaces
				if(Zend_Validate::is($tierPrices, 'NotEmpty') && $customerGroupId != MagePsycho_Massimporterpro_Model_Priceupdater::NO_GROUP_ID){

					if($tierPriceImportType == 'replace_group'){
						//delete tier prices by sku & group id
						$this->deleteTierPrices($productId, $customerGroupId, $websiteId);
					}

					$_tierPrices = explode(';', trim($tierPrices, ';'));
					$tcount = 1;
					foreach($_tierPrices as $_tierPrice){
						$insertUpdate		= '';
						$_qtyPrices			= explode(':', $_tierPrice);
						$tierQty			= isset($_qtyPrices[0]) ? $_qtyPrices[0] : '';
						$tierPrice			= isset($_qtyPrices[1]) ? $_qtyPrices[1] : '';

						if(!Zend_Validate::is($tierQty, 'NotEmpty') || !Zend_Validate::is($tierPrice, 'NotEmpty')){ //skip if tierQty is empty && $tierPrice
							continue;
						}

						//delete group price if it is marked as x
						if(in_array($tierPrice, array('x', 'X'))){
							$insertUpdate = 'DELETE';
							$this->deleteTierPrice($productId, $customerGroupId, $tierQty, $websiteId);
						}else{
							if($tierPriceImportType == 'merge'){
								//check if tier price with that qty exists
								if($this->checkIfTierPriceExists($productId, $customerGroupId, $tierQty, $websiteId)){
									$insertUpdate = 'UPDATE';
									$this->updateTierPrices($productId, $customerGroupId, $tierQty, $tierPrice, $websiteId);
								}else{
									$insertUpdate = 'INSERT';
									$this->insertTierPrices($productId, $customerGroupId, $tierQty, $tierPrice, $websiteId);
								}
							}else{
								$insertUpdate = 'INSERT';
								$this->insertTierPrices($productId, $customerGroupId, $tierQty, $tierPrice, $websiteId);
							}
						}

						$this->_results[$count]['DATA'][$_field][$tcount] = array(
							'website_id'	=> $websiteId,
							'tierQty'		=> $tierQty,
							'tierPrice'		=> $tierPrice,
							'operation'		=> $insertUpdate,
					    );
						$tcount++;
					}
				}
				if(!Zend_Validate::is($tierPrices, 'NotEmpty') && $customerGroupId == MagePsycho_Massimporterpro_Model_Priceupdater::NO_GROUP_ID){
					$this->_results[$count]['DATA'][$_field][1] = array(
						'operation' => 'SKIPPED',
					);
				}
			}
		}

		//group price
		//Note: Group Price feature is only available for Magento >= 1.7
		$fields				 = array_keys($data);
		$groupPriceImportType = isset($options['group_price_import_type']) ? $options['group_price_import_type'] : 'merge';
		if($groupPriceImportType == 'replace_all'){
			//delete all group prices by sku
			$this->deleteAllGroupPrices($productId, $websiteId);
		}

		if($helper->checkVersion('1.7')){
			foreach($fields as $_field){
				if(preg_match("/group_price:(.*)/", $_field, $matches)){
					$customerGroup = isset($matches[1]) ? trim($matches[1]) : '';
					$customerGroupId = $this->getCustomerGroupId($customerGroup);//get customer group id from table

					$groupPrices = $data[$_field];
					$groupPrices = preg_replace('/\s+/', '', $groupPrices); //filtering - removing spaces
					if(Zend_Validate::is($groupPrices, 'NotEmpty') && $customerGroupId != -1){

						if($groupPriceImportType == 'replace_group'){
							//delete group prices related to that sku & group id
							$this->deleteGroupPrices($productId, $customerGroupId, $websiteId);
						}

						$groupPrice		 = $groupPrices;
						$tcount			 = 1;
						$insertUpdate	 = '';

						//delete group price if it is marked as x
						if(in_array($groupPrice, array('x', 'X'))){
							$insertUpdate = 'DELETE';
							$this->deleteGroupPrice($productId, $customerGroupId, $groupPrice, $websiteId);
						}else{
							if($groupPriceImportType == 'merge'){
								//check if group price with that group already exists
								if($this->checkIfGroupPriceExists($productId, $customerGroupId, $websiteId)){
									$insertUpdate = 'UPDATE';
									$this->updateGroupPrices($productId, $customerGroupId, $groupPrice, $websiteId);
								}else{
									$insertUpdate = 'INSERT';
									$this->insertGroupPrices($productId, $customerGroupId, $groupPrice, $websiteId);
								}
							}else{
								$insertUpdate = 'INSERT';
								$this->insertGroupPrices($productId, $customerGroupId, $groupPrice, $websiteId);
							}
						}

						$this->_results[$count]['DATA'][$_field][$tcount] = array(
							'website_id'	=> $websiteId,
							'groupPrice'	=> $groupPrice,
							'operation'		=> $insertUpdate,
						);
					}
					if(!Zend_Validate::is($groupPrices, 'NotEmpty') && $customerGroupId == -1){
						$this->_results[$count]['DATA'][$_field][1] = array(
							'customerGroupId' => $customerGroupId,
							'operation' => 'SKIPPED',
						);
					}
				}
			}
		}
	}

	public function importData($importData, $options = array()){
		//validate csv headers
		//check if sku exists or not, check if
		$count = 1;
		$this->_totalCount		= 0;
		$this->_successCount	= 0;
		$this->_errorCount		= 0;
		$this->_skipCount		= 0;
		foreach ($importData as $data){
			#"sku","store_id","cost","price","msrp","special_price","special_from_date","special_to_date","tier_price:_all_","tier_price:Wholesale","group_price:Retailer"
			$sku									= isset($data['sku']) ? trim($data['sku']) : '';
			$storeId								= isset($data['store_id']) ? trim($data['store_id']) : 0;

			if($this->checkIfSkuExists($sku)){
				try{
					$this->_updatePrices($count, $sku, $storeId, $data, $options);
					$this->_successCount++;
					$this->_results[$count]['RESULT']  = 'SUCCESS';
					$this->_results[$count]['MESSAGE'] = 'Prices for SKU (' . $sku . ') were successfully updated.';
				}catch(Exception $e){
					$this->_errorCount++;
					$this->_results[$count]['RESULT'] = 'ERROR';
					$this->_results[$count]['MESSAGE'] = $e->getMessage();
				}
			}else{
				$this->_skipCount++;
				$this->_results[$count]['RESULT'] = 'SKIPPED';
				$this->_results[$count]['MESSAGE'] = 'Product with SKU (' . $sku . ') doesn\'t exist.';
			}
			$this->_totalCount++;
			$count++;
		}
	}
}