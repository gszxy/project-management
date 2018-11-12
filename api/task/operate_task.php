<?php
/*
 * 前端接口：操作任务
 *
 * 张笑语 2018年10月31日添加
 * 仅在已登录状态下有效。不需要提供用户名
 * 测试状态：尚未测试
 */

include '../../utility/user/taskuser.php';
use WebsiteUser\TaskUser;
$task_user = new TaskUser();
header('Content-type:text/json');
header("Access-Control-Allow-Origin: *");
$type = $_POST["type"];
//操作类型
if(!isset($_POST['task_id']))
{
    http_response_code(400);
    exit;
}
try
{
    switch($type)
    {
    case 'take':
        $task_user->operate_task('take',$_POST['task_id']);
    break;
    case 'delete':
        $task_user->operate_task('delete',$_POST['task_id']);
    break;
    }
}catch(WebsiteUser\TaskUserNotAuthorizedException $exp)
{
    http_response_code(403);
}
catch(WebsiteUser\TaskUserNotLoggedInException $exp)
{
    http_response_code(403);
}