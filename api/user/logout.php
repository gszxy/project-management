<?php
include '../../utility/user/user.php';
use WebsiteUser\User;

/*
 * 前端接口：用户登录
 * 
 * 张笑语 2018年10月22日添加
 * 
 * 方法：HTTP POST
 * 
 */

header("Access-Control-Allow-Origin: *");

$usr = new User();
if($usr->get_is_login())
    $usr->logout();
