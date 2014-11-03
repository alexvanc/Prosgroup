<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$type=$_GET["type"];
	$history=$_GET["history"];
	$legal=ifTypeLegal($type);
	if($legal){
		$typeNum=(integer)$type;
		$query="";
		if($history=="true"){
			$query="select * from version where type=$typeNum";
		}else if($history==false){
			$query="select * from version where type=$typeNum order by code DESC limit 1";
		}else{
			echo "Parameters error";
			exit;
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
			$name=$result_row[0];
			$code=$result_row[1];
			$type=$result_row[2];
			$features=$result_row[3];
			$size=$result_row[4];
			$address=$result_row[5];
			$time=$result_row[6];
			$arr=array('name'=>$name,'code'=>$code,'type'=>$type,
						'features'=>$features,'time'=>$time,'size'=>$size,'address'=>$address);
			$array2[$counter2]=$arr;
			$counter2++;
		}
		$final=json_encode($array2);
		echo $final;
	}else{
		echo 'Parameters Error!';
	}
	
	
	
	
	//判断type是否合法
	function ifTypeLegal($type){
		if(is_numeric($type)){
			$typeNum=(integer)$type;
			if($typeNum==1||$typeNum==2||$typeNum==3){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}

	



?>