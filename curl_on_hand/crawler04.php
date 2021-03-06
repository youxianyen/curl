
<?php
/**
 * 慕课网视频教学
 * 代码实例-PHP-cURL实战
 * 实例描述：登录慕课网并下载个人空间页面
 * 自定义实现页面链接跳转抓取
 * 
 */
$data='username=demo_peter@126.com&password=123qwe&remember=1';
$curlobj = curl_init();			// 初始化
curl_setopt($curlobj, CURLOPT_URL, "http://www.imooc.com/user/login");		// 设置访问网页的URL
curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);			// 执行之后不直接打印出来

// Cookie相关设置，这部分设置需要在所有会话开始之前设置
date_default_timezone_set('PRC'); // 使用Cookie时，必须先设置时区
curl_setopt($curlobj, CURLOPT_COOKIESESSION, TRUE); 
curl_setopt($curlobj, CURLOPT_HEADER, 0); 
// 注释掉这行，因为这个设置必须关闭安全模式 以及关闭open_basedir，对服务器安全不利
//curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1);  

curl_setopt($curlobj, CURLOPT_POST, 1);  
curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);  
curl_setopt($curlobj, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded; charset=utf-8", 
	"Content-length: ".strlen($data)
	)); 
curl_exec($curlobj);	// 执行
curl_setopt($curlobj, CURLOPT_URL, "http://www.imooc.com/space/index");
curl_setopt($curlobj, CURLOPT_POST, 0);  
curl_setopt($curlobj, CURLOPT_HTTPHEADER, array("Content-type: text/xml"
	)); 
$output=curl_redir_exec($curlobj);	// 执行
curl_close($curlobj);			// 关闭cURL
echo $output;

/**
 * 自定义实现页面链接跳转抓取
 */
function curl_redir_exec($ch,$debug="") 
{ 
    static $curl_loops = 0; 
    static $curl_max_loops = 20; 

    if ($curl_loops++ >= $curl_max_loops) 
    { 
        $curl_loops = 0; 
        return FALSE; 
    } 
    curl_setopt($ch, CURLOPT_HEADER, true); // 开启header才能够抓取到重定向到的新URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $data = curl_exec($ch); 
    // 分割返回的内容
    $h_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE); 
    $header = substr($data,0,$h_len);
    $data = substr($data,$h_len - 1);

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    if ($http_code == 301 || $http_code == 302) { 
        $matches = array(); 
        preg_match('/Location:(.*?)\n/', $header, $matches); 
        $url = @parse_url(trim(array_pop($matches))); 
        // print_r($url); 
        if (!$url) 
        { 
            //couldn't process the url to redirect to 
            $curl_loops = 0; 
            return $data; 
        } 
        $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)); 
        if (!isset($url['scheme'])) 
            $url['scheme'] = $last_url['scheme']; 
        if (!isset($url['host'])) 
            $url['host'] = $last_url['host']; 
        if (!isset($url['path'])) 
            $url['path'] = $last_url['path'];

        $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . (isset($url['query'])?'?'.$url['query']:''); 
        curl_setopt($ch, CURLOPT_URL, $new_url); 

        return curl_redir_exec($ch); 
    } else { 
        $curl_loops=0; 
        return $data; 
    } 
} 
?>