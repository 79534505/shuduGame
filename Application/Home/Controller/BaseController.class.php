<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{
    protected $_init_user = true;

	public function _initialize()
	{
		// 初始化用户信息
		if($this->_init_user) {
			$this->_init_user();
		}

	}

    private function _init_user()
    {
        $uinfo = cookie('SHUDU_MEMBER');
        if ($uinfo) {
            $uinfo = explode("|", authcode($uinfo));
            $dao = M('member');
            $this->member = $dao->find($uinfo[1]);
            if ($this->member['password'].$this->member['salt'] != $uinfo[2]) {
                // 登录密码有修改过，需要重新登录
                cookie('SHUDU_MEMBER', null);
                exit(0);
            } else {
                $member = $this->member;
                $this->member = $member;
                $this->assign('member', $this->member);
            }
        }
    }
}

?>