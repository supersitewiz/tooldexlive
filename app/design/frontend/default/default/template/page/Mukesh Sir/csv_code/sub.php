<?php   

mysql_connect('localhost','root','');
mysql_select_db('mukesh_csv');
        
$sql=mysql_query("select * from client_cate where name not in (select name from new_tooldex_cate)");
$i=1;
while($row=mysql_fetch_array($sql))
{
echo $i++;
echo ' ';
echo $row['name'].'<br/>';
}
?>
