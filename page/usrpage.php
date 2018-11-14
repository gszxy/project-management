<?php
//用户个人信息页！
include_once '../utility/user/user.php';
use WebsiteUser\User;
$user = new User();
$info = $user->get_user_info();


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title id="title_name">用户</title>
<meta charset="UTF-8">
<link href="/page/css/out/bootstrap.css" rel="stylesheet" type="text/css">
	<script src="JavaScript/out/jquery-1.11.3.min.js"></script>
	<link href="/page/css/common.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
	.div-a{ float:left;border:1px } 
	.div-b{ float:left;border:1px }
	.div-c{ float:left;border:1px }
	
	.button {
    background-color:#5DD9FF;
    border:thick;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
	
div {
	background-color: lightgrey;
	width: 200px;
	margin: 85px;
	border: 4px solid #a1a1a1;
	padding: 5px 5px 5px 5px;  
	background:#CC9900;
	border-radius: 5px;

}
ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
	background-color: #69C;
	list-style-type: none;
	margin: 0;
	padding: 0;
}
li {
	float: left;
}
li a {
	display: block;
	color: white;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
}

.STYLE3 {
	font-size: 24px;
	font-weight: bold;
}
.STYLE4 {font-size: 36px}
.STYLE5 {
	text-align: center;
	font-size: 18px;
	font-weight: bold;
}
	td{
		text-align: center;
	}
</style>


</head>
<body>
<ul>
  <li><a href="main.html" class="active"><strong>主页</strong> </a></li>
  <li><a href="">用户</a></li>
  <li><a href="">任务</a></li>
  <li><a href="">项目进度</a></li>
  <li><a href="">业绩</a></li>
	<li hidden="hidden"><a href="">管理</a></li><!--仅对管理员可见。通过javascript取消隐藏-->
	<li style="float:right" id="txt_username"></li>
  <li style="float:right" id="btn_logout"><a>退出</a></li>
  
</ul>
<title></title>
<h1 align="center" style="font-family:Arial, Helvetica, sans-serif;font-size:55px;color:#3399FF">User Page &nbsp;</h1>
<p>
</p>
<table width="497" height="399" border="4" align="center">
  <caption class="STYLE3 STYLE4">
    用户信息
  </caption>
  <tr>
    <td width="155"><span class="STYLE5">Username </span></td>
    <td width="275"><span class="STYLE5"><?php echo $info['name']; ?></span></td>
  </tr>
  <tr>
    <td><span class="STYLE5">性别</span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="STYLE5">用户id </span></td>
    <td align="center"><?php echo $info['id']; ?></td>
  </tr>
  <tr>
    <td><p class="STYLE5">email</p>    </td>
    <td><?php echo $info['email']; ?></td>
  </tr>
  <tr>
    <td><span class="STYLE5">Class Number </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="STYLE5">个人简介 </span></td>
    <td colspan="2"><?php echo $info['introduction']; ?></td>
  </tr>
</table>
 
<span style="font-family:Arial, Helvetica, sans-serif;font-size:55px;color:#3399FF">
<input name="button1" type="submit" align="right" class="button" id="button1" value="返回首页"  >
</span>
	

	<script>
	 $('#button1').click(function(){
		 window.location.href = 'main.html';
	 });
	</script>
</body>
</html>