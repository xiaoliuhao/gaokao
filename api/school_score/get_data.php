<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/11/5
 * Time: 16:58
 */
include "./init.php";
$stime = microtime(true);
$tool = new Tool('http://data.api.gkcx.eol.cn');
$db   = new DB('115.159.54.210', 'Liu', 'qq470401911', 'gaokao');
$conn = $db->getConn();

$content = file_get_contents('./school_score.json');

$all_data = json_decode($content);
$num=0;
$j = 358;
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
