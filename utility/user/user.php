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
 */

namespace WebsiteUser
{
    include '../basic/session_identify.php';
    include '../mysql/sql_get_user_info.php';
    use SessionProcess\SessionUser;
    use function SqlUsrDataFuncs\check_password;
    class User
    {
        private /*SessionUser*/ $session;
        private $is_login;
        private $name;
        public function __construct()
        {
            $session = new SessionUser();
            $info = $session -> get_user_login_info();
            $this->is_login = $info['is_login'];
            $this->name = $info['name'];
        }
        public function login(string $usrname, string $psd_sha1,bool $is_to_remember = false) : array 
        {
            $array_check_result = check_password($usrname, $psd_sha1);
            /*返回值格式举例：["is_usr_exist"=>true,"is_psd_ok"=>true];*/
            $this->is_login = $array_check_result["is_psd_ok"];
            if($this->is_login)
                $this->name = $usrname;
            return $array_check_result;            
        }
        public function register(string $usrname, string $psd_sha1, string $email) : void
        {
            
        }
        public function get_user_info()
        {
            
        }
        
    }
}