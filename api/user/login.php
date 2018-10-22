<?php
include '../../utility/user/user.php';
use WebsiteUser\User;

/*
 * 前端接口：用户登录
 * 
 * 张笑语 2018年10月22日添加
 * todo:添加验证码验证功能
 * 
 * 
 */
header("Content-type:text/json");

$usr = new User();

if($usr->get_is_login())
    $usr->logout();
$login_result = $usr->login($_POST["username"], sha1($_POST["psd"]));

$result = json_encode($login_result);
//$result格式举例：return ["is_usr_exist"=>true,"is_psd_ok"=>false];
echo $result;
    
    