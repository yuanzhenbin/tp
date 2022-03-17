<?php
namespace Home\controller;
include __APP__.'Common/function.php';
include __APP__.'Common/Controller.php';
use Common\Tp;

class IndexController extends Tp
{
	public function index()
	{
        $this->display('Index/index');
	}
}
