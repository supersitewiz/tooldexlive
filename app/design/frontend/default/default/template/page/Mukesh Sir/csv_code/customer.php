<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
</head>

<body>
<?php   
mysql_connect('localhost','root','');
mysql_select_db('mukesh_csv');
?>
<table width="850" >
<?php 
$sql=mysql_query("select * from customer_record");
$i=1;
while($row=mysql_fetch_array($sql))
{
?>
<tr>
<td width="50"><?php echo $i++; ?></td>
<td width="200"><?php echo $row['username']; ?></td>
<td width="200"><?php echo md5( $row['password']); ?></td>
<td width="200"><?php echo $row['pass']; ?></td>
<td width="200"><?php echo $row['email']; ?></td>
</tr>
<?php } ?>
</table>


</body>
</html>
 