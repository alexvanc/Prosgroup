<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$getDate=$_GET["date"];
	$id=$_GET["ID"];
	$legal=ifDateLegal($getDate)&&ifTypeLegal($id);
	$multiID=ifMultiID($getIDs);
	if($legal&&is_numeric($id)){
		$type=(integer)$id;
		$startDate="'".getStartByIndex($getDate)."'";
		$endDate="'".getEndByIndex($getDate)."'";
		$query="";
		
		if($id==1){
			$query="select * from dapan where date<$endDate AND date>=$startDate";
		}else if($id==2){
			$query="select * from chuangye where date<$endDate AND date>=$startDate";
		}
		$result=mysql_query($query);//执行查询
		if(!$result)
		{
			die("could not connect to the database</br>".mysql_error());

		}
		$array2;
		$counter2=0;
		while($result_row=mysql_fetch_row($result))//取出结果并显示
		{
			$tdate=$result_row[0];
			$dcp=$result_row[1];
			$arr=array('date'=>$tdate,'dcp'=>$dcp);
			$array2[$counter2]=$arr;
			$counter2++;
		}
		$final=json_encode($array2);
		echo $final;
	}else{
		echo 'Parameters Error!';
	}
	
	
	
	
	
	//判断date是否合法的函数
	function ifDateLegal($date){
		if($date==null){
			return false;
		}
		if(is_numeric($date)){
			return true;
		}else{
			return false;
		}
	}
	//判断ID是否合法
	function ifTypeLegal($id){
		if(is_numeric($id)){
			$type=(integer)$id;
			if($type==1||$type==2){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	//根据输入参数获取查询数据的起始日期
	function getStartByIndex($getDate){
		$index=((integer)$getDate)-1;
		if($index==-1){
			return date('Y-m-01', strtotime("-5 year"));
		}
		return date('Y-m-01', strtotime("-$index month"));
	}
	//根据输入参数查询数据的截止日期
	function getEndByIndex($getDate){
		$index=(integer)$getDate;
		if($index==1||$index==0){
			return date('Y-m-d',time());
		}else{
			$index-=2;
			return date('Y-m-01', strtotime("-$index month"));
		}
	}
	



?>