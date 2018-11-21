<?php
include '../../utility/user/user.php';
use WebsiteUser\User;

/*
 * 前端接口：检查用户名是否已经被占用
 *
 * 张笑语 2018年11月15日添加
 *
 * 测试状态：尚未测试
 */
header('Content-type:text/json');
$user =  new User();

echo json_encode($user->get_team_member_list());