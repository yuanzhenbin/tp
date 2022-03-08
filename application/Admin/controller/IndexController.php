<?php
namespace Admin\controller;
include __APP__.'Common/function.php';
include __APP__.'Common/Controller.php';
use Common\Tp;

class IndexController extends Tp
{
	public function index()
	{
		$this->assign('test','测试');
        $this->display('Index/index.html');
	}
}
