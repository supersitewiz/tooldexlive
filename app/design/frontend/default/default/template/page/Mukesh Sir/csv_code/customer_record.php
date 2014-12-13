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
<th>CU_Email</th>
<th>CU_UserName</th>
<th>CU_Password</th>
<th>CU_Company</th>

<th>CON_FirstName</th>
<th>CON_LastName</th>
<th>CON_Address1</th>
<th>CoON_Address2</th>
<th>CON_Zip</th>
<th>CON_Phone1</th>
<th>CON_City</th>




</tr>
<?php  
//$sql=mysql_query("SELECT * FROM table_name1 LEFT JOIN table_name2 ON table_name1.column_name=table_name2.column_name ");


//$sql=mysql_query("select * FROM contacts LEFT JOIN customers_detail ON contacts.CON_CustomerID=customers_detail.CU_CustomerID");

$sql=mysql_query("select * FROM customers_detail");
$i=1;
while($row1=mysql_fetch_array($sql))
{ 
$sql1=mysql_query("select * FROM contacts where CON_CustomerID='".$row1['CU_CustomerID']."' ");
$row=mysql_fetch_array($sql1);

?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $row1['CU_Email']; ?></td>
<td><?php echo $row1['CU_UserName']; ?></td>
<td><?php echo md5( $row1['CU_Password']); ?></td>
<td><?php echo $row1['CU_Company']; ?></td>

<td><?php echo $row['CON_FirstName']; ?></td>
<td><?php echo $row['CON_LastName']; ?></td>
<td><?php echo $row['CON_Address1']; ?></td>
<td><?php echo $row['CoON_Address2']; ?></td>
<td><?php echo $row['CON_Zip']; ?></td>
<td><?php echo $row['CON_Phone1']; ?></td>
<td><?php echo $row['CON_City']; ?></td>



 	
 	
</tr>
<?php } ?>
</table>



</body>
</html>
