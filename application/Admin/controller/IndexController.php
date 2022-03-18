<?php
namespace Admin\controller;
include __APP__.'Common/function.php';
include __APP__.'Common/Controller.php';
use Common\Controller;

class IndexController extends Controller
{
	public function index()
	{
		$this->assign('test','测试');
        $this->display('Index/index');
	}

	//个人中心
    public function info()
    {
        $info = M('user')->find();
        $this->assign('info',$info);
        $this->display('Index/info');
    }
}
