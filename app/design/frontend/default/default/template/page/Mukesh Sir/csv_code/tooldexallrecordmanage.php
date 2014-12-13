<?php   

mysql_connect('localhost','root','');
mysql_select_db('mukesh_csv');
    

     
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
</head>

<body>
<table>
<tr>
<th>ID</th>
<th>image</th>
<th>sku</th>

<th>SKU</th>
<th>Description</th>
<th>brand</th>
<th>Item_Template</th>
<th>CrossSales</th>
<th>UpSales</th>
<th>shipping_time</th>
<th>Upc</th>




</tr>
<?php  
//$sql=mysql_query("SELECT * FROM table_name1 LEFT JOIN table_name2 ON table_name1.column_name=table_name2.column_name ");


//$sql=mysql_query("select * FROM contacts LEFT JOIN customers_detail ON contacts.CON_CustomerID=customers_detail.CU_CustomerID");

$sql=mysql_query("select * FROM sku limit 60000,5000");
$i=1;
while($row1=mysql_fetch_array($sql))
{ 
$sql1=mysql_query("select * FROM sku_with_brand where SKU='".$row1['sku']."' ");
$row=mysql_fetch_array($sql1);

?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $row1['image']; ?></td>
<td><?php echo $row1['sku']; ?></td>

<td><?php echo $row['SKU']; ?></td>
<td><?php echo $row['Description']; ?></td>
<td><?php echo $row['brand']; ?></td>
<td><?php echo $row['Item_Template']; ?></td>
<td><?php echo $row['CrossSales']; ?></td>
<td><?php echo $row['UpSales']; ?></td>
<td><?php echo $row['shipping_time']; ?></td>
<td><?php echo $row['Upc']; ?></td>



 	
 	
</tr>
<?php } ?>
</table>



</body>
</html>
