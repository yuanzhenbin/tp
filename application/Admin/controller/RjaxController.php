<?php
include __APP__.'Common/function.php';
include __APP__.'Common/Controller.php';

class RjaxController
{
    public function __construct()
    {
        $this->tp = new Tp();
    }

	public function index()
	{
		echo 'ajax主页';
	}

    public function test()
    {
        if (IS_POST) {

            $count = $data = M('yonghu')->where(['id' => ['gt' , 0]])->count();
            $data = M('yonghu')->where(['id' => ['gt' , 0]])->limit(0,10)->order('id desc')->select();
            exitJson($data,0);
        } else {
            exitJson([],101,'异常请求');
        }
    }
}
