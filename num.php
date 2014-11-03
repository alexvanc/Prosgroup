<?php
header("Content-type: text/html; charset=UTF-8");
require('connectdb.php');
$get=$_GET['get'];
$vary=$_GET['vary'];
$getIds=$_GET['ID'];
$type=$_GET['type'];
$legal=ifIDLegal($getIds);
$multiID=ifMultiID($getIds);
if($legal){

	if($multiID){
		$idArray=eachId($getIds);
		if(empty($get)){
			if(!empty($vary)){
				for($i=0;$i<count($idArray);$i++){
					$id=$idArray[$i];
					$query="select focus_num from company where id=$id";
					$result=mysql_query($query);
					if(!$result){
						die("could not connect to the database</br>".mysql_error());
					}
				
					if($result_row=mysql_fetch_row($result)){
						$tempNum=$result_row[0];
						$num=(integer)$tempNum;
						if($vary='add')
							$num++;
						else if($vary='sub')
							$num--;
						$query="update company set focus_num=$num where id=$id";
						if(!mysql_query($query)){
							echo 'false';
							exit;
						}
					}
				
				
				}
				echo 'true';
			}else{
				echo 'Parameter error1';
				exit;
			}
		}else if($get=='true'){
			$idArray=eachId($getIds);
			$array2;
			$counter=0;
			for($i=0;$i<count($idArray);$i++){
					$id=$idArray[$i];
					$query="select focus_num from company where id=$id";
					$result=mysql_query($query);
					if(!$result){
						die("could not connect to the database</br>".mysql_error());
					}
					if($result_row=mysql_fetch_row($result)){
						$array2[$counter]=array("id"=>$id,"num"=>$result_row[0]);
						$counter++;
					}
			}
			echo json_encode($array2);
			exit;
		}
		
	}else{
		if(empty($get)){
			if(!empty($vary)){
				$query="select focus_num from company where id=$getIds";
				$result=mysql_query($query);
				if(!$result){
					die("could not connect to the database</br>".mysql_error());
				}
				if($result_row=mysql_fetch_row($result)){
					$tempNum=$result_row[0];
					$num=(integer)$tempNum;
					if($vary='add')
						$num++;
					else if($vary='sub')
						$num--;
					$query="update company set focus_num=$num where id=$getIds";
					if(mysql_query($query)){
						echo 'true';
						exit;
					}else{
						echo 'false';
					}
				}
			}else{
				echo 'Parameter error2';
				exit;
			}
		}else if($get=='true'){
			$array2;
			$counter=0;
				$query="select focus_num from company where id=$getIds";
				$result=mysql_query($query);
				if(!$result){
					die("could not connect to the database</br>".mysql_error());
				}
				if($result_row=mysql_fetch_row($result)){
					$array2[$counter]=array("id"=>$getIds,"num"=>$result_row[0]);
					$counter++;
				}
			echo json_encode($array2);
			exit;
		}
	
	}
	
}else{
	echo "Parameter error3";
}

	//将字符串分解为id数组的函数
	function eachId($ids){
		$tempStr=substr($ids,1,-1);
		$temparray=explode(",",$tempStr);
		return $temparray;
	}

?>