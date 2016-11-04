<?php
class DB{
    private static $conn;
    private $host;
    private $user;
    private $pwd;
    private $dbname;
    function __construct($host,$user,$pwd,$dbname){
        $this->host = $host;
        $this->user = $user;
        $this->pwd  = $pwd;
        $this->dbname = $dbname;
    }

    public function getConn(){
        self::$conn=@mysql_connect($this->host,$this->user,$this->pwd);
        if(self::$conn){
            mysql_select_db($this->dbname,self::$conn);
            mysql_query("set names utf8");
            return self::$conn;
        }else{
            throw new Exception("数据库服务器连接错误".mysql_error());
        }
    }

    public function select($sql,$conn){
        $result = mysql_query($sql,$conn);
        $i = 0;
        while($row = mysql_fetch_array($result)){
            $data[] = $row[0];
            $i++;
        }
        return $data;
    }

    public function insert($table,$array = array(),$conn){
        if(empty($array)){
            return 0;
        }
        $sql = "INSERT INTO $table ";
        $keys = "(";
        $values = "VALUES(";
        foreach ($array as $key => $value) {
            $keys 	.= "$key".",";

            if(is_array($value)){
                $value_tmp = '';
                foreach($value as $value2){
                    $value_tmp .= "$value2";
                }
                $values .= "'" . "$value_tmp" . "',";
            }else {
                $values .= "'" . "$value" . "',";
            }
        }
        $keys 	= substr($keys, 0, -1).") ";
        $values 	= substr($values, 0, -1).")";
        $sql = $sql.$keys.$values;
//        $sql 	= mysql_real_escape_string($sql.$keys.$values);
//        echo $sql.'<br>';
        $result = mysql_query($sql,$conn);

        return $result;
    }
}