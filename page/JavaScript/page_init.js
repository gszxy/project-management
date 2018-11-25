/*
张笑语  11月16日 新添加   把共通的页面初始化搬到一个文件里来



*/
    $.get("/api/user/get_user_list.php", { /*没有参数*/ }, function (data) {
        data.forEach(user => {
            $('#panel_user_list').append('<div class="u-ulistname"></div>');
            $('#panel_user_list').children(":last").text(user.name);
            $('#panel_user_list').children(":last").click(() => {
                //window.location.href = '/page/usrpage.php?username='+user.name;
                //待实现
            });
        });
    });
    $.get('/api/user/get_personal_info.php', { /*没有参数*/ }, function (data) {
        if (data.is_login) {
            $('#txt_username').text(data.username);
            $('#txt_username').attr('href', '/page/usrpage.php'); //个人资料页导航
            glb_user_name = data.username;
            if (data.identity >= 3) {
                $('#admin_link').removeAttr('hidden');
            }
        }
        else {
            window.location.href ='/page/login.html';
        }
    });
    $('#btn_logout').click(function () {
        $.post('/api/user/logout.php', { /*没有参数*/ }, function (data /*目前无用*/) {
            alert("成功退出登录");
            window.location.href = '/page/login.html';
        });
    });
