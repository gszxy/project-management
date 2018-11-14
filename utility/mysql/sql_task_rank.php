<?php
/*
*
*  数据库存储函数————用于对任务进行评价和打分
*
*
*
*
*
*/
namespace SqlTskRankFuncs
{
    include_once __DIR__ . '/../basic/db.php';
    function RankTask(int $task_id,string $ranker,int $rank)
    {
        //对一个任务进行打分
        //特别注意：此函数不检查任务是否存在，也不检查任务的当前状态是否允许打分。
        //请在上层逻辑中完成相应任务
        $con = DatabaseBasic::get_connection_obj();
        $_ranker = $con->real_escape_string($ranker);
        $query = $con -> prepare("INSERT INTO ranks(task_id,ranker,score) VALUES(?,?,?) ");
        $query -> bind_param('dsd', $task_id ,$_ranker,$score);
        
        if($query->execute()) //这个函数返回值是查询是否成功
        {   
            return true;
        }
        else
        {
            return false;
        }
    }
    function GetTaskRank(int $task_id):array
    {
        $con = DatabaseBasic::get_connection_obj();
        $result = $con->query("SELECT * FROM ranks WHERE task_id = $task_id");
        $task_ranks = array();
        while($line = $result->fetch_array(MYSQLI_ASSOC))
        {
            array_push($task_ranks,$line);
        }
        return $task_ranks;
    }
}