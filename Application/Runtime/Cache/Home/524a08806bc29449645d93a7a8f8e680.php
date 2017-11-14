<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>数独游戏注册</title>
<meta name="author" content="DeathGhost" />
<link rel="stylesheet" type="text/css" href="/Public/static/css/style.css" tppabs="css/style.css" />
<style>
body{height:100%;background:#16a085;overflow:hidden;}
canvas{z-index:-1;position:absolute;}
</style>
<script src="/Public/static/js/jquery.js"></script>
<script src="/Public/static/js/verificationNumbers.js" tppabs="/Public/static/js/verificationNumbers.js"></script>
<script src="/Public/static/js/Particleground.js" tppabs="/Public/static/js/Particleground.js"></script>
<script>
$(document).ready(function() {
  //粒子背景特效
  $('body').particleground({
    dotColor: '#5cbdaa',
    lineColor: '#5cbdaa'
  });
  //提交
  $(".submit_btn").click(function(){
	  location.href="javascrpt:;"/*tpa=http://***index.html*/;
	  });
});
</script>
</head>
<body>
<dl class="admin_login">
 <dt>
  <strong>数独游戏注册</strong>
 </dt>
 <dd class="user_icon">
  <input type="text" placeholder="账号" class="login_txtbx"/>
 </dd>
 <dd class="pwd_icon">
  <input type="password" placeholder="密码" class="login_txtbx"/>
 </dd>
 <dd>
  <input type="button" value="立即注册" class="submit_btn"/>
 </dd>
 <dd>
 </dd>
</dl>
</body>
</html>