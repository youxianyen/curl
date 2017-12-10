<?php
/*CURL 是利用URL语法在命令行方式下工作的开源文件传输工具，他能够从互联网上获取各种资源。简单来说，curl就是抓取页面的升级版。
最简单的一个模型如下：
1.curl初始化。
2.配置相关curl参数。
3.读取页面，返回数据。
4.关闭curl。
*/
 //1.初始化，创建一个新cURL资源
 $ch = curl_init(); 
//2.设置URL和相应的选项
 curl_setopt($ch, CURLOPT_URL, "http://www.baidu.com/");
 
curl_setopt($ch, CURLOPT_HEADER, 0); 
//3.抓取URL并把它传递给浏览器
 curl_exec($ch); 
//4.关闭cURL资源，并且释放系统资源
 curl_close($ch); 