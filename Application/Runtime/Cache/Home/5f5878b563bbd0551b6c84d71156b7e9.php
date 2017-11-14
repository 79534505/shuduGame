<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>数独游戏</title>
	<link type="text/css" rel="stylesheet" href="/Public/static/css/base.css">
	<link type="text/css" rel="stylesheet" href="/Public/static/css/index.css">
	<script type="text/javascript">
		var SITE_URL  = '';
		//载入函数
		var U = function(url, params) {
			var website = SITE_URL+'/index.php';
			url = url.split('/');
			if(url[0] == '' || url[0] == '@')
				url[0] = '';
			if (!url[1])
				url[1] = 'Index';
			if (!url[2])
				url[2] = 'index';
			website = website+'/'+url[0]+'/'+url[1];
			if(params) {
				params = params.join('&');
				website = website + '&' + params;
			}
			return website;
		};
		// LazyLoad.js(["js/zepto.min.js", "js/z.touch.js","js/z.ajax.js"], function(){});
	</script>
<script type="text/javascript" src="/Public/static/js/model.js"></script>
<script type="text/javascript" src="/Public/static/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/Public/static/js/view.js"></script>
<script type="text/javascript" src="/Public/static/js/rule.js"></script>
<script type="text/javascript" src="/Public/static/js/controller.js"></script>
</head>
<body>
	<div class="content">
		<!--分数-->
		<div class="score_list">
		<p>排行榜</p>
			<!--用户列表-->
		<ul>
			<li>
				<div>用户名</div>
				<div>时间</div>
				<div>排名</div>
			</li>

			<li>
				<div>user</div>
				<div>1:00</div>
				<div>1</div>
			</li>

			<li>
				<div>user</div>
				<div>1:00</div>
				<div>1</div>
			</li>

		</ul>

		</div>

		<!--游戏界面-->
		<div class="game_frame">
			<canvas id="canvas" width="640" height="480"></canvas>
		</div>

		<!--用户信息-->
		<div class="userinfo">
		<p>个人信息</p>
			<ul>
				<li>
					<div>用 户 名 :</div>
					<div>admin</div>
				</li>

				<li>
					<div>当前排名:</div>
					<div>1</div>
				</li>

				<li>
					<div>最好成绩:</div>
					<div>1:00</div>
				</li>

				<li>
					<div>当前时间:</div>
					<div>1:00</div>
				</li>
			</ul>
		</div>

		<!--游戏记录-->
		<div class="game_record">
			<p>游戏记录</p>
			<ul>
				<li>
					<table>
						<tr>
							<td>admin</td>
							<td>09月27日 09:10</td>
							<td>1:00</td>
						</tr>
					</table>
					<!--<div>admin</div>-->
					<!--<div>09月27日 09:10</div>-->
					<!--<div>1:00</div>-->
				</li>
			</ul>
		</div>
	</div>


</body>
<script>
    main();
</script>
</html>