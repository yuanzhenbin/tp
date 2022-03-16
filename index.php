<?php
//设置常用常量
define("DS", DIRECTORY_SEPARATOR);
define("__ROOT__", str_replace("\\","/",__DIR__).'/');
define("__APP__", __ROOT__.'application/');
define("__PROJECT__", __ROOT__.'application/');
define("__STATIC__", __ROOT__.'public/static');
define("__CSUFFIX__", 'Controller');
define("REQUEST_METHOD", $_SERVER['REQUEST_METHOD']);

define('__HTTP__', 'http');
define('__URL__', __HTTP__.'://'.$_SERVER['SERVER_NAME']);
define('APP_PATH', __URL__.'/application');
define('__PUBLIC__', __URL__.'/public');

$module_restrictions = array('Admin', 'Home');

//默认请求路径
$module = "Admin";
$controller = "Index";
$action = "index";

//获取网页地址
define("__WEBSITE__", $_SERVER['HTTP_HOST']);
define("__PATHPREFIX__", $_SERVER['HTTP_HOST'].'/index.php');
$getPath = explode('/', $_SERVER['PHP_SELF']);

//获取访问的模块、控制器、方法
if(!empty($_GET["m"])) {
    $module = $_GET["m"];
} else if (!empty($getPath[2])){
    $module = $getPath[2];
}
if(!empty($_GET["c"])) {
    $controller = $_GET["c"];
} else if (!empty($getPath[3])){
    $controller = $getPath[3];
}
if(!empty($_GET["a"])) {
    $action = $_GET["a"];
} else if (!empty($getPath[4])){
    $action = $getPath[4];
}

//控制允许访问的模块
if (!in_array($module,$module_restrictions)) {
    $module = 'Home';
}

define("__MODULE__", $module);
//缓存与日志路径
define("__CACHE__", __APP__.'Runtime/Cache/'.$module);
define("__LOGS__", __APP__.'Runtime/Logs/'.$module.strtotime('Y-m-d', time()));

include __APP__.$module."/Controller/".$controller.__CSUFFIX__.".php";

//应用初始化常量
define("IS_POST", REQUEST_METHOD == 'POST'? true : false);
define("IS_GET", REQUEST_METHOD == 'GET'? true : false);

$controller_name = '\\'.$module.'\\controller\\'.$controller."Controller";

$ctrl = new $controller_name();
$ctrl->$action();

