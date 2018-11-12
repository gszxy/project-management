<?php

/*
 * 前端接口：获取任务列表
 * 张笑语 2018年11月5日添加      
 * 仅在已登录状态下有效。不需要提供用户名
 * 方法：HTTP GET
 * 功能列表
 * 1.获取个人任务列表   2018年11月5日   未测试   张笑语
 * 2.获取团队整体任务列表
 * 
 * 
 */
include '../../utility/user/taskuser.php';
use WebsiteUser\TaskUser;
$task_user = new TaskUser();
header('Content-type:text/json');
header("Access-Control-Allow-Origin: *");
$type = $_GET["type"];
//操作类型
$result = '';
$msg = '';
$is_ok = true;
switch($type)
{
case 'personal':
 //   try{
    $result = $task_user->get_personal_task();
 //   }catch(Exception $exp)
  //  {
  //      http_response_code(500);
  //      $msg = $exp->getMessage();
  //      $is_ok = false;
  //  }
break;
case 'team':

 //   try{
        $limit_of_days_from_completion = isset($_GET['limit']) ? $_GET['limit'] : 365;
        //设定了已完成任务的最长查询时间。避免出现的任务过多。默认值365.
        $untaken = $task_user->get_team_untaken_task();
        $ongoing = $task_user->get_team_ongoing_task();
        $finished = $task_user->get_team_finished_task($limit_of_days_from_completion);
        $result = ['untaken'=>$untaken,'ongoing'=>$ongoing,'$finished'=>$finished];
   // }catch(Exception $exp)
   // {
   //     http_response_code(500);
    //    $msg = $exp->getMessage();
    //    $is_ok = false;
    //}
break;
default:
    http_response_code(400);
    $msg = 'illegal parameters: unrecognised operation type';
    $is_ok = false;
break;
}
echo json_encode(['is_ok'=>$is_ok,'result'=>$result,'msg'=>$msg]);

