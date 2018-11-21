<?php
include '../../utility/user/admin.php';
use WebsiteUser\Admin;

/*
 * 前端接口：管理员获取全体用户的详细信息
 * 
 * 张笑语 2018年11月16日添加
 * 
 * 方法: HTTP GET
 * 此方法有高级权限要求，详见Admin类
 */

header('Content-type:text/json');
header("Access-Control-Allow-Origin: *");

$admin = new Admin();
$result;
try{
    $result = $admin->get_team_member_detailed_info();
}
catch(WebsiteUser\AdminOperationNotAuthorizedException $exp)
{
    http_response_code(403);
    $result = ["result"=>'failed',"info"=>$exp->getMessage()];
}

echo json_encode($result);