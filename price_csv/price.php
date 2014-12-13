<?php
/**
 * @author      MagePsycho <info@magepsycho.com>
 * @website     http://www.magepsycho.com
 * @category    Export / Import
 */
$msg_full_path=$_REQUEST['msg_full_path'];
if($msg_full_path=='')
{
$msg="Select Product CSv File";
print("<script>window.location='http://www.tooldex.com/price_csv/index.php?msg=$msg'</script>");
}
$mageFilename = '../app/Mage.php';
require_once $mageFilename;
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app('admin');
Mage::register('isSecureArea', 1);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 
set_time_limit(0);
ini_set('memory_limit','1024M');
 
/***************** UTILITY FUNCTIONS ********************/
function _getConnection($type = 'core_read'){
    return Mage::getSingleton('core/resource')->getConnection($type);
}
 
function _getTableName($tableName){
    return Mage::getSingleton('core/resource')->getTableName($tableName);
}
 
function _getAttributeId($attribute_code = 'price'){
    $connection = _getConnection('core_read');
    $sql = "SELECT attribute_id
                FROM " . _getTableName('eav_attribute') . "
            WHERE
                entity_type_id = ?
                AND attribute_code = ?";
    $entity_type_id = _getEntityTypeId();
    return $connection->fetchOne($sql, array($entity_type_id, $attribute_code));
}
function _getAttributeId1($attribute_code = 'special_price'){
    $connection = _getConnection('core_read');
    $sql = "SELECT attribute_id
                FROM " . _getTableName('eav_attribute') . "
            WHERE
                entity_type_id = ?
                AND attribute_code = ?";
    $entity_type_id1 = _getEntityTypeId();
    return $connection->fetchOne($sql, array($entity_type_id1, $attribute_code));
}
 
function _getEntityTypeId($entity_type_code = 'catalog_product'){
    $connection = _getConnection('core_read');
    $sql        = "SELECT entity_type_id FROM " . _getTableName('eav_entity_type') . " WHERE entity_type_code = ?";
    return $connection->fetchOne($sql, array($entity_type_code));
}
 
function _getIdFromSku($sku){
    $connection = _getConnection('core_read');
    $sql        = "SELECT entity_id FROM " . _getTableName('catalog_product_entity') . " WHERE sku = ?";
    return $connection->fetchOne($sql, array($sku));
 
}

 
function _checkIfSkuExists($sku){
    $connection = _getConnection('core_read');
    $sql        = "SELECT COUNT(*) AS count_no FROM " . _getTableName('catalog_product_entity') . " WHERE sku = ?";
    $count      = $connection->fetchOne($sql, array($sku));
    if($count > 0){
        return true;
    }else{
        return false;
    }
}
 
function _updatePrices($data){
    $connection     = _getConnection('core_write');
    $sku            = $data[0];
    $newPrice       = $data[1];
 $specialPrice   = $data[2];
    $productId      = _getIdFromSku($sku);
    $attributeId    = _getAttributeId();
 $attributeId1    = _getAttributeId1();
 
    $sql = "UPDATE " . _getTableName('catalog_product_entity_decimal') . " cped
                SET  cped.value = ?
            WHERE  cped.attribute_id = ?
            AND cped.entity_id = ?";
$sql1 = "UPDATE " . _getTableName('catalog_product_index_price') . " cpip
                SET  cpip.final_price = ?
            WHERE cpip.entity_id = ?";
 
 
    $connection->query($sql, array($newPrice, $attributeId, $productId));
 $connection->query($sql, array($specialPrice, $attributeId1, $productId));
 $connection->query($sql1, array($specialPrice, $productId));

}

/***************** UTILITY FUNCTIONS ********************/
 
$csv                = new Varien_File_Csv();
/*$data               = $csv->getData('var/import/export_all_products.csv'); //path to csv */
$data               = $csv->getData($msg_full_path); 

array_shift($data);
 
$message = '';
$count   = '1';
$count_error   = '1';
 
foreach($data as $_data){
    if(_checkIfSkuExists($_data[0])){
        try{
            _updatePrices($_data);
            $message .= '<tr><td style="color:#00CC00; display:none;">'.$count . ' = Success:: While Updating Price (' . $_data[1] . ') of Sku (' . $_data[0] . '). </td></tr>';

_updatePrices($_data);

            $message .= '<tr><td style="color:#00CC00; display:none;">'.$count . ' = Success:: While Updating Special Price (' . $_data[2] . ') of Sku (' . $_data[0] . '). </td></tr>';
 
        }catch(Exception $e){
            $message .=  '<tr><td style="color:#FF0000;">'.$count_error++ .' . Error:: While Upating  Price (' . $_data[1] . ') of Sku (' . $_data[0] . ') => '.$e->getMessage().'</td></tr>';

$message .=  '<tr><td style="color:#FF0000;">'. $count_error++ .' . Error:: While Upating  Price (' . $_data[2] . ') of Sku (' . $_data[0] . ') => '.$e->getMessage().'</td></tr>';

        }
    }else{

        $message .= '<tr><td style="color:#FF0000;">'. $count_error++ .' . Error:: Product with Sku (' . $_data[0] . ') does\'t exist. </td></tr>';

    }
     $total_row=$count;
	 $count_error1=$count_error;
    $count++;
	 
}
 

 
?>
<table width="1000" cellpadding="1" cellspacing="1" align="center" border="1">
<tr><td align="center"><h1>Product Price Update According to SKU</h1></td></tr>
<?php echo $message; ?>
<tr><td><strong>Total Record Update = <?php echo $total_row; ?></strong></td></tr>
</table>


<table width="1000" cellpadding="1" cellspacing="1" align="center" border="1" style="margin-top:100px;">
<tr><td>Note : Please do not Close/Refresh window  after updating data click home link = <a href="http://www.tooldex.com/price_csv/index.html">Go to Home</a> </td></tr>
</table>
