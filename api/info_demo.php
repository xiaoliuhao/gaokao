<?php
/**
 * 微校接口调用DEMO
 * 有问题请参考以下地址：
 * http://open.weixiao.qq.com/app/index.html#/api?content=problem
 */
class MediaInfo {
    // 由微校提供
    const API_KEY = 'CA594DE1E12F8E16';
    const API_SECRET = '47A9B7B87A0E85C0B5C56DDD5025B70C';

    public function getInfo() {
        $media_id = 'gh_0b2016dd8cb4';
        $open_url = 'http://weixiao.qq.com/common/get_media_info';

        $param_array = array(
            'media_id' => $media_id,
            'api_key' => self::API_KEY,
            'timestamp' => time(),
            'nonce_str' => $this->genNonceStr(),
        );

        $param_array['sign'] = $this->calSign($param_array);
//        var_dump($param_array);
//        $reponse = $this->post($open_url, json_encode($param_array));
//        echo $json_encode($param_array);
//        echo $reponse;
//        echo json_encode($param_array);
        var_dump($param_array);

        var_dump(json_encode($param_array));
    }

    /**
     * 生成32位随机字符串
     * @return string
     */
    public function genNonceStr() {
        return strtoupper(md5(time() . mt_rand(0, 10000) . substr('abcdefg', mt_rand(0, 7))));
    }

    /**
     * curl post 请求
     * @param string $url
     * @param string $json_data json字符串
     * @return json
     */
    public function post($url, $json_data, $https = true) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * 计算签名
     * @param array $param_array
     * @return string
     */
    public function calSign($param_array) {
        $names = array_keys($param_array);
        sort($names, SORT_STRING);

        $item_array = array();
        foreach ($names as $name) {
            $item_array[] = "{$name}={$param_array[$name]}";
        }

        $str = implode('&', $item_array) . '&key=' . self::API_SECRET;
        return strtoupper(md5($str));
    }
}

$object = new MediaInfo();
$object->getInfo();