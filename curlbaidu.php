<?php
 $curl=curl_init("http://www.baidu.com");
 //创建curl资源 
 curl_exec($curl); //传递给浏览器
 curl_close($curl); //关闭curl释放资源
?>