<?php
/*
 * 前端接口：添加新任务
 *
 * 张笑语 2018年10月25日添加
 *        2018年10月30日测试
 * 仅在已登录状态下有效。不需要提供用户名
 * 测试状态：已测试
 */

 include '../../utility/user/taskuser.php';
 use WebsiteUser\TaskUser;
 $task_user = new TaskUser();
 header('Content-type:text/json');
 header("Access-Control-Allow-Origin: *");
 $is_ok = false;
 $msg = '';

 if(!(isset($_POST['title'])&&isset($_POST['content'])&&isset($_POST['hours_needed'])&&isset($_POST['is_to_take'])&&isset($_POST['close_date'])))
 {
    http_response_code(400);
    $msg = 'illegal parameters';
 }
 else
 {
 try
 {

     $task_user->add_task($_POST['title'],$_POST['content'],$_POST['hours_needed'],$_POST['close_date'],$_POST['is_to_take'] == 'true');
     $is_ok = true;
 }
 catch (WebsiteUser\FcnParamIllegalException $exp)
 {
     http_response_code(400);// HTTP 400错误：请求参数有误
     $msg = $exp->getMessage();
 }
 catch(WebsiteUser\TaskUserNotAuthorizedException $exp)
 {
    http_response_code(403);// HTTP 403错误：拒绝响应   要么没登录，要么没权限，详见错误消息
    $msg = $exp->getMessage();
 }
 catch (Exception $exp)
 {
    http_response_code(500); //HTTP 500错误：服务器内部错误。 遇此错误请报告bug
    $msg = "UNHANDLED EXCEPTION:".$exp->getMessage();
 }
 
}
echo json_encode(['result'=>$is_ok,'message'=>$msg]);