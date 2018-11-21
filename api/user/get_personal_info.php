<?php
include '../../utility/user/user.php';
use WebsiteUser\User;

/*
 * 前端接口：获取用户信息
 * 
 * 张笑语 2018年10月27日添加
 * 张笑语 2018年11月5日测试
 * 张笑语 2018年11月16日 添加用户权限返回
 * 方法: HTTP GET
 * 
 */
header('Content-type:text/json');
header("Access-Control-Allow-Origin: *");


$usr = new User();
$name = '';
if(!$usr->get_is_login())
{
    $is_login = false;
}
else
{
    $is_login = true;
    $info = $usr->get_user_info();
    $name = $info["name"];
    $privilege_level = $info["identity"];
}
echo json_encode(['is_login'=>$is_login,'username'=>$name,'identity'=>$privilege_level]);