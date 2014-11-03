<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$getDate=$_GET["date"];
	$getIDs=$_GET["ID"];
	$legal=ifDateLegal($getDate)&&ifIDLegal($getIDs);
	$multiID=ifMultiID($getIDs);
	if($legal){
		$final="";
		$array1;
		$startDate="'".getStartByIndex($getDate)."'";
		$endDate="'".getEndByIndex($getDate)."'";
		$counter1=0;//最外面的数组
		if($multiID){//如果有多个id的话
		$temparray=eachId($getIDs);
		for($i=0;$i<count($temparray);$i++){
			$query="select base11.id,base11.date,dop,dcp,csic,gc from base00,base11 where base11.id=$temparray[$i] AND base00.id_date_cast=base11.id_date_cast 
					AND  base11.date<$endDate AND base11.date>=$startDate";
			$result=mysql_query($query);//执行查询
				if(!$result)
				{
					die("could not connect to the database</br>".mysql_error());

				}
			//每个id对应很多个数据对象
			$counter2=0;
			$array2;
			while($result_row=mysql_fetch_row(($result)))//取出结果并显示
			{
			$id=$result_row[0];
			$date=$result_row[1];
			$dop=$result_row[2];
			$dcp=$result_row[3];
			$csic=$result_row[4];
			$gc=$result_row[5];
			$arr=array('id'=>$id,'date'=>$date,'dop'=>$dop,'dcp'=>$dcp,'csic'=>$csic,'gc'=>$gc);
			//$strr=json_encode($arr);
			$array2[$counter2]=$arr;
			$counter2++;
			// if($temparray[0]==1&&$temparray[1]==2&&$i==1){ //一个比较奇葩的规则
				// unset($array2[19]);
			// }
			}
			$array1[$counter1]=$array2;
			$counter1++;
			}
			$final=json_encode($array1);
			echo $final;
		}else{
			$query="select base11.id,base11.date,dop,dcp,csic,gc from base00,base11 where base00.id_date_cast=base11.id_date_cast 
					AND base11.id=$getIDs AND base11.date<$endDate AND base11.date>=$startDate";
			$result=mysql_query($query);//执行查询
				if(!$result)
				{
					die("could not connect to the database</br>".mysql_error());

				}
				$array2;
				$counter2=0;
			while($result_row=mysql_fetch_row(($result)))//取出结果并显示
			{
			$id=$result_row[0];
			$date=$result_row[1];
			$dop=$result_row[2];
			$dcp=$result_row[3];
			$csic=$result_row[4];
			$gc=$result_row[5];
			$arr=array('id'=>$id,'date'=>$date,'dop'=>$dop,'dcp'=>$dcp,'csic'=>$csic,'gc'=>$gc);
			$array2[$counter2]=$arr;
			$counter2++;
			}
			$array1[0]=$array2;
			$final=json_encode($array1);
			echo $final;
		}
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
	
	//将字符串分解为id数组的函数
	function eachId($ids){
		$tempStr=substr($ids,1,-1);
		$temparray=explode(",",$tempStr);
		return $temparray;
	}



?>