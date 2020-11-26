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

            $page = I('page')?I('page'):1;
            $limit = I('limit')?I('limit'):10;
            $firstRow = (($page - 1) * $limit);

            $where = [];
            $where['id'] = ['gt' , 0];

            $count = $data = M('yonghu')->where($where)->count();
            $data = M('yonghu')->where($where)->limit($firstRow,$limit)->order('id desc')->select();
            exit(json_encode(['data' => $data, 'count' => $count, 'code' => 0]));
        } else {
            exitJson([],101,'异常请求');
        }
    }

    public function test_mysqli()
    {
        $sql = "select * from jjrxt_yonghu";
        $mysqli = mysqli_connect('127.0.0.1', 'root', '', 'tets', '3306');
        $data = mysqli_query($mysqli,$sql);
        if($data)
        {
            if(mysqli_num_rows($data))
            {
                $result_array = mysqli_fetch_array($data);
                var_dump($data);die;
            }
        }
        $mysqli->close();

    }
}
