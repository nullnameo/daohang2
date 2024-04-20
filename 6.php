<?php
// 指定请求头地址和 m3u8 链接
$header_url = "http://www.macaulotustv.cc/index.php/index/live.html";
$m3u8_url = "http://live-hls.macaulotustv.com/lotustv/macaulotustv.m3u8";
// 模拟请求头
$headers = array(
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36",
    "Referer: http://www.macaulotustv.cc/",
    "Accept-Language: en-US,en;q=0.9",
);
// 初始化cURL请求
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $m3u8_url,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_REFERER => $header_url,
));
$response = curl_exec($ch);
curl_close($ch);

// 如果无法获取到m3u8内容，则输出错误信息
if (!$response) {
    echo "Failed to fetch m3u8 file";
    exit;
}

// 将相对链接转换为绝对链接
$response = str_replace("\n", "", $response); // 去除换行符
$response = str_replace("\r", "", $response); // 去除回车符
$response = trim($response); // 去除首尾空格

// 如果链接是相对路径，添加基础路径
if (strpos($response, "http") !== 0) {
    $response = dirname($m3u8_url) . "/" . $response;
}

// 检查链接是否有效
$http_response = get_headers($response);
if (!$http_response || strpos($http_response[0], "200") === false) {
    echo "Invalid m3u8 file";
    exit;
}

// 输出链接
echo $response;
?>
