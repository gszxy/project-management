<?php

/*
 * 本文件为数据库连接基础文件。
 * <<<<<测试状态：测试完成
 * <------------------------------------------------------------>
 * <---严禁将此文件中提到的服务器ip地址，数据库密码等敏感信息的版本上传至网络---->
 * <------------------------------------------------------------>
 * 未完成工作：
 * 为服务器连接功能提供异常处理功能
 * 提供状态查询功能
 * --------------------------------------------------------------
 * 张笑语 2018年10月9日 新添加
 *            10月13日 修改结构
 *            10月19日测试、修正
 */

class DatabaseBasic
{
    private static $address = '127.0.0.1';
    private static $username = 'project_manage';
    private static $password = 'qQI5id5efVQYudHU';
    private static $dbname = 'project_manage';
    
    private static $status = 0;//状态： 1->已连接 0->未连接 -1->出错
    private static $connection;
    private static function db_connect()
    {
        self::$connection = new mysqli(self::$address, self::$username, self::$password, self::$dbname);
        self::$connection->set_charset('utf8');
        self::$status = 1;
    }
    public static function get_connection_obj()
    {
        if(self::$status == 0)
            self::db_connect();
            return self::$connection;
            
    }
}