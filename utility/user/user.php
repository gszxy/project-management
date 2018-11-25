<?php
/*本代码文件是基础用户类。
 *通过创建本类的对象，可以实现登录、注册和获取用户信息的功能。
 *当一个用户登陆进入网页时
 *网页端使用session实现登陆。如果做安卓版本，需要使用另一套方法保存会话。
 *
 *此文件中类的主要调用： utility/basic/session_indentify.php
 *
 *张笑语 10月9日 新添加
 *张笑语 10月15日 初步功能实现（用户登陆注册部分）
 *张笑语 10月29日 类型实现和基本测试
 *张笑语 11月16日 添加权限获取功能
 */

namespace WebsiteUser
{
    include_once __DIR__ . '/../basic/session_identify.php';
    include_once __DIR__ . '/../mysql/sql_get_user_info.php';
    use SessionProcess\SessionUser;
    use function SqlUsrDataFuncs\check_if_usrname_exist;
    use function SqlUsrDataFuncs\check_password;
    use function SqlUsrDataFuncs\add_usr;
    use function SqlUsrDataFuncs\get_usr_info;
    use function SqlUsrDataFuncs\get_user_list;
    use Exception;
    //定义一些异常类
    class UsernameOccupiedException extends Exception{}
    //
    class User
    {
        private $session;/*SessionUser*/
        protected $is_login;
        protected $name;
        protected $usr_id;
        protected $usr_privilege = 1; //用户获取任务信息、进行各种操作的权限等级
        public function __construct()
        {
            $this ->session = new SessionUser();
            $info = $this->session->get_user_login_info();
            $this->is_login = $info['is_login'];
            $this->name = $info['name'];
            //$this->usr = $info['id'];
            if($this->is_login)
                $this->usr_privilege  = get_usr_info($this->name)['identity'];
        }
        public function login(string $usrname, string $psd_sha1,bool $is_to_remember = false) : array 
        {
            $array_check_result = check_password($usrname, $psd_sha1);
            /*返回值格式举例：["is_usr_exist"=>true,"is_psd_ok"=>true];*/
            $this->is_login = $array_check_result["is_psd_ok"];
            if($this->is_login)
            {
                $this->name = $usrname;
                $this->session->usr_login_set_session_and_cookie($usrname,$is_to_remember);
                //张笑语 11月16日添加：权限获取
                $this->usr_privilege  = get_usr_info($this->name)['identity'];
                //此处命名有严重失误。这个get_usr_info是SqlUsrDataFuncs命名空间的，不要和下面的成员函数搞混了
            }
            return $array_check_result;            
        }
        public function register(string $usrname, string $psd_sha1, string $email) : void
        {
            if(check_if_usrname_exist($usrname))
                throw new UsernameOccupiedException();
            add_usr($usrname, $psd_sha1, $email);
        }
        public function get_user_info()
        {
            
            return get_usr_info($this->name);
            //此处命名有严重失误。这个get_usr_info是SqlUsrDataFuncs命名空间的，不要和成员函数搞混了
        }
        public function logout()
        {
            if($this->is_login)
                $this->session->usr_logout_clean_session_and_cookie();
            $this->is_login = false;
        }
        public function get_is_login()
        {
            return $this->is_login;
        }
        public function get_team_member_list()
        {
            return get_user_list();
        }
    }
}