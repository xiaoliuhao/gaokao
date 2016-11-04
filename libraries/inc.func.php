<?php
/**
 * Created by PhpStorm.
 * User: Liu
 * Date: 2016/11/4
 * Time: 20:02
 */
function tips($str){
    for($j = 1; $j <= 4096; $j++ ) print(' ');
    echo '<br>'.$str.'<br>';
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
        $str .= " {$key}:{$value} ";
    }

    $str .= "错误".date('Y-m-d H:i:s')."\r\n";
    $data = fwrite($file,$str);
    fclose($file);
}