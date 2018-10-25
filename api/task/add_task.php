<?php
/*
 * 前端接口：添加新任务
 *
 * 张笑语 2018年10月25日添加
 * 仅在已登录状态下有效。不需要提供用户名
 * 测试状态：尚未测试
 */

 include '../../utility/user/taskuser.php';
 use WebsiteUser\TaskUser;
 use Exception;
 header('Content-type:text/json');
 header("Access-Control-Allow-Origin: *");
 $task_user = new TaskUser();
 try
 {
     $task_user->add_task($_POST['content'],$_POST['hours_needed'],$_POSY['is_to_take']);
     $is_ok = true;
     $msg ='';
 }
 catch (Exception $exp)
 {
     $is_ok = false;
     $msg = $exp->get_message();
 }
 echo json_encode(['result'=>$exp,'message'=>$msg]);