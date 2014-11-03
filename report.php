<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$getIDs=$_GET["ID"];
	$final;//最后返回的数据
	$legal=ifIDLegal($getIDs);
	$multiID=ifIDLegal($getIDs);
	if($legal){
		$query='';
		$array;
		$counter=0;
		if($multiID){
			$query="select id,analysisrep from company WHERE id IN $getIDs";
		}else{
			$query="select id,analysisrep from company WHERE id=$getIDs";
		}
		$result=mysql_query($query);//执行查询
		if(!$result)
		{
		die("could not connect to the database</br>".mysql_error());
		}
		while($result_row=mysql_fetch_row(($result)))//取出结果并显示
		{
		$id=$result_row[0];
		$url=$result_row[1];
		$arr=array('id'=>$id,'url'=>$url);
		$array[$counter]=$arr;
		$counter++;
		}
		$final=json_encode($array);
		echo $final;
	}else{
		echo 'Parameters Error!';
	}
	
	


?>