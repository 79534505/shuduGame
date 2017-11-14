<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends BaseController {
    public function index(){
	    //判断是否登录
		if($this->member['id']){
			redirect(U('Index/index'));
		}
	    //获取客户端IP
	    $client_ip = get_client_ip();
	    $this->assign('client_ip',$client_ip);

        $this->display('Login/index');
    }

	/*
	 * 登录验证
	 */
	public function do_login(){
		//获取post提交过来的数据
		$post = I('post.');
		//用封装好的h函数过滤掉恶意的html标签
		$username = trim(h($post['username']));
		$password = trim(h($post['password']));
		//这里再次对用户名密码进行一次检查,防止不是正常提交
		if($username == '' || $password == ''){
			$data = array(
				'status' => false,
				'msg' => '用户账号和密码不能为空!'
			);
			//返回json数据
			$this->ajaxReturn($data);
		}

		//这里开始查询数据库检查用户名是否存在
		$res = M('member')->where(array('username' => $username))->find();
		//用户名不存在的情况
		if(!$res){
			$data = array(
				'status' => false,
				'msg' => '用户名不存在!'
			);
			$this->ajaxReturn($data);
		}

		//检查密码是否正确 输入的密码+注册时候的密佐
		if(md5(md5($password)).$res['salt'] != $res['password']){
			$data = array(
				'status' => false,
				'msg' => '密码不正确!'
			);
			$this->ajaxReturn($data);
		}else{
			//判断用户cookie是否存在
			if(!getCookieUid()) {
				$userinfo = C('SECURE_CODE').'|'.$res['id'].'|'.$res['password'].$res['salt'];
				$expire = 86400 ;
				// 注册cookie 把用户信息进行加密
				cookie('SHUDU_MEMBER', authcode($userinfo,'ENCODE','',$expire));
			}else{
				//cookie中存在用户ID
				$data = array(
					'status' => false,
					'msg' => '数据有误,请刷新后再试!'
				);
				cookie('SHUDU_MEMBER',null);
				$this->ajaxReturn($data);
			}

			//登录成功
			$data = array(
				'status' => true,
				'msg' => '登录成功!'
			);
			$this->ajaxReturn($data);
		}
	}

	/*
	 * 注册处理
	 */
	public function do_register(){
		//获取post提交过来的数据
		$post = I('post.');
		//用封装好的h函数过滤掉恶意的html标签
		$username = trim(h($post['username']));
		$password = trim(h($post['password']));
		$ip = trim($post['client_ip']);

		//这里再次对用户名密码进行一次检查,防止不是正常提交
		if($username == '' || $password == ''){
			$data = array(
				'status' => false,
				'msg' => '用户账号和密码不能为空!'
			);
			//返回json数据
			$this->ajaxReturn($data);
		}

		//这里去查询用户名是否存在
		$res = M('member')->where(array('username' => $username))->count();
		if($res){
			$data = array(
				'status' => false,
				'msg' => '用户名已经存在!'
			);
			$this->ajaxReturn($data);
		}

		//开始进行注册
		$data['username'] = $username;
		$data['password'] = md5(md5($password));
		$data['reg_time'] = time();
		$data['ip'] = $ip;
		$data['salt'] = randomStr();

		$res = M('member')->add($data);
		if($res){
			//写入cookie进行登录
			$userinfo = C('SECURE_CODE').'|'.$res.'|'.md5(md5($password)).$data['salt'];
			$expire = 86400 ;
			// 注册cookie 把用户信息进行加密
			cookie('SHUDU_MEMBER', authcode($userinfo,'ENCODE','',$expire));

			$data = array(
				'status' => true,
				'msg' => '注册成功!'
			);
			$this->ajaxReturn($data);
		}else{
			$data = array(
				'status' => false,
				'msg' => '网络繁忙!'
			);
			$this->ajaxReturn($data);
		}
	}

}