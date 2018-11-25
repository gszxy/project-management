张笑语 2018年11月  
本网站已在网络上发布，地址为：https://prj.knightdusk.cn [这个是我以前注册的域名]
为保证项目文件的相对完整，且html文件的样式不发生太大变化，上交的作业中没有移除所有开源组件。详情见下方开源组件一览。
项目的github地址是https://github.com/gsTanthalas/project-management
小组成员：张笑语 苏子童 高宇晨 杨绍然 陈奕帆

目录
1.项目文件结构说明
2.项目中引用的开源组件一览

根目录下各个文件夹的主要内容是：
A.api   后端接口。此文件夹内提供前端程序获取数据所调用的接口
B.asset 资源文件。包括项目中使用的图片素材和开源UI组件
C.page  包括所有前端网页和js文件。特别指出，加载任务页面的逻辑位于/page/JavaScript/tsk_prs.js
D.utility 后端类和数据库访问层
  utility/basic  数据库连接，session和cookie的处理
  utility/misc   无实质内容，原来规划用于放置一些没有实现的小功能
  utility/mysql  数据库访问层
  utility/user   各个用户类。是后端的核心业务逻辑所在。

开源组件引用一览：
jQuery     基础JavaScript库
Bootstrap  基于jQuery的响应式网页框架
Bootstrap-datetimepicker 一款日期和时间选择器
flatui     一款基于bootstrap的扁平化风格ui组件
Datatables 一款用于生成美观的，拥有分页、排序等功能的表格的组件。
Echarts    一款用于生成各种图表的组件
qcloud-sdk 腾讯云开发工具。我们的服务器使用了腾讯云的服务器，文件管理部分功能使用了其提供的sdk来实现。
           此sdk的代码在上交的作业中已经移除。
