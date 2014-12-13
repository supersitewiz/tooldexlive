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
<th>sku</th>
<th>sku</th>
<th>brand </th>

<th>discontinued</th>
<th>similar_product</th>
<th>discontinued_product_sku</th>
<th>shipping_time</th>
<th>upc</th>
 




</tr>
<?php  
//$sql=mysql_query("SELECT * FROM table_name1 LEFT JOIN table_name2 ON table_name1.column_name=table_name2.column_name ");


//$sql=mysql_query("select * FROM contacts LEFT JOIN customers_detail ON contacts.CON_CustomerID=customers_detail.CU_CustomerID");

$sql=mysql_query("select * FROM finel263");
$i=1;
while($row1=mysql_fetch_array($sql))
{ 
$sql1=mysql_query("select * FROM finel500 where sku='".$row1['sku']."' ");
$row=mysql_fetch_array($sql1);

?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $row1['sku']; ?></td>
 
<td><?php echo $row['sku']; ?></td>
<td><?php echo $row['brand']; ?></td>
<td><?php echo $row['discontinued']; ?></td>
<td><?php echo $row['similar_product']; ?></td>
<td><?php echo $row['discontinued_product_sku']; ?></td>
<td><?php echo $row['shipping_time']; ?></td>
<td><?php echo $row['upc']; ?></td>




 	
 	
</tr>
<?php } ?>
</table>



</body>
</html>
