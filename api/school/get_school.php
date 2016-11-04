<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/11/4
 * Time: 17:02
 */
include "./init.php";

ini_set("max_execution_time", "0");
ini_set('memory_limit','512M');
$stime = microtime(true);

$tool = new Tool('http://data.api.gkcx.eol.cn/soudaxue/queryschool.html?messtype=jsonp&province=&schooltype=&page=1&size=10&keyWord1=&schoolprop=&schoolflag=&schoolsort=&schoolid=&callback=jQuery18307330200179406006_1478249781451&_=1478249794835');
$db   = new DB('115.159.54.210', 'Liu', 'qq470401911', 'gaokao');

$conn = $db->getConn();

$data_arr = array();


for($i = 1; $i <= 275; $i++) {
    $start_time=microtime(true);

    $url  = "http://data.api.gkcx.eol.cn/soudaxue/queryschool.html?messtype=json&schooltype=&page={$i}&size=10";
    $data = $tool->cURL($url);

    $file_path = "./json/school_data{$i}.json";
    write_data($file_path, $data);

    $data_tmp = json_decode($data);
    $data_arr[] = $data_tmp->school;

    $end_time=microtime(true);//获取程序执行结束的时间
    $total=$end_time-$start_time;   //计算差值
    $full = $end_time - $stime;
    $get = $i*10;
    $str = "正在获取数据:已完成({$get}/2750).用时{$total}秒. 共用时{$full}秒";
    tips($str);
}

write_data("./data_school.json",json_encode($data_arr));

$num = 0;
foreach($data_arr as $schools){
    $start_time=microtime(true);

    $school = object_to_array($schools);
    $num += my_insert($db, 'gk_schools', $school, $conn);

    $end_time=microtime(true);//获取程序执行结束的时间
    $total=$end_time-$start_time;   //计算差值
    $full = $end_time - $stime;
    $str = "正在存入数据:已完成($num/2750).{$total}秒.共{$full}秒";
    tips($str);
}

function object_to_array($obj){
    $_arr = is_object($obj) ? get_object_vars($obj) :$obj;
    $arr = array();
    foreach ($_arr as $key=>$val){
        $val = (is_array($val) || is_object($val)) ? object_to_array($val):$val;
        $arr[$key] = $val;
    }
    return $arr;
}

function write_data($file_path, $json){
    $file=fopen($file_path,"a+");
    fwrite($file,$json);
    fclose($file);
}

//echo json_encode($data_arr);