<?php  

mysql_connect('localhost','root','');
    mysql_select_db('mukesh_csv');
    

     
	
 

//       get file extension this function.............
  
        $file="db_csv/cate_client.csv";
      $handle = fopen("$file", "r");
	  
	  

     /*$handle = fopen('kd.csv', 'r');*/
	 
	 //       pass value in array .............

while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
   
    $val1 = mysql_real_escape_string($data[0]);
	
	 
 

			$aaa="INSERT INTO `client_cate` (`name`) VALUES ('{$val1}')";
			
		
		   
		   mysql_query($aaa) or die(mysql_error());
		   
		   
		   
			$result = (mysql_insert_id()> 0) ? 'i' : 'i' ;
		   
			$output[$result]++;
			
		echo	$msg="Your Csv File Is Successfully Upload..........";

	}
	
	print_r($result);
	
	 
  




 
/* 
 mysql_query("INSERT INTO `csv work` (`company_name`,`owner_name`,`owner_DOB`,`Owner_contact`,`owner_email`,`Company_telephone`,`company_mobile`,`company_email`,`have_website`,`website`,`company_address`,`area`,`city`,`state`,`pin_code`,`pin_code`,`service_category`,`service_offered`,`other_service`,`company breif`,`keyword`) VALUES ('{$val1}','{$val2}','{$val3}','{$val4}','{$val5}','{$val6}','{$val7}','{$val8}','{$val9}','{$val10}','{$val11}','{$val12}','{$val13}','{$val14}','{$val15}','{$val16}','{$val17}','{$val18}','{$val19}','{$val20}')");




echo $file=$_FILES['file']['name'];

echo $handle = fopen("$file", "r");


 






     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)

     {   

       $import="INSERT into aa(id,name,city) values('$data[0]','$data[1]','$data[2]')";

       mysql_query($import) or die(mysql_error());

     }

     fclose($handle);

     print "Import done";  /*
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 

       $file=$_FILES['file']['name'];
        $path_info = pathinfo($file);
		$ex=$path_info['extension'];
		
		if($ex==csv)
		{
 
     $file=$_FILES['file']['name'];
	 
     $handle = fopen("$file", "r");
	 
     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)

     {
       $import="INSERT into aa(id,name,city,min,max) values('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]')";
       mysql_query($import) or die(mysql_error());
     }

     fclose($handle);
	 
	  $msg="File successful Upload...";
*/
  /*   }
	 
	 else
	 {
	 
	  $msg="Format Not Allowed...";
	 
	 }*/ 


//print("<script>window.location='main.php?cmd=work_upload&msg=$msg'</script>");
 
  