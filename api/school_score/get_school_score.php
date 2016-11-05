<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/11/4
 * Time: 22:03
 */
include "./init.php";
$stime = microtime(true);
$tool = new Tool('http://data.api.gkcx.eol.cn');
$db   = new DB('115.159.54.210', 'Liu', 'qq470401911', 'gaokao');
$conn = $db->getConn();

//大学录取分数线
$url = "http://data.api.gkcx.eol.cn/soudaxue/queryProvinceScore.html?messtype=json&url_sign=queryProvinceScore&page=1&size=50&province=内蒙古&fsyear=";

$all_data = array();
$j = 0;
for($year=2013 ; $year<=2015; $year++) {
    $all_data[$year] = array();
    $foreach_times = get_search_time($tool, $url, $year);
    for ($i = 1; $i <= $foreach_times; $i++) {
        $url = "http://data.api.gkcx.eol.cn/soudaxue/queryProvinceScore.html?messtype=json&url_sign=queryProvinceScore&page={$i}&size=50&province=内蒙古&fsyear={$year}";
        $content = $tool->cURL($url);
        $total_data = json_decode($content);
        $total_data2 = object_to_array($total_data);
        $all_data[$year][] = @$total_data2['school'];

        $file_tmp = "./data/data_{$year}_{$j}.json";
        write_data($file_tmp , $content);

        $j++;

        $time1 = microtime(true);
        $tmp = $time1-$stime;

        $str1 = "{$year}年:({$i}/{$foreach_times}),已执行{$tmp}";
        tips($str1);
    }
    $end_time=microtime(true);//获取程序执行结束的时间
    $full = $end_time - $stime;
    $str = "------------已成功获取{$year}年的数据,已经用时{$full}秒";
//    tips($str);
}

//echo $j;

//echo json_encode($all_data);

write_data('./school_score.json',json_encode($all_data));

$num=0;
foreach ($all_data as $key => $data_year) {
    foreach ($data_year as $schools) {
        $start_time = microtime(true);

        $school = object_to_array($schools);
        $num += my_insert($db, 'gk_school_score', $school, $conn);

        $end_time = microtime(true);//获取程序执行结束的时间
        $total = $end_time - $start_time;   //计算差值
        $full = $end_time - $stime;
        $all_nums = $j*50;
        $str = "----正在存入数据:已完成({$num}/{$all_nums}).{$total}秒.共{$full}秒";
        tips($str);
    }
}



function get_search_time($tool, $url, $year){
    $content = $tool->cURL($url);
    $data = json_decode($content);
    $all  = $data->totalRecord->num;
    $foreach_times = (int)($all/50)+1;
    return $foreach_times;
}


/*
 * {
    "schoolid": "46",                   //学校id
    "schoolname": "中国人民大学",        //学校名字
    "localprovince": "内蒙古",           //生源地
    "studenttype": "文科",                //考生类别
    "year": "2014",                     //年份
    "batch": "本科一批",                //录取批次
    "var": "626",                       //平均分
    "var_score": "626",                 //平均分
    "max": "--",                        //最高分
    "min": "--",                        //最低分
    "num": "0",                         //
    "fencha": "101",                    //分差
    "provincescore": "525",             //省控线
    "url": "http://gkcx.eol.cn/schoolhtm/schoolAreaPoint/46/10002/10034/10036.htm?630044"
}
 * */
