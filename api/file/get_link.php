<?php
/*
 * 前端接口：获取上传文件的预授权链接
 *
 * 张笑语 2018年11月19日添加
 * 仅在已登录状态下有效。不需要提供用户名
 * 测试状态：未测试
 */

include '../../utility/user/fileuser.php';;
 use WebsiteUser\FileUser;
 $user = new FileUser();

header('Content-type:text/json');
header("Access-Control-Allow-Origin: *");
$type = $_GET['type'];
$link = '';
switch($type)
{
case 'upload':
    $result = $user->get_upload_file_link();
break;
}
echo json_encode(["link"=>$link]);


