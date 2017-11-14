<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
	    //判断是否登录
	    if(!$this->member['id']){
		    redirect(U('Login/index'));
	    }
	    //排行榜
	    $rank_list = M('rank')->order('rank asc')->limit(10)->select();
	    //迭代
	    foreach($rank_list as &$val){
		    //时间转换
			$val['game_time'] = $this->swithc_time($val['game_time']);
	    }
	    //断开指针
	    unset($val);

		//个人信息
	    $user_info = array();
		$user_rank = M('rank')->where(array('uid' => $this->member['id']))->find();//这里取排名信息
	    $user_info['game_time'] = $this->swithc_time($user_rank['game_time']);
		$user_info['rank'] = $user_rank['rank'] ? $user_rank['rank'] : 0;
	    $user_info['count'] = M('list')->where(array('uid' => $this->member['id']))->count(); //这里取游戏次数

	    //游戏记录
		$game_list = M('list')->order('time desc')->limit(10)->select();
	    //迭代
		foreach($game_list as &$val){
			//时间转换
			$val['time'] = friendlyDate($val['time']);
			$val['game_time'] = $this->swithc_time($val['game_time']);
		}
	    unset($val);

	    //获取客户端IP
	    $client_ip = get_client_ip();
	    $this->assign('client_ip',$client_ip);
	    //获取user_agent
	    $user_agent= strtolower($_SERVER['HTTP_USER_AGENT']);
	    $this->assign('user_agent',$user_agent);

	    //变量输出
	    $this->assign('rank_list',$rank_list);
	    $this->assign('user_info',$user_info);
	    $this->assign('game_list',$game_list);
	    //渲染模板
        $this->display();
    }

	/*
	 * 保存游戏记录
	 */
	public function ajax_game(){
		//获取post提交的数据
		$game_time = I('post.game_time',0,'intval');
		$client_ip = I('post.client_ip','','trim');
		$user_agent = I('post.user_agent','','trim');

		//判断是否登录
		if(!$this->member['id']){
			$data = array(
				'status' => false,
				'msg' => '登录信息有误,请重新登录!'
			);
			$this->ajaxReturn($data);
		}

		//存入数据库
		$data['username'] = $this->member['username'];
		$data['uid'] = $this->member['id'];
		$data['time'] = time();
		$data['game_time'] = $game_time;
		$data['ip'] = $client_ip;
		$data['user_agent'] = $user_agent;
		$res = M('list')->add($data);
		if($res){
			//计算这次游戏排名
			$result = $this->calc_rank($game_time);
			if(!$result['status']){
				$this->ajaxReturn($result);
			}

			$data = array(
				'status' => true,
				'msg' => '恭喜您，顺利过关!'
			);
			$this->ajaxReturn($data);
		}else{
			$data = array(
				'status' => false,
				'msg' => '网络繁忙,请刷新有重试!'
			);
			$this->ajaxReturn($data);
		}

	}

	/*
	 * 计算排名函数
	 */
	private function calc_rank($game_time){
		//查询用户是否存在排行榜上
		$res = M('rank')->where(array('uid' => $this->member['id']))->find();
		if($res){
		//存在排行榜
			//当前成绩是否高于排行榜成绩
			if(intval($res['game_time']) > intval($game_time)){
				//更新到排行榜
				$result = M('rank')->where(array('uid' => $this->member['id']))->save(array('game_time' => intval($game_time)));
				if(!$result){
					$data = array(
						'status' => false,
						'msg' => '更新游戏成绩失败!'
					);
					return $data;
				}
			}
		}else{
			//不存在排行榜增加一条数据
			$data =array(
				'uid' => $this->member['id'],
				'username' => $this->member['username'],
				'game_time' => $game_time
			);
			$result = M('rank')->add($data);
			if(!$result){
				$data = array(
					'status' => false,
					'msg' => '更新游戏成绩失败!'
				);
				return $data;
			}
		}

		//重新计算排名
		$this->up_rank();
	}

	/*
	 * 更新名次
	 */
	private function up_rank(){
		//查询排行榜数据
		$rank_list = M('rank')->order('game_time asc')->select();
		//迭代数据
		foreach($rank_list as $key => $val){
			//更新名次
			M('rank')->where(array('id' => $val['id']))->save(array('rank' => intval($key) + 1));
		}
	}

	/*
	 * 把时间转换为时分秒
	 */
	private  function swithc_time($time){
		$hour = intval($time/60/60) <= 0 ? '00' : intval($time/60/60);
		$minute = intval($time/60 % 60) <= 0 ? '00' : intval($time/60 % 60);
		$second = intval($time%60) <= 0 ? '00' : intval($time%60);
		if(strlen($hour) == '1'){
			$hour = '0'.$hour;
		}
		if(strlen($minute) == '1'){
			$minute = '0'.$minute;
		}
		if(strlen($second) == '1'){
			$second = '0'.$second;
		}
		if($hour == '00'){
			return $minute.':'.$second;
		}else{
			return $hour.':'.$minute.':'.$second;
		}

	}


}