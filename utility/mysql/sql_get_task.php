<?php
/*本代码文件是关于任务功能的数据库操纵类。
 *可以直接对数据库进行操作
 *
 *
 *
 *张笑语 10月24日 新添加
 *
 */
namespace SqlTskDataFuncs
{
    include __DIR__ . '/../basic/db.php';
    include __DIR__ . '/sql_get_user_info.php';
    use DatabaseBasic;
    use Exception;
    //定义一些异常类...
    class TskUserNotFoundException extends Exception {}
    class TskDBErrorException extends Exception {}
    //...
    const __ByCreator = 1;
    const __ByOwner = 2;
    const __ByUniqueId = 3;
    const __ByStatus = 4;
    
    function get_task(int $type, $param ,int $limit_of_days_from_completion = -1) : array
    {//type 1:通过任务的创建者查找任务
     //type 2:通过任务的执行者查找任务
     //type 3:通过任务的全局唯一id查找任务
     //type 4:通过状态获取任务，1->未领取  2->进行中  3->已完成
     //第三个参数限制完成天数，避免任务列表上显示过多已完成任务。未完成任务不受此参数影响。
    }
    function insert_task($creator,$content,$hours_needed,$owner = NULL,$sprint_id = 0)
    {
        $con_obj = DatabaseBasic::get_connection_obj();
        $_creator = $con_obj->real_escape_string($creator);
        $_content = $con_obj->real_escape_string($content);
        if(isset($owner))//如果指定了任务执行者。典型情况是自己给自己添加任务
        {
            $_owner = $con_obj->real_escape_string($owner);
            $query = $con_obj->prepare("INSERT INTO tasks (`creator`,`content`,`huors_needed`,`owner`,`status`) VALUES(?,?,?,?,2)"); 
            //status = 2，数据库默认值为1，即跳过待领取阶段进入进行中阶段     
            if($add_usr_query == false)
                throw new DBErrorException($con_obj->error);
            $add_usr_query -> bind_param("ssds",$_creator,$_content,$hours_needed,$_owner);
        }
        else
        {
            $query = $con_obj -> prepare("INSERT INTO tasks (`creator`,`content`,`huors_needed`) VALUES( ? , ? , ? )");          
            if($add_usr_query == false)
                throw new DBErrorException($con_obj->error);
            $add_usr_query -> bind_param("sss",$_creator,$_content,$hours_needed);
        }
        $is_successful = $add_usr_query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con_obj->error);
    }
    function progress_task($task_id,$hours_gone)//增加一个任务已经完成的时间
    {

    }
    function add_report($user,$content)//为将来开发多团队功能预留
    {

    }
    function invalidate_task($task_id)
    {//我们删除一个任务的方式不是从数据库中移除，而是将其标记为无效

    }

}