<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/11/4
 * Time: 20:02
 */
function tips($str){
    for($j = 1; $j <= 4096; $j++ ) print(' ');
    echo '<br>'.$str.date('Y-m-d H:i:s').'<br>';
    ob_flush();
    flush();
}

function my_insert($db_driver, $table, $school_arr = array(), $conn){
    $insert_num = 0;
    foreach ($school_arr as $school){
        $result = $db_driver->insert($table, $school, $conn);
        if ($result){
            $insert_num++;
        }else{
            write_error($school);
        }
    }
    return $insert_num;
}

function write_error($data = array()){
    $file_path = '../../error/error_log.txt';
    $file=fopen($file_path,"a+");
    $str = '添加';

    foreach($data as $key => $value) {

        if(is_array($value)){
            $value_tmp = '';
            foreach($value as $value2){
                $value_tmp .= "$value2";
            }
            $str .= " {$key}:{$value_tmp} ";
        }else {
            $str .= " {$key}:{$value} ";
        }
    }

    $str .= "错误".date('Y-m-d H:i:s')."\r\n";
    $data = fwrite($file,$str);
    fclose($file);
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