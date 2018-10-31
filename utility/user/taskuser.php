<?php
/*本代码文件是任务用户类。
 *通过创建本类的对象，可以实现获取用户任务列表等任务管理类功能
 *
 *
 *
 *张笑语 10月24日 新添加
 *
 */
namespace WebsiteUser
{
    include_once __DIR__.'/user.php';
    include_once __DIR__.'/../mysql/sql_get_task.php';
    include_once __DIR__.'/../mysql/sql_get_user_info.php';
    use SqlTskDataFuncs;
    use function SqlUsrDataFuncs\check_if_usrname_exist;
    use function SqlTskDataFuncs\get_task;
    use function SqlTskDataFuncs\insert_task;
    //首先定义一些异常类……
    use Exception;
    class TaskUserNotAuthorizedException extends Exception{}
    class TaskUserNotLoggedInException extends TaskUserNotAuthorizedException{}
    class FcnParamIllegalException extends Exception{}
    //
    class TaskUser extends User
    {
        //用户人物类

        private $add_task_privilege_requirement = 1;
        private $distribute_task_privilege_requirement = 2;
        private $delete_self_created_task_privilege_requirement = 1;
        private $delete_any_task_privilege_requirement = 2;
        private $rank_task_privilege_requirement = 2;
        public function __construct()
        {//构造函数的任务：获取用户权限等级
            parent::__construct();

            //to do:获取全局权限设置要求
        }
        //
        private function assert_is_usr_logged_in()
        {
            if(!$this->is_login)
                throw new TaskUserNotLoginedException("Tried to load tasks for a user which has not logged in");
        }
        private function assert_user_authorized(int $privilege_requirement)
        {
            if(($this->usr_privilege) < $privilege_requirement)
                throw new TaskUserNotAuthorizedException("required privilege level:".$privilege_requirement);
        }
        public function get_personal_task()
        {
            $this->assert_is_usr_logged_in();
            $task_usr_owned = get_task(__ByOwner,$this->name);//数组：正在进行的任务
            $task_usr_created = get_task(__ByCreator,$this->name);//数组，该用户创建的任务
            return ["task_owned"=>$task_usr_owned,"task_created"=>$task_usr_created];
        }
        public function get_team_untaken_task()
        {
            $this->assert_is_usr_logged_in();
            
        }
        public function get_team_ongoing_task()
        {
            
        }
        public function get_team_finished_task(int $limit_of_days_from_completion)
        {

        }
        //actions.....
        public function operate_task(string $operation,int $task_id, $content )
        {
            switch($operation)
            {
                case 'take':
                    //content要求：NULL
                    set_task_status($task_id,2/*ongoing*/,$this->name);
                break;
                case 'finish':
                    set_task_status($task_id,3/*finished*/);
                break;
                case 'report':
                    //content: ["title"->标题,"text"->报告正文]
                    add_report($task_id,$this->name,$content);
                break;
                case 'delete':
                    if(get_task(__ByUniqueId,$task_id)['creator'] == $this->name)
                        $this->assert_user_authorized($this->delete_self_created_task_privilege_requirement);
                    else
                        $this->assert_user_authorized($this->delete_any_task_privilege_requirement);
                    invalidate_task($task_id);
                break;
                case 'distribute':
                    $this->assert_user_authorized($this->distribute_task_privilege_requirement);
                    if(!check_if_usrname_exist($content))
                        throw new FcnParamIllegalException('username not existing');
                    set_task_status($task_id,__Ongoing,$content);
                break;
                case 'rank':
                break;
            }
        }
        function add_task($title, $content,$hours_needed,bool $is_to_take = false,$sprint_id = 0)
        {//is_to_take:是否立即给自己领取任务
            $this->assert_user_authorized($this->add_task_privilege_requirement);
            insert_task($title,$content,$hours_needed,
                        $is_to_take == true ? $this->name : NULL ,$sprint_id);
        }
    }
}
