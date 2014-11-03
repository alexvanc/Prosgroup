<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$getIDs=$_GET["ID"];
	$getNext=$_GET["next"];
	$legal=ifIDLegal($getIDs)&&ifNextLegal($getNext);
	$multiID= ifMultiID($getIDs);
	$final="";
	$counter=0;
	
	if($legal){
		
		$query='';
		$array2;
		$counter2=0;
		$start=((integer)$getNext-1)*20;
		$end=((integer)$getNext)*20;
		if($multiID){
			$query="select id,date,title,content,from_where from research_report WHERE id IN $getIDs order by date DESC limit $start,$end";
			
		}else{
			$query="select id,date,title,content, from_where from research_report WHERE id=$getIDs order by date DESC limit $start,$end";
		}
		
		$result=mysql_query($query)or die("Invalid query: ".mysql_error());
		
			while($result_row=mysql_fetch_row(($result)))//取出结果并显示
			{
			$id=$result_row[0];
			$date=$result_row[1];  
			$title=preg_replace($search,$replace,trim($result_row[2]));
			$content=preg_replace($search,$replace,trim($result_row[3]));
			$research_report=$result_row[4];
			// $research_report=$result_row[4];
			$arr=array("id"=>$id,"date"=>$date,"title"=>$title,"content"=>$content,"from_where"=>$research_report);
			$array2[$counter2]=$arr;
			$counter2++;
			}
		$temp=array('title'=>'title','content'=>'content','test'=>'中文');
		// $final=json_encode($array2);
		// echo $final;
		echo json_encode($temp);
		exit;
			// preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $json);
		echo preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $final);
		//echo urldecode($final);
	}else{
		echo 'Parameters Error!';
	}
	
	
	
	
	//判断next是否合法的函数
	function ifNextLegal($next){
		if($next==null){
			return false;
		}
		if(is_numeric($next)){
			return true;
		}else{
			return false;
		}
	}
	
?>