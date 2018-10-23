<?php
include '../../utility/user/user.php';
use WebsiteUser\User;
use WebsiteUser\UsernameOccupiedException;
use Exception;
/*
 * 前端接口：用户注册功能
 *
 * 张笑语 2018年10月22日添加
 *       2018年10月24日测试通过
 * todo:添加发送邮件功能
 * 添加检查密码复杂度是否合法功能
 * 添加检查邮箱格式是否合法功能
 * 添加$_POST参数未设置时的报错功能
 */
header('Content-type:text/json');

$usr = new User();
$is_register_successful = 'false';
$info = '';
if($usr->get_is_login())
    $usr->logout();
try 
{
    $usr->register($_POST["username"], sha1($_POST["psd"]."a_magic_str__@#S8GT^Y&JHGA13"), $_POST["email"]);
    $is_register_successful = true;
    $info = "success";
}catch(UsernameOccupiedException $exp)
{
    $is_register_successful = false;
    $info = "username occupied";
}
catch(Exception $exp)
{
	http_response_code(500);
	$str = $exp->getMessage();
	$info = "UNHANDLED EXCEPTION:$str";
}
finally 
{
    echo(json_encode(["result"=>$is_register_successful,"info"=>$info]));
}
