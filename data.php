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
		$counter1=0;//�����������
		if($multiID){//����ж��id�Ļ�
		$temparray=eachId($getIDs);
		for($i=0;$i<count($temparray);$i++){
			$query="select base11.id,base11.date,dop,dcp,csic,gc from base00,base11 where base11.id=$temparray[$i] AND base00.id_date_cast=base11.id_date_cast 
					AND  base11.date<$endDate AND base11.date>=$startDate";
			$result=mysql_query($query);//ִ�в�ѯ
				if(!$result)
				{
					die("could not connect to the database</br>".mysql_error());

				}
			//ÿ��id��Ӧ�ܶ�����ݶ���
			$counter2=0;
			$array2;
			while($result_row=mysql_fetch_row(($result)))//ȡ���������ʾ
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
			// if($temparray[0]==1&&$temparray[1]==2&&$i==1){ //һ���Ƚ�����Ĺ���
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
			$result=mysql_query($query);//ִ�в�ѯ
				if(!$result)
				{
					die("could not connect to the database</br>".mysql_error());

				}
				$array2;
				$counter2=0;
			while($result_row=mysql_fetch_row(($result)))//ȡ���������ʾ
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
	
	
	
	
	
	//�ж�date�Ƿ�Ϸ��ĺ���
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
	//�������������ȡ��ѯ���ݵ���ʼ����
	function getStartByIndex($getDate){
		$index=((integer)$getDate)-1;
		if($index==-1){
			return date('Y-m-01', strtotime("-5 year"));
		}
		return date('Y-m-01', strtotime("-$index month"));
	}
	//�������������ѯ���ݵĽ�ֹ����
	function getEndByIndex($getDate){
		$index=(integer)$getDate;
		if($index==1||$index==0){
			return date('Y-m-d',time());
		}else{
			$index-=2;
			return date('Y-m-01', strtotime("-$index month"));
		}
	}
	
	//���ַ����ֽ�Ϊid����ĺ���
	function eachId($ids){
		$tempStr=substr($ids,1,-1);
		$temparray=explode(",",$tempStr);
		return $temparray;
	}



?>