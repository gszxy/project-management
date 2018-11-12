<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>项目管理</title>
<meta charset="UTF-8">
<link href="/page/css/bootstrap.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
  <div class="container">
	  <h2 style="align-content: center">登录项目管理平台</h2>
	  <span>张笑语组/第15组</span>
	  <div class="row">
		  <div class="col-lg-4"></div>
		  <div class="col-lg-4" style="align-content: center">
			  <form action="page/func/checklogin.php" onsubmit="return validateForm()" name="login_form" id="flg" method="post">	
				  <input type="hidden" name="captcha" id="cap_input">
				  <div>
					  <label for="username">用户名</label>
					  <input type="text" class="input-group-lg" name="username">
				  </div>
				  <div>
					  <label for="username">密码&nbsp;&nbsp;</label>
					  <input type="password" class="input-group-lg" name="password">
					  <button type="button" class="btn btn-block" id="TencentCaptcha" data-appid="2100105972" data-cbfn="callback">点击验证</button>  
					  <input type="submit"  class="btn btn-default" value="登录" ></input>
				  </div>
			  </form>

		  </div>
		  <div class="col-lg-4"></div>

		  <span id="cap_re"></span>
	  </div>	  
	  
	  
  </div>
<h1>项目管理平台 测试首页</h1>
	
<script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>
<script>
    window.onload = function(){is_captcha_successful = 0}
	window.callback = function(res){
    console.log(res);
    // res（未通过验证）= {ret: 1, ticket: null}
    // res（验证成功） = {ret: 0, ticket: "String", randstr: "String"}

    if(res.ret === 0)
	{
		$("#cap_re").text("验证成功");
		$("#cap_input").text(JSON.stringify(res));
		is_captcha_successful = 1;
  		} // 票据
	else
	{
		$("#cap_re").text("验证失败，请重试");
		is_captcha_successful = 0;
	}
    };

function validateForm()
{
	var x=document.forms["login_form"]["password"].value;
	var y=document.forms["login_form"]["username"].value;

    if (x==null || x=="")
  {
    alert("必须填写密码");
    return false;
  }
	else if (y==null || y =="")
  {
    alert("必须填写用户名");
    return false;
  }
	
	if(is_captcha_successful!=1)
	{
	   alert("请进行验证码验证");
       return false;
	}
	return true;
}
</script>
</body>
</html>