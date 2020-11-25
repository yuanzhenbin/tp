<?php
include __APP__.'Common/function.php';
include __APP__.'Common/Controller.php';

class IndexController
{
    public function __construct()
    {
        $this->tp = new Tp();
    }

	public function index()
	{
		$this->tp->assign('test','测试');
        $this->tp->display('Index/index.html');
	}
}
