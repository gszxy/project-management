<?php

/*
 * 本文件为数据库连接基础文件。
 * <------------------------------------------------------------>
 * <-----严禁将此文件包含服务器ip地址，数据库密码等敏感信息的版本上传至网络----->
 * <------------------------------------------------------------>
 * 未完成工作：
 * 为服务器连接功能提供异常处理功能
 * 提供状态查询功能
 * --------------------------------------------------------------
 * 张笑语 2018年10月9日 新添加
 */
class DatabaseBasic
{
    private static $address = 'localhost';
    private static $username = '';
    private static $password = '';
    private static $dbname = '';
    private static $dbport = 3389;
    private static $status = 0;//状态： 1->已连接 0->未连接 -1->出错
    function db_connect()
    {
        $connection = new mysqli($address, $username, $password, $dbname, $dbport);
        $connection->set_charset('utf8');
        return $connection;
    }
}
