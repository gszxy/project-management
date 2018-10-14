<?php


/*此类负责总处理网站会话变量,实现登录状态保持和未登录用户标记,或跟踪未登录用户
 * 
 * 
 * 待添加：用户手动退出时清除session和cookie的功能
 * 
 * 
 * 
 *
 * --------------------------------------------------------------
 * 张笑语  2018年10月9日   新添加
 */
namespace SessionProcess
{
    include 'utility/mysql/sql_get_user_info.php';
    use function SqlUsrDataFuncs\get_usr_info;
    class SessionUser
    {
        private static $magic_str = "e32dcf$^fgw2QW@!@SVAS";
        //这个字符串的值对系统安全很重要，不得泄露。
        //用于生成验证保存登录状态的session变量和cookie。
        //修改此字符串使得所有已经登陆网站的用户立刻退出，网站所有用户保存的登录状态失效
        private $is_login;
        private $usr_name;
        private $session_psd;  
   
        public function __construct(int $type = 0,string $name = null, string $tele_session_psd = '')
        {//type参数：0->浏览器  1->手机app。目前不支持手机app访问，三个参数全部无效。
            session_start();

            if(!(isset($_SESSION["username"]) && isset($_SESSION["session_psd"])))
            {
                if(isset($_COOKIE["username"]) && isset($_COOKIE["session_psd"]))
                {//如果当前会话没有登陆，那么
                    $_SESSION["username"] = $_COOKIE["username"];
                    $_SESSION["session_psd"] = $_COOKIE["session_psd"];
                }
                else
                {
                    $this->is_login = false;
                    return;
                }
            }
            //下面检查加密的session_psd是否合法，确认是否允许登录。
            $claimed_name = $_SESSION["username"];
            $claimed_s_psd = $_SESSION["session_psd"];
            $legal_s_psd = sha1($claimed_name.$magic_str);
            //用户名和magic_str拼接后使用sha1算法单项加密，然后比对用户提交的session_psd（页面上注册或登录时使用相同方法生成）
            //这样一来可以确定保持登录状态的cookie不是伪造的
            if($claimed_s_psd == $legal_s_psd)
            {
                $this->is_login = true;
                $this->username = $_SESSION["username"];
                $this->session_psd = $_SESSION["username"];
            }
            else
            {
                $_SESSION["session_psd"] = '';
                $_SESSION["name"] = '';
                //删除无效的session数据
                $this->is_login = false;
                return;
            }
        }
        public function get_name() : string
        {
            if($is_login)
                return $this->name;
            else
                return "";
        }
        public function usr_login_set_session_and_cookie
               (string $name,string $is_to_remember = false,int $valid_time = 2592000) : void
        {
            $new_s_psd = sha1($name.$magic_str);
            $_SESSION["username"] = $name;
            $_SESSION["session_psd"] = $new_s_psd;
            if($is_to_remember)
            {
                setcookie("username",$name,time()+$valid_time);
                setcookie("session_psd",$new_s_psd,time()+$valid_time);
            }
        }

    }
}