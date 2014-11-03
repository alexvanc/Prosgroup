<?php
header("Content-type: text/html; charset=UTF-8");
$db_host='*****';
$db_database='*****';
$db_username='*****';
$db_password='*****';
$connection=mysql_connect($db_host,$db_username,$db_password);//连接到数据库
mysql_query("set names utf8",$connection);//编码转化
if(!$connection){
	die("could not connect to the database:</br>".mysql_error());//诊断连接错误
}
$db_selecct=mysql_select_db($db_database);//选择数据库
if(!$db_selecct)
{
die("could not to the database</br>".mysql_error()); 
}

	//判断ID是否合法的函数
	function ifLegal($id){
		if(is_numeric($id)){
			$query="select id ,name from company where id=$id";
			$result=mysql_query($query);//执行查询
			if(!$result)
				{
					die("could not connect to the database</br>".mysql_error());

				}
			if(mysql_num_rows($result)==1){
				return true;
			}
			return false;
		}else{
			return false;
		}
	
	}
	
	
	//如果是多个ID，则进行判断,因为先进行有效性判断，所以简化了操作
	function ifIDLegal($id){
		if($id==null){
			return false;
		}
		if(is_numeric($id)){
			return ifLegal($id);
		}
		$tempStr=substr($id,1,-1);
		$temparray=explode(",",$tempStr);
		for($i=0;$i<count($temparray);$i++){
			if(!ifLegal($temparray[$i])){
				return false;
			}
		}
		return true;	
		
	}
	//判断是否是多个ID
	function ifMultiID($id){
		if(ifIDLegal($id)){
			if(is_numeric($id)){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	//将内容里面的关键英文符号如:"'替换为对应的中文符号的方法
	function replace($source){
		//将英文双引号替换问中文双引号
		$target=preg_replace('/"([^"]*)"/', '“${1}”',$source);
		//将英文单引号替换为中单引号
		$target=preg_replace("/'([^']*)'/", '‘${1}’',$target);
		//将英文冒号替换问英文冒号
		//$target=preg_replace("/:/","：",$target);
		//将英文中括号替换为英文中括号
		//$target=preg_replace('/\[([^"]*)\]/', '【${1}】',$target);
		return $target;
	}
	
	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉javascript
					 '/="([^\"]*)"/',					//将网页标签中的双引号替换为单引号
	                 "'((\r\n)|\s)+'",                 // 去掉空白字符
	                 "'&(quot|#34);'i",                 // 替换 HTML 实体 双引号
	                 "'&(amp|#38);'i",					//取地址符
	                 "'&(lt|#60);'i",					//小于号
	                 "'&(gt|#62);'i",					//大于号
	                 "'&(nbsp|#160);'i",				//空格
	              //   "'&(iexcl|#161);'i",				//i
	             //    "'&(cent|#162);'i",				//¢			
	             //    "'&(pound|#163);'i",				//£
	            //     "'&(copy|#169);'i",				//©
				//	 '/"([^"]*)"/',						//	自己添加的替换英文双引号部分
					 '/\[([^"]*)\]/',					//	替换中括号
					 "/\\\\/", 							//替换反斜杠
					 '/"/',								//替换单个的英文引号
					 "'&(bull|#8226);'i",
	                 "'&#(\d+);'e");                    // 作为 PHP 代码运行
	
	$replace = array ("",
					  '=\'${1}\'',
	                  " ",				//"\\1",
	                  "\"",
	                  "&",
	                  "<",
	                  ">",
	                  " ",
	          //        chr(161),
	          //        chr(162),
	          //        chr(163),
	          //        chr(169),
					  '“${1}”',
					  '【${1}】', 
					  "/", 
					  "“", 
					  "•", 
	                  "chr(\\1)");
	
?>