<?php
	header("Content-type: text/html; charset=UTF-8");
	$AppID='wx617cebc36f07f558';
	$AppSecret='ed3f8afe3655a8d2cd4bfefaac55115a';
	$token_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$AppID."&secret=".$AppSecret;
	$access_token=getAccessToken($token_url);
	$result=createMenu($access_token);
	echo $result; 
	
	function getAccessToken($token_url){
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $token_url);  
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");  
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
      //   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');  
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
      curl_setopt($ch, CURLOPT_AUTOREFERER, 1);  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
      $tmpInfo = curl_exec($ch);  
       if (curl_errno($ch)) {  
        echo curl_error($ch);  
			exit;
       }  
	   curl_close($ch);  
	   $tempResult=json_decode($tmpInfo)->access_token;
	   return $tempResult;
	}
	
	function createMenu($access_token){
		$post_data='{
			"button":[
			{	
				"type":"click",
				"name":"行情",
				"key":"MENU_PRICE"
			},
			
			{	
				"type":"click",
				"name":"随机推荐",
				"key":"MENU_RECOMMEND"
			},
			{
				"name":"更多",
				"sub_button":[
				{	
					"type":"click",
					"name":"下载",
               "key":"SUNMENU_DOWNLOAD"
            },
            {
               "type":"click",
               "name":"背景",
               "key":"SUNMENU_BACKGROUND"
            },
            {
               "type":"click",
               "name":"所有公司",
               "key":"SUBMENU_ALL"
            }]
			}]
		}';
		
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token);  
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
      //   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');  
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
      curl_setopt($ch, CURLOPT_AUTOREFERER, 1);  
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
      $tmpInfo = curl_exec($ch);  
       if (curl_errno($ch)) {  
        echo curl_error($ch);  
			exit;
       }  
          
       curl_close($ch);   
       return $tmpInfo;
		
	}
	
?>