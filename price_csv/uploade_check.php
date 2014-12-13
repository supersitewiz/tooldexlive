<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
</head>

<body>
<form method="post" action="price.php" >
<input type="hidden" name="msg_full_path"   value="<?php echo $_REQUEST['msg_full_path'] ?>" />
<table width="700" align="center" border="10" cellpadding="10" cellspacing="10">
<tr><td colspan="2" align="center"><h1>File Successfully Upload </h1></td></tr>
<tr>
<td>Last Upload File Name :- <?php echo substr($_REQUEST['msg_full_path'],7,1000); ?> </td>
</tr>
 
<tr>
<td  align="center"> <input type="submit" name="submit"  value=" Start Uploading " /></td>
</tr>

</table>
</form>

</body>
</html>
