<?php
include '../../utility/user/user.php';
use WebsiteUser\User;
use WebsiteUser\UsernameOccupiedException;
/*
 * 前端接口：用户注册功能
 *
 * 张笑语 2018年10月22日添加
 * todo:添加发送邮件功能
 *
 *
 */
header("Content-type:text/json");

$usr = new User();
$is_register_successful = 'false';
$info = '';
if($usr->get_is_login())
    $usr->logout();
try 
{
    $usr->register($_POST["username"], sha1($_POST["psd"]), $_POST["email"]);
    $is_register_successful = true;
    $info = "success";
}catch(UsernameOccupiedException $exp)
{
    $is_register_successful = false;
    $info = "username occupied";
}finally 
{
    echo(json_encode(["result"=>$is_register_successful,"info"=>$info]));
}
