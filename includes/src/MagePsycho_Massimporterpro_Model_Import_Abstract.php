<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class MagePsycho_Massimporterpro_Model_Import_Abstract extends Mage_Core_Model_Abstract{

	protected $_importType		= '';
	protected $_logPath			= '';
	protected $_totalCount		= 0;
	protected $_successCount    = 0;
	protected $_errorCount		= 0;
	protected $_skipCount		= 0;
	protected $_results			= array();

	public function addRowSuccess($data = array()){

	}

	public function addRowError($data = array()){

	}

	abstract public function importData($importData, $options = array());

	public function getSuccessCount(){
		return (int) $this->_successCount;
	}

	public function getErrorCount(){
		return (int) $this->_errorCount;
	}

	public function getSkipCount(){
		return (int) $this->_skipCount;
	}

	public function getTotalCount(){
		return (int) $this->_totalCount;
	}

	public function getResults(){
		return $this->_results;
	}

	public function getSucessMessage(){
		return $this->_successMessage;
	}

	public function getErrorMessage(){
		return $this->_errorMessage;
	}

	public function getNoticeMessage(){
		return $this->_noticeMessage;
	}

	public function log($data){
		$path = Mage::getBaseDir('var') . DS . 'log' . DS .'massimporterpro' . DS . $this->_importType;
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		$file = 'massimporterpro/' . $this->_importType . '/' . trim(date('Y-m-d H-i-s'), '/') . '.log';
		Mage::log($data, null, $file);
	}

	public function getConnection($type = 'core_read'){
		return Mage::getSingleton('core/resource')->getConnection($type);
	}

	public function getTableName($tableName){
		return Mage::getSingleton('core/resource')->getTableName($tableName);
	}

	public function getEntityTable($attributeId){
		$connection = $this->getConnection('core_read');
		$sql = "SELECT backend_type
					FROM " . $this->getTableName('eav_attribute') . "
				WHERE
					entity_type_id = ?
					AND attribute_id = ?";
		$entityTypeId = $this->getEntityTypeId();
		$backendType	= $connection->fetchOne($sql, array($entityTypeId, $attributeId));
		$tableName		= '';
		if(!empty($backendType)){
			$tableName		= 'catalog_product_entity';
			if($backendType != 'static'){
				$tableName .= '_' . $backendType;
			}
		}
		return $tableName;
	}

	public function getAttributeId($attributeCode = 'price'){
		$connection = $this->getConnection('core_read');
		$sql = "SELECT attribute_id
					FROM " . $this->getTableName('eav_attribute') . "
				WHERE
					entity_type_id = ?
					AND attribute_code = ?";
		$entityTypeId = $this->getEntityTypeId();
		return $connection->fetchOne($sql, array($entityTypeId, $attributeCode));
	}

	public function getEntityTypeId($entityTypeCode = 'catalog_product'){
		$connection = $this->getConnection('core_read');
		$sql		= "SELECT entity_type_id FROM " . $this->getTableName('eav_entity_type') . " WHERE entity_type_code = ?";
		return $connection->fetchOne($sql, array($entityTypeCode));
	}

	public function getIdFromSku($sku){
		$connection = $this->getConnection('core_read');
		$sql		= "SELECT entity_id FROM " . $this->getTableName('catalog_product_entity') . " WHERE sku = ?";
		return $connection->fetchOne($sql, array($sku));
	}

	public function getSkuFromId($entity_id){
		$connection = $this->getConnection('core_read');
		$sql		= "SELECT sku FROM " . $this->getTableName('catalog_product_entity') . " WHERE entity_id = ?";
		return $connection->fetchOne($sql, array($entity_id));
	}

	public function checkIfSkuExists($sku){
		$connection = $this->getConnection('core_read');
		$sql		= "SELECT COUNT(*) AS count_no FROM " . $this->getTableName('catalog_product_entity') . "	WHERE sku = ?";
		$count		= $connection->fetchOne($sql, array($sku));
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

	public function checkIfRowExists($field, $productId, $storeId){
		$attributeId	= $this->getAttributeId($field);
		$tableName		= $this->getEntityTable($attributeId);
		$connection		= $this->getConnection('core_read');
		$sql			= "SELECT COUNT(*) AS count_no FROM " . $this->getTableName($tableName) . "	WHERE entity_id = ? AND attribute_id = ? AND store_id = ?";
		$count			= $connection->fetchOne($sql, array($productId, $attributeId, $storeId));
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

	public function insertRow($field, $value, $productId, $storeId, $additonalData = array()){
		$connection				= $this->getConnection('core_write');
		$attributeId			= $this->getAttributeId($field);
		$tableName				= $this->getEntityTable($attributeId);

		$sql = "INSERT INTO " . $this->getTableName($tableName) . "
					SET
				entity_type_id = ?,
				attribute_id = ?,
				store_id = ?,
				entity_id = ?,
				value = ?
				";
		$connection->query($sql, array($this->getEntityTypeId(), $attributeId, $storeId, $productId, $value));
	}

	public function updateRow($field, $value, $productId, $storeId, $additonalData = array()){
		$connection				= $this->getConnection('core_write');
		$attributeId			= $this->getAttributeId($field);
		$tableName				= $this->getEntityTable($attributeId);
		$sql = "UPDATE " . $this->getTableName($tableName) . "
					SET  `value` = ?
				WHERE  attribute_id = ?
				AND entity_id = ?
				AND store_id = ?";
		$connection->query($sql, array($value, $attributeId, $productId, $storeId));
	}
}