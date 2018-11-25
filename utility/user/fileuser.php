<?php
//通过腾讯云对象存储实现安全的文件上传下载功能
//此功能截止到2018年11月25日没有完成

namespace WebsiteUser
{
    include __DIR__.'/user.php';
    require __DIR__.'/../misc/qcloudsdk/vendor/autoload.php';  //腾讯云
    use Exception;
    use Qcloud\Cos\Client;
    class FileUserNotAuthorizedException extends Exception{}
    class TecentCloudSDKErrorException extends Exception{}
    class FileUser extends User
    {
        private $cos_obj; //腾讯云对象存储SDK操作对象

        private $delete_any_file_privilege_requirement = 3;
        private $delete_self_uploaded_file_privilege_requirement = 3;
        private $file_user_basic_privilege_requirement = 1;
        private function assert_usr_authorized($privilege_requirement)
        {

        }
        public function __construct()
        {
            parent::__construct();

            //todo:增加权限获取功能
            $this->cos_obj = new Client(array(
                'region' => 'ap-beijing',
                'credentials' => array(
                'appId' => '1253546535',
                'secretId' => 'AKIDTr46xeR1RfkdKqQIE2hCWfeS5VjYLi18',
                'secretKey' => '3vb5NUHwQwLZbR4VjmdIl2AAuY0HMFx7',
                ),
            ));

        }
        public function get_upload_file_link($filename = '')
        {
            $this->assert_usr_authorized(0);
            $signedUrl = '';
                #此处可以替换为其他上传接口
                $command = $this->cos_obj->getCommand('putObject', array(
                    'Bucket' => 'knight-dusk-1253546535',
                    'Key' => "testing.txt",
                    'Body' => '', //Body可以任意
                ));
                $signedUrl = $command->createPresignedUrl('+10 minutes');

            return $signedUrl;
        }
        public function download_file()
        {

        }
        public function delete_file()
        {

        }
    }






}