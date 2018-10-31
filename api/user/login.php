<?php
include '../../utility/user/user.php';
use WebsiteUser\User;
ob_start();
/*
 * 前端接口：用户登录
 * 
 * 张笑语 2018年10月22日添加
 * todo:添加验证码验证功能
 * 
 * 
 */
$usr = new User();//构造函数涉及到cookie，一定要放在header之前
//这个bug是意料之外的，确实导致了一定的公共环境耦合

header('Content-type:text/json');
header("Access-Control-Allow-Origin: *");



if($usr->get_is_login())
    $usr->logout();
$login_result = $usr->login($_POST["username"], sha1($_POST["psd"]."a_magic_str__@#S8GT^Y&JHGA13"),$_POST['is_to_remember']);

$result = json_encode($login_result);
//$result格式举例：return ["is_usr_exist"=>true,"is_psd_ok"=>false];
echo $result;
    
    