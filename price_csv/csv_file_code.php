<?php 
/*if($_FILES['uploadedfile']['type']!='text/csv')
{

    $msg= "Your file format wrong use only CSV file !";
	print("<script>window.location='http://www.tooldex.com/price_csv/index.php?msg=$msg'</script>");
exit;
}*/

$file_name=$_FILES['uploadedfile']['name'];
$target_path = "import/";
$identifier=$_REQUEST['identifier'].'_';
$full_path=$target_path.$identifier.$file_name;

$target_path = $target_path.$identifier. basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
 {
  echo 'Successfully uploaded...';
   print("<script>window.location='http://www.tooldex.com/price_csv/uploade_check.php?msg_full_path=$full_path'</script>");
   
} else{

     $msg= "Your file Size is greater Than specified limit, please try again!";
	print("<script>window.location='http://www.tooldex.com/price_csv/index.php?msg=$msg'</script>");
}

 
?>

 