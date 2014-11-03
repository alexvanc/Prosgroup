<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$getIDs=$_GET["ID"];
	$legal=ifIDLegal($getIDs);
	$multiID=ifMultiID($getIDs);
	$final;
	if($legal){
		$array;
		$counter=0;
		$query;
		if($multiID){
			$query="select id,name,briefintro,analysisrep from company WHERE ID IN $getIDs";
		}else{
			$query="select id,name,briefintro,analysisrep from company WHERE id=$getIDs";
		}
		$result=mysql_query($query);//执行查询
		if(!$result)
		{
		die("could not connect to the database</br>".mysql_error());
		}
		while($result_row=mysql_fetch_row(($result)))//取出结果并显示
		{
		$id=$result_row[0]; 
		$name=urlencode(preg_replace($search,$replace,$result_row[1]));
		//$info=urlencode($result_row[2]);
		$info=urlencode(preg_replace($search,$replace,$result_row[2]));
		$analysisrep=urlencode($result_row[3]);
		$arr=array('id'=>$id,'name'=>$name,'info'=>$info,'analysisrep'=>$analysisrep);
		$array[$counter]=$arr;
		$counter++;
		}
		$final=json_encode($array);

		echo preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", urldecode($final));;
	}else{
		echo 'Parameters Error!';
	}
	
?>