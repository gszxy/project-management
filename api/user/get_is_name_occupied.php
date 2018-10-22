<?php
include '../../utility/mysql/sql_get_user_info.php';
use function SqlUsrDataFuncs\check_if_usrname_exist;

/*
 * 前端接口：检查用户名是否已经被占用
 *
 * 张笑语 2018年10月22日添加
 *
 * 测试状态：尚未测试
 */
header("Content-type:text/json");

$name = $_GET["name_to_check"];

$is_name_ok = !check_if_usrname_exist($name);