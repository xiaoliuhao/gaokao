<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/11/4
 * Time: 22:20
 */
include "../../libraries/curl.class.php";
include "../../libraries/inc.func.php";
include "../../libraries/DB.class.php";
//脚本最大执行时间 0为不限制
ini_set("max_execution_time", "0");
//时区设置
ini_set('date.timezone','Asia/Shanghai');