<?php
header("Content-type: text/html; charset=UTF-8");
require('connectdb.php');
$content=$_POST['content'];
$email=$_POST['email'];
$time=$_POST['time'];
$type=$_POST['type'];
if(!empty($content)){
	$query="insert into feedback(content,time,type,email) values('$content','$time','$type','$type')";
	$result=mysql_query($query);
	if($result){
		echo 'true';
	}else{
		echo 'false';
	}
}else{
	echo "Parameter error";
}


?>