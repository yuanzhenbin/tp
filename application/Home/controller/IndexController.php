<?php
namespace Home\controller;
include __APP__.'Common/function.php';
include __APP__.'Common/Controller.php';
use Common\Controller;

class IndexController extends Controller
{
	public function index()
	{
        $this->display('Index/index');
	}
}
