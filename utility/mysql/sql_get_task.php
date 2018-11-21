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
    include_once __DIR__ . '/../mysql/sql_get_user_info.php';
    use DatabaseBasic;
    use Exception;
    use SqlUsrDataFuncs\DBErrorException;
    use SqlUsrDataFuncs\check_if_usrname_exist;
    //引入一些异常类...
    class TskUserNotFoundException extends Exception {}
    class TskDBErrorException extends Exception {}
    class TskFcnInvalidArgException extends Exception{}
    class OperationOnInvalidTaskIdException extends Exception{}
    //...
    const __ByCreator = 1;
    const __ByOwner = 2;
    const __ByUniqueId = 3;
    const __ByStatus = 4;
    
    const __Untaken = 1;
    const __Ongoing = 2;
    const __Finished= 3;
    
    function get_task(int $type, $param ,int $limit_of_days_from_completion = -1, int $max_num = -1)
    {//type 1:通过任务的创建者查找任务
     //type 2:通过任务的执行者查找任务
     //type 3:通过任务的全局唯一id查找任务
     //type 4:通过状态获取任务，1->未领取  2->进行中  3->已完成
     //第三个参数限制完成天数，避免任务列表上显示过多已完成任务。未完成任务不受此参数影响。
        $str_query;
        $pattern;
        $limit_pattern = ' AND validity = 1';
         //说明：被标记为删除的任务validity=0
        if($limit_of_days_from_completion != -1)
        {
            $limit_pattern .= " AND DATEDIFF(CURDATE() , date(`add_date`) ) < $limit_of_days_from_completion";

            if($max_num != -1)
                $limit_pattern .= " LIMIT $max_num";
        }
        switch($type)
        {
        case __ByCreator:
            $str_query = 'SELECT * FROM tasks WHERE creator = ? '. $limit_pattern;
            $pattern = "s";
        break;
        case __ByOwner:
            $str_query = 'SELECT * FROM tasks WHERE `owner` = ? '. $limit_pattern;
            $pattern = "s";
        break;
        case __ByUniqueId:
            $str_query = 'SELECT * FROM tasks WHERE id = ?'. $limit_pattern;
            $pattern = "i";
        break;
        case __ByStatus:
            $str_query = 'SELECT * FROM tasks WHERE `status` = ?'. $limit_pattern;
            $pattern = "i";
        break;
    }
        $con = DatabaseBasic::get_connection_obj();
        $query = $con->prepare($str_query);
        if($query == false)
            throw new DBErrorException($con->error);
        $query->bind_param($pattern,$param);
        $is_successful = $query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con->error);
    //processing result.....
        $tasks = array();
        $num = 0;
        $result = $query->get_result();
        while($line = $result->fetch_array(MYSQLI_ASSOC))
        {
            ++$num;
            array_push($tasks,$line);
        }
        return $tasks;
    }
    function insert_task($title,$content ,$creator,$hours_needed,$close_date,$status, $owner = false,$sprint_id = 0)
    {
        $con = DatabaseBasic::get_connection_obj();
        $_creator = $con->real_escape_string($creator);
        $_content = $con->real_escape_string($content);
        $_title = $con->real_escape_string($title);
        $_close_date = $con->real_escape_string($close_date);
        if($status == 2)//如果指定了任务执行者。典型情况是自己给自己添加任务
        {
            $_owner = $con->real_escape_string($owner);
            $query = $con->prepare("INSERT INTO tasks(`title`,`creator`,`content`,`hours_needed`,`owner`,`status`,`close_date`)VALUES(?,?,?,?,?,2,?)"); 
            //status = 2，数据库默认值为1，即跳过待领取阶段进入进行中阶段     
            if($query == false)
                throw new DBErrorException($con->error);
            $query -> bind_param("sssdss",$_title,$_creator,$_content,$hours_needed,$_owner,$_close_date);
        }
        else
        {
            $query = $con -> prepare("INSERT INTO tasks (`title`,`creator`,`content`,`hours_needed`,`close_date`) VALUES( ? , ? , ?,?,?)");          
            if($query == false)
                throw new DBErrorException($con->error);
            $query -> bind_param("sssds",$_title,$_creator,$_content,$hours_needed,$_close_date);
        }
        $is_successful = $query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con->error);
    }
    function set_task_status($task_id,$status,$content = NULL)
    {//content:将任务状态设置为进行中时，提供执行任务的用户的用户名
     //注意：此函数不检查用户名是否合法，由上层逻辑完成
        $con = DatabaseBasic::get_connection_obj();
        switch($status)
        {
        case __Ongoing:
            $query = $con->prepare("UPDATE tasks SET `status` = ? ,`owner`=?,`start_date`=CURRENT_TIMESTAMP WHERE id = ?");
            $query->bind_param("isi",$status,$content,$task_id);
        break;
        case __Finished:
            $query = $con->prepare("UPDATE tasks SET `status` = ? ,`finish_date`= CURRENT_TIMESTAMP WHERE id = ?");
            $query->bind_param("ii",$status,$task_id);
        break;
        default://未定义的特殊状态
            $query = $con->prepare("UPDATE tasks SET `status` = ? WHERE id = ?");
            $query->bind_param("ii",$status,$task_id);
        }
        if($query == false)
            throw new DBErrorException($con->error);
        $is_successful = $query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con->error);
        if($con->affected_rows==0)
            throw new OperationOnInvalidTaskIdException("Tried to set status for a non-existing task");
    }
    function progress_task(int $task_id,int $hours_gone)//增加一个任务已经完成的时间
    {

    }
    function add_report($task_id,$user,$content)
    {

    }
    function get_report(int $type = -1, $content)
    {
        //ByUniqueId:按照任务id获取全部报告
        //ByCreator:
        //默认值：获取所有报告
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
        if($con->affected_rows==0)
            throw new OperationOnInvalidTaskIdException("Tried to delete a non-existing task");
    }

    function get_user_unfinished_task_breif($type,$name = null) : array
    {   //获取指定用户未完成任务的大致信息
        //函数总是返回数组，即使只是查询一个用户的信息
        //关联数组的索引是用户的名字
        //步骤一：获取所有未完成任务
        $con = DatabaseBasic::get_connection_obj();
        $_name = $con->real_escape_string($name);

        if($type == 'single')
            $qstr = "SELECT `owner`, SUM(hours_needed)-SUM(hours_gone) AS hours_ongoing,COUNT(id) AS count_ongoing
                     FROM tasks WHERE `owner`=? AND `status`=2 GROUP BY `owner` ";
        else if($type == 'all')//获取全部用户的未完成任务数据
            $qstr = "SELECT `owner`, SUM(hours_needed)-SUM(hours_gone) AS hours_ongoing,COUNT(id) AS count_ongoing
                     FROM tasks WHERE `status`=2 GROUP BY `owner` ";
        else
            throw new TskFcnInvalidArgException('Unrecogized Operation Type');
        $query = $con->prepare($qstr);
        if($type == 'single')
            $query->bind_param("s",$_name);
        if($query == false)
            throw new DBErrorException($con->error);
        $is_successful = $query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con->error);
        $unfinished = $query->get_result();
        // 步骤二：获取一周内完成的任务  
        if($type == 'single')
            $qstr = "SELECT `owner`, COUNT(id) AS count_finished FROM tasks WHERE `owner`=? AND `status` = 3 
                 AND DATEDIFF(CURDATE() , date(`finish_date`) ) <= 7 GROUP BY `owner` ORDER BY id ";
        else if($type == 'all')//获取全部用户的未完成任务数据
            $qstr = "SELECT `owner`, COUNT(id) AS count_finished FROM tasks WHERE `status` = 3 
                 AND DATEDIFF(CURDATE() , date(`finish_date`) ) <= 7 GROUP BY `owner` ORDER BY id ";
        //必须orderbyid，这样下面的循环才得以正确的匹配每个用户的信息
        $query = $con->prepare($qstr);
        if($type == 'single')
            $query->bind_param("s",$_name);
        if($query == false)
            throw new DBErrorException($con->error);
        $is_successful = $query -> execute();
        if(!$is_successful)
            throw new DBErrorException($con->error);
        $finished = $query->get_result();
        //处理数据
        $result = array ();
 
        while($row_unfinished = $unfinished->fetch_array(MYSQLI_ASSOC))
        {       
            $result[$row_unfinished['owner']] = 
            ["count_ongoing"=>$row_unfinished['count_ongoing'],"hours_ongiong"=>$row_unfinished['hours_ongoing']];

            //向关联数组添加了一个这样的键值对
            //名字 => 一个包含未完成和完成了的任务数量的关联数组
        }
        while($row_finished = $finished->fetch_array(MYSQLI_ASSOC))
        {//必须分开处理，因为说不定一个用户没有未完成/进行中的人物
            $name_in_finished = $row_finished['owner'];
            if(isset($result[$name_in_finished]))
                $result[$name_in_finished]["count_finished"] = $row_finished['count_finished'];
            else
                $result[$name_in_finished] = ["count_finished"=>$row_finished['count_finished']];
            //含义同上
        }

        return $result;

    }

}



