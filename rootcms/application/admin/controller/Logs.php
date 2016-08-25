<?php

// +----------------------------------------------------------------------
// | rootCMS 网站后台日志管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminBase;

class Logs extends AdminBase {

    //后台登陆日志
    public function loginlog() {
        if (IS_POST) {
            $this->redirect('loginlog', $_POST);
        }
        $where = array();
        $username = input('username');
        $start_time = input('start_time');
        $end_time = input('end_time');
        $loginip = input('loginip');
        $status = input('status');
        if (!empty($username)) {
            $where['username'] = array('like', '%' . $username . '%');
        }
        if (!empty($start_time) && !empty($end_time)) {
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time) + 86399;
            $where['logintime'] = array(array('GT', $start_time), array('LT', $end_time), 'AND');
        }
        if (!empty($loginip)) {
            $where['loginip '] = array('like', "%{$loginip}%");
        }
        if ($status != '') {
            $where['status'] = $status;
        }
        $model =  model("admin/Loginlog");
        $count = $model->where($where)->count();
        $page = $this->page($count, 20);
        $data = $model->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array('id' => 'DESC'))->select();
        $this->assign("Page", $page->show())
                ->assign("data", $data)
                ->assign('where', $where)
                ->display();
    }

    //删除一个月前的登陆日志
    public function deleteloginlog() {
        if ( model("admin/Loginlog")->deleteAMonthago()) {
            $this->success("删除登陆日志成功！");
        } else {
            $this->error("删除登陆日志失败！");
        }
    }

    //操作日志查看
    public function index() {
        if (IS_POST) {
            $this->redirect('index', $_POST);
        }
        $uid = input('uid');
        $start_time = input('start_time');
        $end_time = input('end_time');
        $ip = input('ip');
        $status = input('status');
        $where = array();
        if (!empty($uid)) {
            $where['uid'] = array('eq', $uid);
        }
        if (!empty($start_time) && !empty($end_time)) {
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time) + 86399;
            $where['time'] = array(array('GT', $start_time), array('LT', $end_time), 'AND');
        }
        if (!empty($ip)) {
            $where['ip '] = array('like', "%{$ip}%");
        }
        if ($status != '') {
            $where['status'] = (int) $status;
        }
        $count = M("Operationlog")->where($where)->count();
        $page = $this->page($count, 20);
        $Logs = M("Operationlog")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(array("id" => "desc"))->select();
        $this->assign("Page", $page->show());
        $this->assign("logs", $Logs);
        $this->display();
    }

    //删除一个月前的操作日志
    public function deletelog() {
        if ( model("admin/Operationlog")->deleteAMonthago()) {
            $this->success("删除操作日志成功！");
        } else {
            $this->error("删除操作日志失败！");
        }
    }

}