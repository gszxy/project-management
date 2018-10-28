<?php


/*此类负责总处理网站会话变量和cookie
 * 实现登录状态保持和未登录用户标记,或跟踪未登录用户
 * 
 * 
 * 
 * 
 *
 * --------------------------------------------------------------
 * 张笑语  2018年10月9日   新添加
 */
namespace SessionProcess
{
    class SessionUser
    {
        private static $magic_str = "";
        //这个字符串的值对系统安全很重要，不得泄露。
        //用于生成验证保存登录状态的session变量和cookie。
        //修改此字符串使得网站所有用户保存的登录状态失效
        private $is_login;
        private $usr_name;
        private $session_psd;  
   
        public function __construct(int $type = 0,string $name = null, string $tele_session_psd = '')
        {//type参数：0->浏览器  1->手机app。目前不支持手机app访问，三个参数全部无效。
            session_start();
            $this->is_login = false;
            if(isset($_SESSION["username"]) && isset($_SESSION["session_psd"]))
                $this->is_login = true;
            else
            {
                if(isset($_COOKIE["username"]) && isset($_COOKIE["session_psd"]))
                {//如果当前会话没有登陆，那么检查cookie有无记住登陆信息
                    //下面检查加密的session_psd是否合法，确认是否允许登录。
                    $claimed_name = $_COOKIE["username"];
                    $claimed_s_psd = $_COOKIE["session_psd"];
                    $legal_s_psd = sha1($claimed_name.self::$magic_str);
                    //用户名和magic_str拼接后使用sha1算法单项加密，然后比对用户提交的session_psd（页面上注册或登录时使用相同方法生成）
                    //这样一来可以确定保持登录状态的cookie不是伪造的
                    if($claimed_s_psd == $legal_s_psd)
                    {
                        $_SESSION["username"] = $_COOKIE["username"];
                        $_SESSION["session_psd"] = $_COOKIE["session_psd"];
                        $this->is_login = true;
                    }
                }
            }
            if($this->is_login)
            {
                $this->usr_name = $_SESSION["username"];
                $this->session_psd = $_SESSION["session_psd"];
            }           
        }
        public function get_user_login_info() : array
        {
            if($this->is_login)
                return ['is_login' => true,'name' =>$this->usr_name];
            else
                return ['is_login' => false,'name' =>''];
        }
        public function usr_login_set_session_and_cookie
               (string $name,bool $is_to_remember = false,int $valid_time = 2592000) : void
        {
            $new_s_psd = sha1($name.self::$magic_str);
            $_SESSION["username"] = $name;
            $_SESSION["session_psd"] = $new_s_psd;
            if($is_to_remember)
            {
                setcookie("username",$name,time()+$valid_time);
                setcookie("session_psd",$new_s_psd,time()+$valid_time);
            }
            $this->is_login = true;
            $this->usr_name = $name;
        }
        public function usr_logout_clean_session_and_cookie()
        {
            session_destroy();
            if(isset($_COOKIE["username"]) && isset($_COOKIE["session_psd"]))
            {
                setcookie("username",'',time()-3600);
                setcookie("session_psd",'',time()-3600);
                //删除cookie。删除cookie的方式是把有效时间设置为小于当前时间。然后cookie就会被浏览器检测删除
            }
        }
    }
}