<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/9/12
 * Time: 10:04
 */
class Tool {
    private $base_url;
    private $short_url;
    private $url;
    private $content;
    private $detail_url;

    public function __construct($base_url='', $short_url=''){
        $this->base_url = $base_url;
        $this->short_url = $short_url;
        $this->url = $base_url.$short_url;
    }

    public function __get($key){
        return $this->$key;
    }

    public function __set($key, $value){
        $this->$key = $value;
    }

    public function add_params(array $params){
        $str = '';
        foreach($params as $key => $value){
            $str .= '&'.$key.'='.$value;
        }
        $this->short_url = substr($str, 1);
        return $this->url = $this->base_url.'?'.$this->short_url;
    }

    public function cURL($url){
        //设置代理
        $headers = array(
            'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0'
        );
        //初始化一个cURL
        $ch = curl_init();
        //设置url和相应的选项
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //执行cURL
        curl_exec($ch);
        //获取抓到的内容
        $content = curl_multi_getcontent($ch);
        //关闭cURL 并释放系统资源
        curl_close($ch);

        return $content;
    }

    public function c_post($data=array()) {
        $headers = array(
//            'Host: http://hongyan.cqupt.edu.cn',
            'Client-Ip: 202.202.43.125',
            'Referer:http://hongyan.cqupt.edu.cn/MagicLoop/index.php?s=/addon/CourseTable/CourseTable/index/openid/ouRCyjhDftiBIjtGRw85Cp4mqwJk/token/gh_68f0a1ffc303',
            'Origin:http://hongyan.cqupt.edu.cn',
            'X-Requested-With:XMLHttpRequest'
        );

        $url = $this->url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_REFERER, 'http://hongyan.cqupt.edu.cn');
        curl_setopt($ch, CURLOPT_HEADER, 0 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
        $content = curl_exec ( $ch );
        curl_close ( $ch );

        return $content;
    }
    
    public function resetContent($content){
        $result = explode("\r\n", $content);
        $str = '';
        foreach ($result as $value){
            $str .= $value;
        }
        return $this->content = $str;
    }

    public function match($search, $content, $result){
        preg_match_all($search, $content, $result);
        return $result;
    }
}