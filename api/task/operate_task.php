<?php
/*
 * 前端接口：操作任务
 *
 * 张笑语 2018年10月31日添加
 * 仅在已登录状态下有效。不需要提供用户名
 * 测试状态：尚未测试
 */

include '../../utility/user/taskuser.php';
include_once '../../utility/mysql/sql_get_task.php';
//这个引入只是为了使用异常类。需要探索一个更好的方式
use WebsiteUser\TaskUser;
$task_user = new TaskUser();
header('Content-type:text/json');
header("Access-Control-Allow-Origin: *");//为了调试方便而设。生产环境下应当删除。
$type = $_POST["type"];
$result= true;
$info ="";
//操作类型
if(!isset($_POST['task_id']))
{
    http_response_code(400);
    $result = false;
    $info = "Operation type not assigned.";
    exit;
}
try
{
    switch($type)
    {
        //这个switch似乎有些多余，但是是为了以后调用其它模块方便而设的
        //比如，我们要在任务完成之后给管理员发送一条消息提醒他们给任务打分
        //就在具体操作后面调用相应方法即可。
    case 'take':
        $task_user->operate_task('take',$_POST['task_id']);
    break;
    case 'delete':
        $task_user->operate_task('delete',$_POST['task_id']);
    break;
    case 'finish':
        $task_user->operate_task('finish',$_POST['task_id']);
    break;
    default:
        http_response_code(400);
        $result = false;
        $info = "Unrecognised Operation Type";
    }
}catch(WebsiteUser\TaskUserNotAuthorizedException $exp)
{
    http_response_code(403);
    $result = false;
    $info = $exp->getMessage();
}
catch(WebsiteUser\TaskUserNotLoggedInException $exp)
{
    http_response_code(403);
    $result = false;
    $info = $exp->getMessage();
}
catch(SqlTskDataFuncs\OperationOnInvalidTaskIdException $exp)
{
    http_response_code(404);
    $result = false;
    $info = $exp->getMessage();
}
echo json_encode(["result"=>$result,"info"=>$info]);