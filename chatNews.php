<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$num=$_GET["num"];
	if(is_numeric($num)){
		$query="select title,content from news where num=$num";
		$result=mysql_query($query)or die("Invalid query: ".mysql_error());
		if($result_row=mysql_fetch_row($result)){//取出结果并显示
			$prefix='<head><meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes" />';
			$title="<title>$result_row[0]</title></head>";
			$result=$prefix.$title.$result_row[1];
			echo $result;
		}
	}else{
		echo 'Parameters Error!';
	}

?>