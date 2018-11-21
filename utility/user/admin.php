<?php


namespace WebsiteUser
{
    include_once __DIR__.'/user.php';
    include_once __DIR__.'/../mysql/sql_get_task.php';
    include_once __DIR__.'/../mysql/sql_get_user_info.php';
    include_once __DIR__.'/../mysql/sql_task_rank.php';
    use WebsiteUser\User;
    use function SqlTskDataFuncs\get_user_unfinished_task_breif;
    use function SqlUsrDataFuncs\get_complete_user_info;
    use Exception;

    //定义一些异常类...
    class AdminOperationNotAuthorizedException extends Exception {};
    //......
    class Admin extends User
    {
        private $watch_team_member_info_privilege_requirement = 3;
        private $change_team_member_privilege_privilege_requirement = 10;
        private $delete_team_member_privilege_requirement = 10;
        private $add_team_member_privilege_requirement = 3;
        private $sys_announce_privilege_requirement = 3;

        public function __construct()
        {
            parent::__construct();
            //todo:读取管理员权限操作的权限要求，覆盖上方默认值
        }

        private function assert_admin_authorized(int $privi_require)
        {
            //todo:为敏感的管理员权限操作添加日志
            if(!$this->is_login)
                throw new AdminOperationNotAuthorizedException("Tried to use functions reserved for admins without logging in.");
            else if($this->usr_privilege < $privi_require)
                throw new AdminOperationNotAuthorizedException("Tried to use functions reserved for admins without required privilege.");
        }
        public function get_team_member_detailed_info()
        {
            //除了基础用户信息以外，还追加以下信息：
            //用户过去一周完成的任务量
            //用户剩余未完成的任务量及小时数
            $this->assert_admin_authorized($this->watch_team_member_info_privilege_requirement);
            $_user =  get_complete_user_info();

            $task_info = get_user_unfinished_task_breif('all');//其实这个函数应该改个名字..因为它现在也返回已完成任务的信息
            $user = array();
            foreach($_user as &$a_user )
            {
                $user[$a_user['name']] = $a_user;
            }
            
            $result = array_merge_recursive($user,$task_info);
            //转索引数组
            //这个破东西以后得换成数据库LEFT JOIN
            return array_values($result);
        }

        public function send_system_announcement(string $announce,string $expire_datetime)
        {

        }
    }
}