<?php
require __ROOT__.'config/config.php';
include __APP__.'Common/Model.php';

function I($name = null,$default = null)
{
    if ($name) {
        if (IS_POST) {
            return $_POST[$name];
        } else if (IS_GET) {
            return $_GET[$name];
        } else {
            return $default;
        }
    } else {
        if (IS_POST) {
            return $_POST;
        } else if (IS_GET) {
            return $_GET;
        } else {
            return $default;
        }
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

//连接数据库
function writeLog($message, $path = [])
{

}