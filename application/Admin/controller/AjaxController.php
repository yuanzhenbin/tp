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
            $page = I('page',1);
            $limit = I('limit',10);
            $firstRow = (($page - 1) * $limit);

            $where = [];
            $name = I('name');
            $phone = I('phone');
            if ($name) {
                $where['name'] = ['like','%'.$name.'%'];
            }
            if ($phone) {
                $where['phone'] = $phone;
            }

            $count = $data = M('user')->where($where)->count();
            $data = M('user')->field('*')->where($where)->limit($firstRow,$limit)->order('id desc')->select();
            $sex_list = [0=>'未知', 1=>'男', 2=>'女'];
            foreach ($data as $k => $v) {
                $data[$k]['sex_show'] = isset($sex_list[$v['sex']])?$sex_list[$v['sex']]:'未知';
            }
            exit(json_encode(['data' => $data, 'count' => $count, 'code' => 0]));
        } else {
            exitJson([],101,'异常请求');
        }
    }

    public function add()
    {
        $name = I('name');
        $phone = I('phone');
        $account = I('account');
        $password = I('password');

        if ($name && $phone && $account && $password) {
            $check = M('user')->where(['account' => $account])->find();
            if ($check) {
                exitJson([],101,'账号不能重复');
            }

            $add_data = [];
            $add_data['name'] = $name;
            $add_data['phone'] = $phone;
            $add_data['account'] = $account;
            $add_data['password'] = md5($password);
            $add_data['add_time'] = time();
            $result = M('user')->add($add_data);
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
        $id = I('id');
        $name = I('name');
        $phone = I('phone');

        if (!$id) {
            exitJson([], 101, '缺少参数');
        }

        $save_data = [];
        $save_data['name'] = $name;
        $save_data['phone'] = $phone;
        $result = M('user')->where(['id' => $id])->save($save_data);

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
            $result = M('user')->where(['id' => $id])->delete();
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
        $sql = "select * from user";
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
