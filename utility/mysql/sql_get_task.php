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
    include_once __DIR__ . '/../basic/db.php';
    include_once __DIR__ . '/sql_get_user_info.php';
    use DatabaseBasic;
    use Exception;
    use SqlUsrDataFuncs\DBErrorException;
    //定义一些异常类...
    class TskUserNotFoundException extends Exception {}
    class TskDBErrorException extends Exception {}
    //...
    const __ByCreator = 1;
    const __ByOwner = 2;
    const __ByUniqueId = 3;
    const __ByStatus = 4;
    
    const __Untaken = 1;
    const __Ongoing = 2;
    const __Finished= 3;
    
    function get_task(int $type, $param ,int $limit_of_days_from_completion = -1) : array
    {//type 1:通过任务的创建者查找任务
     //type 2:通过任务的执行者查找任务
     //type 3:通过任务的全局唯一id查找任务
     //type 4:通过状态获取任务，1->未领取  2->进行中  3->已完成
     //第三个参数限制完成天数，避免任务列表上显示过多已完成任务。未完成任务不受此参数影响。
    }
    function insert_task($title,$content ,$creator,$hours_needed,$owner = NULL,$sprint_id = 0)
    {
        $con = DatabaseBasic::get_connection_obj();
        $_creator = $con->real_escape_string($creator);
        $_content = $con->real_escape_string($content);
        $_title = $con->real_escape_string($title);
        if($owner != NULL)//如果指定了任务执行者。典型情况是自己给自己添加任务
        {
            $_owner = $con->real_escape_string($owner);
            $query = $con->prepare("INSERT INTO tasks(`title`,`creator`,`content`,`hours_needed`,`owner`,`status`)VALUES(?,?,?,?,?,2)"); 
            //status = 2，数据库默认值为1，即跳过待领取阶段进入进行中阶段     
            if($query == false)
                throw new DBErrorException($con->error);
            $query -> bind_param("sssds",$_title,$_creator,$_content,$hours_needed,$_owner);
        }
        else
        {
            $query = $con -> prepare("INSERT INTO tasks (`title`,`creator`,`content`,`hours_needed`) VALUES( ? , ? , ?,?)");          
            if($query == false)
                throw new DBErrorException($con->error);
            $query -> bind_param("ssss",$_title,$_creator,$_content,$hours_needed);
        }
        $is_successful = $query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con->error);
    }
    function set_task_status($task_id,$status,$content = NULL)
    {//content:将任务状态设置为进行中时，提供执行任务的用户的用户名

    }
    function progress_task($task_id,$hours_gone)//增加一个任务已经完成的时间
    {

    }
    function add_report($task_id,$user,$content)
    {

    }
    function invalidate_task($task_id)
    {//我们删除一个任务的方式不是从数据库中移除，而是将其标记为无效
        $con = DatabaseBasic::get_connection_obj();
        $query = $con->prepare("UPDATE tasks SET validity = 0 WHERE id = ?");
        $query->bind_param("i",$task_id);
        if($query == false)
            throw new DBErrorException($con->error);
        $is_successful = $query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con->error);
    }


}

