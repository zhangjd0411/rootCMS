<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Member\Model;

use Common\Model\Model;

class StucateModel extends Model {

    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array('catename', 'require', '课程名称不能为空！')
    );
    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
        array('status','0'),
    );

    /**
     * 根据错误代码返回错误提示
     * @param type $errorCodes 错误代码
     * @return type
     */
    public function getErrorMesg($errorCodes) {
        switch ($errorCodes) {
            case -1:
                $error = '用户名不合法';
                break;
            case -2:
                $error = '包含不允许注册的词语';
                break;
            case -3:
                $error = '用户名已经存在';
                break;
            case -4:
                $error = 'Email 格式有误';
                break;
            case -5:
                $error = 'Email 不允许注册';
                break;
            case -6:
                $error = '该 Email 已经被注册';
                break;
            default:
                $error = '操作出现错误';
                break;
        }

        return $error;
    }

    /**
     * 取得本应用中的用户资料
     * @param type $identifier
     * @param type $field
     * @return boolean
     */
    public function getCateInfo($identifier, $field = '*') {
        if (empty($identifier)) {
            return false;
        }
        $where = array();
        if (is_numeric($identifier) && gettype($identifier) == "integer") {
            $where['id'] = $identifier;
        } else {
            $where['catename'] = $identifier;
        }
        $userInfo = $this->where($where)->field($field)->find();
        if (empty($userInfo)) {
            return false;
        }
        return $userInfo;
    }
    
    public function getCateData(){
        $retarr = array();
        $tmpdata = $this->select();
        if(!empty($tmpdata)){
            foreach($tmpdata as $k => $v){
                $retarr[$v['id']] = array("id"=>$v['id'],"catename"=>$v['catename']);
            }
        }
        return $retarr;
    }
}
