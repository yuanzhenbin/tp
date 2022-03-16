<?php
require __ROOT__.'config/config.php';
include __APP__.'Common/Model.php';

function I($name = null,$default = null)
{
    if ($name) {
        if (IS_POST) {
            $result = $_POST[$name];
        } else if (IS_GET) {
            $result = $_GET[$name];
        } else {
            return $default;
        }
        if (!$result && $default) {
            $result = $default;
        }
        return $result;
    } else {
        if (IS_POST) {
            $result = $_POST;
        } else if (IS_GET) {
            $result = $_GET;
        }
        if (!$result && $default) {
            $result = $default;
        }
        return $result;
    }
}

function exitJson($data = [], $code = 200, $message = '')
{
    exit(json_encode(['data' => $data, 'code' => $code, 'message' => $message]));
}

function IS_POST()
{
    if( $_SERVER['REQUEST_METHOD'] === 'POST'){
        return true;
    } else{
        return false;
    }
}

function IS_GET()
{
    if( $_SERVER['REQUEST_METHOD'] === 'GET'){
        return true;
    } else{
        return false;
    }
}

//连接数据库
function M($tableName = '', $connect = [])
{
    $db = new Model($tableName,$connect);
    return $db;
}

//记录日志
function writeLog($message, $tip = 'warning', $path = '')
{
    if (!is_string($message)) {
        $message = json_encode($message,JSON_UNESCAPED_UNICODE|JSON_FORCE_OBJECT);
        if (!is_string($message)) {
            $message = '此处日志非字符串或数组格式，写入错误';
        }
    }
    if (!is_string($tip)) {
        $tip = 'warning';
    }
    $message = date('Y-m-d H:i:s')."[".$tip."]:\r\n".$message."\r\n";

    $log_name = 'log_'.date('y_m_d',time()).'.log';
    if (!$path) {
        $log_path = $_SERVER["DOCUMENT_ROOT"] . '/application/Runtime/Logs';
    } else {
        $log_path = $_SERVER["DOCUMENT_ROOT"] . $path;
    }
    if (!is_dir($log_path)) {
        mkdir($log_path, 0777, true);
    }

    $file_open = fopen($log_path.'/'.$log_name,'a+');
    fwrite($file_open,$message);
}