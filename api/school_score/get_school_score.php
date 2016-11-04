<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/11/4
 * Time: 22:03
 */
include "./init.php";
$tool = new Tool('http://data.api.gkcx.eol.cn');
$db   = new DB('115.159.54.210', 'Liu', 'qq470401911', 'gaokao');
$conn = $db->getConn();


//大学录取分数线
$url = "http://data.api.gkcx.eol.cn/soudaxue/queryProvinceScore.html?messtype=jsonp&url_sign=queryProvinceScore&page=1&size=50&province=内蒙古&fsyear=2015&callback=jQuery183009280941664946574_1478267670060&_=1478267681103";

$data = $tool->cURL($url);

var_dump($data);
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
