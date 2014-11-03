<?php
header("Content-type: text/html; charset=UTF-8");
require('connectdb.php');

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
$keyPrice="MENU_PRICE";
$keyRecommend="MENU_RECOMMEND";
$keyDownload="SUNMENU_DOWNLOAD";
$keyBackground="SUNMENU_BACKGROUND";
$keyAllCompany="SUBMENU_ALL";
$introduction="这里是iBuffett价值投资小助手，股票查询请回复股票代码或股票名称，如：601519或大智慧";
$prompt="暂无相关信息，股票查询请回复股票代码或股票名称，如：601519或大智慧";
$helpkey="帮助";
$servekey="客服";
$allkeywords=array($helpkey,$servekey);
$content="";
$pinyin=array();
$simstockcode=array();
$mixstockcode=array();
$name=array();
$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";  
if (!empty($postStr)){
	init_key($pinyin,$simstockcode,$mixstockcode,$name);
	$time = time();
	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$msgType=$postObj->MsgType;
	$content="";
	if($msgType=='event'){
		$event=$postObj->Event;
		if($event=='subscribe'){
			$content.=$introduction;
		}else if($event=='unsubscribe'){
			$content.='有缘再见！';
		}else if($event=='CLICK'){
			$eventKey=$postObj->EventKey;
			switch ($eventKey){
				case $keyPrice:
					$content.=getPriceResult($fromUsername,$toUsername);//得到大盘的各种指数
					echo $content;
					exit;
				case $keyRecommend:
					$content.=getRecommentResult($fromUsername,$toUsername,$mixstockcode);//得到三条最近的新闻
					echo $content;
					exit;
				case $keyDownload:
					$content.=getAllDownloadAddress();
					break;
				case $keyAllCompany:
					$content.=getAllCompany();//获取所有公司的名称
					break;
				case $keyBackground:
					$content.="艾巴菲特（iBuffett）价值投资信息系统集业绩评估，资讯阅览，报告详解于一体，家族控股上市公司动态、股票池指数即选即查。希望分享中国未来经济发展成果的你，千万不要错过这款能助你实现财富梦想的好帮手哦！";
					break;
					default:
					
			}
		}
	}else if($msgType=='text'){
		$keyword=trim($postObj->Content);
		//开始对关键字进行分析
		if(!empty( $keyword )){
			$type=checkType($keyword,$pinyin,$simstockcode,$mixstockcode,$name);
			if(is_numeric($keyword)&&ifLegal($keyword)){//回复新闻和行情
				$query="select name from company where id=$keyword";
				$result=mysql_query($query);
				if(!$result){
					// die("could not connect to the database</br>".mysql_error());
					$content.="could not connect to the database</br>";
				}
				if($result_row=mysql_fetch_row($result)){
					$content.=$result_row[0];
				}
			}else if($type!=0){
				$id=getIdByType($type,$keyword);
				echo getDetailResult($id,$fromUsername,$toUsername);
				exit;
			}else if(in_array($keyword,$allkeywords)){
				switch ($keyword){
				case $helpkey:
					$content.="股票查询请回复股票代码或股票名称";
					break;
				case $servekey:
					$content.="暂时还没有客服";
					break;		
				}
			}else{
				$content.=$prompt;
			}
		}else{
			$content.='输入内容不能为空';
		}
	}else{
		$content.='本系统目前只支持文字';
	}
	$msgType = "text";
	$contentStr = $content;
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	echo $resultStr;

}else{
	$id=$_GET["ID"];
	if($id!=null){
		if(ifLegal($id)){
			$stockCode=getCodeById($id);
			$sourceCode=file_get_contents("http://3.manbu.sinaapp.com/zhangzhuxin/content.php?code=$stockCode");
			echo str_ireplace('股票医生','iBuffett价值投资小助手',$sourceCode);
		}else if($id='300'){
			$sourceCode=file_get_contents("http://3.manbu.sinaapp.com/zhangzhuxin/content.php?code=sh000300");
			echo str_ireplace('股票医生','iBuffett价值投资小助手',$sourceCode);
		}
	}else{
		$string="
		<HTML>
			<HEAD>
			<META http-equiv=\"REFRESH\" CONTENT=\"4; url=http://www.lxjx.cn/\">
			</HEAD>
			<BODY>
				<center><b><h1>挖掘机技术到底哪家强？</h1></b></center>
				</br>
				<h3>答案三秒钟后揭晓……</h3>
			</BODY>
		</HTML>
		";
		echo $string;
	}
}

	function getAllDownloadAddress(){
		$resultAddress="";
		$query="select type,new_addr from version";
		$result=mysql_query($query);
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
			return "could not connect to the database</br>";
		}
		while($result_row=mysql_fetch_row($result)){
			switch ($result_row[0]){
				case 1:
					$resultAddress.="android: ".$result_row[1];
					break;
				case 2:
					$resultAddress.="WindowsPhone8: ".$result_row[1];
					break;
				case 3:
					$resultAddress.="iO ".$result_row[1];
					break;
					default:
					
			}
			$allCompany.=$result_row[0].'\n';
		}
	}
	function getAllCompany(){
		$allCompany="";
		$query="select name from company";
		$result=mysql_query($query);
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
			return "could not connect to the database</br>";
		}
		while($result_row=mysql_fetch_row($result)){
			$counter=strlen($result_row[0]);
			if($counter==12){
				$allCompany.=$result_row[0].'        ';
			}else if($counter==9){
				$allCompany.=$result_row[0].'            ';
			}else{
				$allCompany.=$result_row[0].'            ';
			}
			
		}
		return $allCompany;
	}
	//推荐menu的响应方法
	 function getRecommentResult($toUsername,$fromUsername,$mixstockcode){
	 $newsTpl="<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>3</ArticleCount>
							<Articles>";
	 $newsTpl2="<item>
							<Title><![CDATA[%s]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>";
		//先随机取一家公司
		$resultStr=sprintf($newsTpl,$toUsername,$fromUsername,time());
		$generate=true;
		while($generate){
			$tempStr="";
			$flag=true;
			shuffle($mixstockcode);
			$temp = array_slice($mixstockcode,0,3);
			foreach($temp as $code){
			// echo $code;
				$name="";
				$allData=file_get_contents("http://202.108.37.102/list=$code",10);//新浪的网页是GBK编码
				$allData=iconv("GBK", "utf-8//IGNORE",$allData);
				$tempArray=explode(",",$allData);
				if($tempArray[1]=='0.00'){
					$flag=false;
					break;
				}else{
					// $resultStr.="";
					$query="select name,id from company where stock_code='$code'";
					$result=mysql_query($query);
					if(!$result){
						die("database Error!");
					}
					if($result_row=mysql_fetch_row($result)){
						$name=$result_row[0];
						$tempStr.=sprintf($newsTpl2,$name,$name,"http://image.sinajs.cn/newchart/daily/n/$code.gif","Host/citi/validate.php?ID=$result_row[1]");
					}
					
				}
			}
			if($flag){
				$resultStr.=$tempStr."</Articles></xml>";
				$generate=false;
			}
			
		}
		return $resultStr;
		
	}
	//获取一条格式化后的新闻
	function formatOneNew($result_row){
		$itemTpl="<item>
							<Title><![CDATA[%s]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
					</item>";
		$num=$result_row[0];
		$id=$result_row[1];
		$tempTitle=$result_row[2];
		$content=$result_row[4];
		$picUrl=getUrlByContent($content);
		$url="Host/citi/chatNews.php?num=$num";
		$query="select name from company where id=$id";
		$result=mysql_query($query);
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
			return "could not connect to the database</br>";
		}
		$resultRow=mysql_fetch_row($result);
		$companyName=$resultRow[0];
		$title=$tempTitle."\r\n\t\t\t\t---------\t".$companyName;
		$description="hahha";
		return sprintf($itemTpl,$title,$description,$picUrl,$url);
	}
	//初始化一些关键字
	function init_key(&$pinyin,&$simstockcode,&$mixstockcode,&$name){
		$result=mysql_query("select name,stock_code,name_ping from company");
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
			return "could not connect to the database</br>";
		}
		while($result_row=mysql_fetch_row($result)){
			array_push($pinyin,$result_row[2]);
			array_push($simstockcode,substr($result_row[1],2));
			array_push($mixstockcode,$result_row[1]);
			array_push($name,$result_row[0]);
		}
	
	}
	//判断用户的输入是否是合法查询
	function checkType($keyword,$pinyin,$stockcode,$mixstockcode,$name){
		if(in_array($keyword,$pinyin))	{return 1;}
		if(in_array($keyword,$stockcode))	{return 2;}
		if(in_array($keyword,$mixstockcode))	{return 3;}
		if(in_array($keyword,$name))	{return 4;}
		return 0;
	}
	//每个公司的一条行情，两条新闻
	function getDetailResult($id,$toUsername,$fromUsername){
		
		$newsTpl="<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>3</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[%s]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>";
		$newsTpl2="</Articles>
							</xml> ";
		$resultContent="";
		$name="";
		$stockCode="";
		$query="select name,stock_code from company where id=$id";
		$result=mysql_query($query);
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
		}
		if($result_row=mysql_fetch_row($result)){
			$name=$result_row[0]."行情";
			$stockCode=$result_row[1];
		}
		$picUrl="http://image.sinajs.cn/newchart/daily/n/$stockCode.gif";
		$url="Host/citi/validate.php?ID=$id";
		$resultContent.=sprintf($newsTpl,$toUsername,$fromUsername,time(),$name,$name,$picUrl,$url);
		$query="select num,id,title,date,content from news where id=$id order by date DESC limit 2";
		$result=mysql_query($query);
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
		}
		while($result_row=mysql_fetch_row($result)){
			$resultContent.=formatOneNew($result_row);
		}
		$resultContent.=$newsTpl2;
		return $resultContent;
	}
	//获取用户输入信息对应的公司ID
	function getIdByType($type,$keyword){
		$query="";
		switch ($type){
			case 1:
				$query="select id from company where name_ping='".$keyword."'";
			break;
			case 2:
				$query="select id from company where stock_code LIKE '%".$keyword."'";
			break;
			case 3:
				$query="select id from company where stock_code='".$keyword."'";
			break;
			case 4:
				$query="select id from company where name='".$keyword."'";
			break;
		}
		$result=mysql_query($query);
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
		}
		if($result_row=mysql_fetch_row($result)){
			return $result_row[0];
		}
		
	}
	//根据新闻内容抽取图片地址的函数，如果没有则随机选服务器上的图片
	function getUrlByContent($content){
		$matches=null;
		$pattern='/<img.*?src\=(\"|\')([^>]*(\.jpg|\.jpeg|\.bmp|\.png))(\"|\')>/';
		preg_match_all($pattern, $content, $matches);
		if(count($matches[0])==0){
			//随机生成图片
			$num=rand(1,10);
			return "Host/citi/dpic/$num.jpg";
		}
		return $matches[0][0];	
	} 
	//响应行情menu的方法
	function getPriceResult($toUsername,$fromUsername){
		$priceTpl="<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>1</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[沪深行情]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[http://image.sinajs.cn/newchart/daily/n/sh000001.gif]]></PicUrl>
							<Url><![CDATA[Host/citi/validate.php?ID=300]]></Url>
							</item>
							</Articles>
					</xml>";
		//由于阿里云服务器DNS解析不了新浪的部分域名，需要用ip
		$allData=file_get_contents("http://202.108.37.102/list=sh000001",10);//新浪的网页是GBK编码
		$allData=iconv("GBK", "utf-8//IGNORE",$allData);
		$tempArray=explode(",",$allData);
		$content.="今开：".$tempArray[1];
		$content.="\r\n昨收：".$tempArray[2];
		$content.="\r\n当前价格：".$tempArray[3];
		$content.="\r\n今日最高：".$tempArray[4];
		$content.="\r\n今日最低：".$tempArray[5];
		$content.="\r\n买入：".$tempArray[6];
		$content.="\r\n卖出：".$tempArray[7];
		$content.="\r\n成交（百股）：".($tempArray[8]/100);
		$content.="\r\n成交（万元）：".($tempArray[9]/10000);
		$content.="\r\n更新时间：".($tempArray[31]);
		return sprintf($priceTpl,$toUsername,$fromUsername,time(),$content);
		
	}
	//根据公司的ID获取公司的股票代码
	function getCodeById($id){
		$result=mysql_query("select stock_code from company where id=$id");
		if(!$result){
			die("could not connect to the database</br>".mysql_error());
		}
		if($result_row=mysql_fetch_row($result)){
			return $result_row[0];
		}else{
			return "here";
		}
		
	}
?>