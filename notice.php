<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$getIDs=$_GET["ID"];
	$getNext=$_GET["next"];
	$legal=ifIDLegal($getIDs)&&ifNextLegal($getNext,$getIDs);
	$multiID=ifMultiID($getIDs);
	$final;
	$counter=0;
	
	if($legal){
		$query='';
		$counter=getCounter($multiID,$getIDs);
		$array2;
		$counter2=0;
		if($multiID){
			$query="select id,date,title,content from notice WHERE id IN $getIDs order by date DESC";
			
		}else{
			$query="select id,date,title,content from notice WHERE id=$getIDs order by date DESC";
		}
		$result=mysql_query($query);
		if((integer)$getNext-1!=0){
			if(!mysql_data_seek($result,((integer)$getNext-1)*20)) {
			return ;
			}
		}
		if($getNext==$counter/20+1){//如果是最后一屏
			while($result_row=mysql_fetch_row(($result)))//取出结果并显示
			{
			$id=$result_row[0];
			$date=$result_row[1];
			//$title=urlencode($result_row[2]);
			//$title=urlencode(replace($result_row[2]));
			$title=urlencode(preg_replace($search,$replace,$result_row[2]));
			//$content=urlencode($result_row[3]);
			//$content=urlencode(replace($result_row[3]));
			$content=urlencode(preg_replace($search,$replace,$result_row[3]));
			$arr=array('id'=>$id,'date'=>$date,'title'=>$title,'content'=>$content);
			//$strr=json_encode($arr);
			$array2[$counter2]=$arr;
			$counter2++;
			}
				
		}else{
			$tempcounter=0;
			while($result_row=mysql_fetch_row(($result)))//取出结果并显示
			{
			$id=$result_row[0];
			$date=$result_row[1];
			//$title=urlencode($result_row[2]);
			//$title=urlencode(replace($result_row[2]));
			$title=urlencode(preg_replace($search,$replace,$result_row[2]));
			//$content=urlencode($result_row[3]);
			//$content=urlencode(replace($result_row[3]));
			$content=urlencode(preg_replace($search,$replace,$result_row[3]));
			$arr=array('id'=>$id,'date'=>$date,'title'=>$title,'content'=>$content);
			//$strr=json_encode($arr);
			$array2[$counter2]=$arr;
			$counter2++;
			$tempcounter++;
			if($tempcounter==20){
				break;
			}
			}
			
		}
		$final=json_encode($array2);
		//echo urldecode($final);
		echo preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", urldecode($final));
	}else{
		echo 'Parameters Error!';
	}
	
	
	
	//判断next是否合法的函数
	function ifNextLegal($next,$ids){
		if($next==null){
			return false;
		}
		if(ifIDLegal($ids)){
			$query;
			if(ifMultiID($ids)){
				$query="select id,title,date from notice WHERE id IN $ids";
			}else{
				$query="select id,title,date from notice WHERE id=$ids";
			}
			$result=mysql_query($query);
			$num=mysql_num_rows($result);
			if(is_numeric($next)&&$next>=1&&$next<=($num/20+1)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//返回结果数量的函数
	function getCounter($boo,$ids){
		$query='';
		if($boo){
			$query="select id,title,date from notice WHERE id IN $ids";
		}else{
			$query="select id,title,date from notice WHERE id=$ids";
		}
		$result=mysql_query($query);
		$num=mysql_num_rows($result);
		return $num;

	}
	
?>