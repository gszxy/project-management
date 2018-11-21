<?php
//以下函数有权与数据库进行直接交互。
/*
 * 这些函数是给user类使用的，不得在业务逻辑中直接调用函数访问数据库信息
 * 
 * 张笑语 10月13日 新添加 设计基础功能
 */
namespace SqlUsrDataFuncs
{
    include_once __DIR__ . '/../basic/db.php';
    use DatabaseBasic;
    use Exception;

    //定义一些异常类……
    class DBErrorException extends Exception {}
    class UserNotFoundException extends Exception {}
    //
    function get_usr_info($usr_name) : array
    {
        $con_obj = DatabaseBasic::get_connection_obj();
        $_name = $con_obj->real_escape_string($usr_name);
        $find_name_query = $con_obj -> prepare("SELECT * FROM users WHERE name = ? ");
        $find_name_query -> bind_param('s', $_name);
        
        if($find_name_query->execute()) //这个函数返回值是查询是否成功
        {   
            $result = $find_name_query->get_result();
            if($result->num_rows > 1)
                throw new Exception("username not unqiue");
            else if($result->num_rows == 0)
                throw new UserNotFoundException("user not found");
            return mysqli_fetch_array($result,MYSQLI_ASSOC);
        }
    }
    function check_if_usrname_exist(string $name) : bool
    {
        if($name == NULL)
            return false;
        $con_obj = DatabaseBasic::get_connection_obj();
        $_name = $con_obj->real_escape_string($name);
        $find_psd_query = $con_obj -> prepare("SELECT psd_sha1 FROM users WHERE name = ? ");
        $find_psd_query->bind_param('s', $_name);
        
        if($find_psd_query->execute()) //这个函数返回值是查询是否成功
        {
            $result = $find_psd_query->get_result();
            if($result->num_rows == 0)
                return false;
            else
                return true;
        }
        else
            throw new DBErrorException();
    }

    function check_password($usr_name,$psd_sha1) : array
    {
        $con_obj = DatabaseBasic::get_connection_obj();
        $_name = $con_obj->real_escape_string($usr_name);
        $find_psd_query = $con_obj -> prepare("SELECT psd_sha1 FROM users WHERE `name` = ? ");
        $find_psd_query->bind_param('s', $_name);
        
        if($find_psd_query->execute()) //这个函数返回值是查询是否成功
        {
			$result = $find_psd_query->get_result();
            if($result->num_rows > 1)
                throw new Exception("username not unqiue");
            if($result->num_rows == 0)
                return ["is_usr_exist"=>false,"is_psd_ok"=>false];
                
            $real_psd_sha1 = mysqli_fetch_array($result,MYSQLI_ASSOC)["psd_sha1"];
            if($psd_sha1 == $real_psd_sha1)
                return ["is_usr_exist"=>true,"is_psd_ok"=>true];
            else
                return ["is_usr_exist"=>true,"is_psd_ok"=>false];
        }
    }

    function add_usr($name,$psd_sha1,$email) : void
    {
        $con_obj = DatabaseBasic::get_connection_obj();
        $_name = $con_obj->real_escape_string($name);
        $_email = $con_obj->real_escape_string($email);
        $add_usr_query = $con_obj -> prepare("INSERT INTO users (`name`,`psd_sha1`,`email`) VALUES( ? , ? , ? )");
        if($add_usr_query == false)
            throw new DBErrorException($con_obj->error);
        $add_usr_query -> bind_param("sss",$_name,$psd_sha1,$_email);
        $is_successful = $add_usr_query -> execute();
        if(!$is_successful)
            throw new DBErrorException();
    }

    function change_psd($name,$new_psd_sha1)
    {
    
    }
    function get_email($usrname)
    {
        
    }

    function get_complete_user_info()
    {
        $con = DatabaseBasic::get_connection_obj();
        $result = $con->query("SELECT * FROM users"); //记得删除密码那个字段（显然……）
        $ret = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            unset($row['psd_sha1']);
            array_push($ret,$row);
        }
        return $ret;
    }
    function get_user_list()
    {
        $con = DatabaseBasic::get_connection_obj();
        $result = $con->query("SELECT `name` FROM users"); 
        $ret = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            array_push($ret,$row);
        }
        return $ret;
    }
}