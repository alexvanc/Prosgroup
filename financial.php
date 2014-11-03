<?php
	header("Content-type: text/html; charset=UTF-8");
	require('connectdb.php');
	$id=$_GET["ID"];
	$legal=ifLegal($id);
	if($legal){
		$query="select * from financial_data where id=$id";
		$result=mysql_query($query);//执行查询
		if(!$result)
		{
			die("could not connect to the database</br>".mysql_error());
		}
		$array2;
		if($result_row=mysql_fetch_row($result))//取出结果并显示
		{
			 $name1=$result_row[1]; 
			 $name2=$result_row[2]; 
			 $name3=$result_row[3]; 
			 $name4=$result_row[4]; 
			 $name5=$result_row[5]; 
			 $name6=$result_row[6]; 
			 $name7=$result_row[7]; 
			 $name8=$result_row[8]; 
			 $name9=$result_row[9]; 
			 $name10=$result_row[10]; 
			 $name11=$result_row[11]; 
			 $name12=$result_row[12]; 
			 $name13=$result_row[13]; 
			 $array2=array('enterprise_net_profit_margin'=>$name1,'industry_net_profit_margin'=>$name2,
				'enterprise_roe'=>$name3,'industry_roe'=>$name4,'industry_asset_liability_ratio'=>$name5,
				'the_gearing_ratio'=>$name6,'company_inventory_turnover'=>$name7,
				'industry_inventory_turnover'=>$name8,'company_quick_ratio'=>$name9,
				'industry_quick_ratio'=>$name10,'company_consolidated_leverage'=>$name11,
				'industry_consolidated_leverage'=>$name12,'ratings'=>$name13);
		}
		$final=json_encode($array2);
		echo $final;
	}else{
		echo 'Parameters Error!';
	}
	




?>