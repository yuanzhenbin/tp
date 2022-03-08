<?php
namespace Admin\controller;
include __APP__.'Common/function.php';
include __APP__.'Common/Controller.php';
use Common\Tp;

class AjaxController extends Tp
{
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
            $where['id'] = ['gt', 0];

            $count = $data = M('yonghu')->where($where)->count();
            $data = M('yonghu')->field('*')->where($where)->limit($firstRow,$limit)->order('id desc')->select();
            exit(json_encode(['data' => $data, 'count' => $count, 'code' => 0]));
        } else {
            exitJson([],101,'异常请求');
        }
    }

    public function add()
    {
        $name = I('name');
        $id = I('id');

        if ($name && $id) {
            $add_data = ['ygmingcheng' => $name,'id'=> $id];
            $result = M('yonghu')->add($add_data);
        } else {
            exitJson([],101,'缺少参数');
        }

        if ($result) {
            exitJson([],200,'添加成功');
        } else {
            exitJson([],101,'添加失败');
        }
    }

    public function update()
    {
        $name = I('name');
        $id = I('id');

        if ($name && $id) {
            $save_data = [];
            $save_data['ygmingcheng'] = $name;
            $result = M('yonghu')->where(['id' => $id])->save($save_data);
        } else {
            exitJson([],101,'缺少参数');
        }

        if ($result) {
            exitJson([],200,'修改成功');
        } else {
            exitJson([],101,'修改失败');
        }
    }

    public function delete()
    {
        $id = I('id');

        if ($id) {
            $result = M('yonghu')->where(['id' => $id])->delete();
        } else {
            exitJson([],101,'缺少参数');
        }

        if ($result) {
            exitJson([],200,'删除成功');
        } else {
            exitJson([],101,'删除失败');
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
