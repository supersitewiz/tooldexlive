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

if($_REQUEST['submit']=='Search')
{

 $pro_code=$_REQUEST['pro_code'];

$aa=mysql_query("SELECT * FROM `match` WHERE cat_code='$pro_code'");
$bb=mysql_fetch_array($aa);
$cc=$bb['cat_name'];

$aa1=mysql_query("select * from match3 where cat_name='$cc'");
$bb1=mysql_fetch_array($aa1);
$dd= $bb1['cat_code'];
$nn= $bb1['cat_name'];
}
?>
<form method="post">
<table width="50%" align="center" border="10" cellpadding="10" cellspacing="10">
<tr><th colspan="3">Find Category Id</th></tr>
<tr><td>Enter Product Code</td> <td><input type="text" name="pro_code" id="pro_code" required="required" /></td> <td><input type="submit"  name="submit" value="Search" /></td></tr>
<tr><td colspan="3" align="center">Exinting Product Code = <?php echo $pro_code; ?></td></tr>

<tr><td colspan="3" align="center">Get Category Name = <?php echo $nn; ?></td></tr>
<tr><td colspan="3" align="center">Magento Category Id = <?php echo $dd; ?></td></tr>


</table>

</form>

</body>
</html>
