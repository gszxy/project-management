<?php


namespace WebsiteUser
{
    include __FILE__.'/user.php';
    class FileUser extends User
    {
        private $delete_file_privilege_requirement = 2;
        private function assert_usr_authorized(int $privilege_requirement)
        {

        }
        public function __construct()
        {
            parent::__construct();

            //todo:增加权限获取功能
        }
        public function upload_file()
        {

        }
        public function download_file()
        {

        }
        public function delete_file()
        {

        }
    }






}