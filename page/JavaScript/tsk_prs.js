//本Javascript文件由main.html task.html引用，用于在界面上展示任务。
//张笑语 11月9日 添加
//for debug only
g_usr_privilege = 2;
//
class Task {
    constructor(_content /*包含任务信息的json对象*/, callback,_node/*jquery选择器对象*/) {
        this.content = _content;
        this.callbackfunc = callback;
        this.node = _node;
        //回调函数必须接受两个参数，第一个是jquery节点对象，发出事件的任务所在的node。第二个是任务类型[1:任务被删除]
        this.take = function () {
            $.post('/api/task/operate_task.php',{type:'take',task_id:this.content.id},function(){
                alert('成功领取任务');
                window.location.reload();//刷新页面。之后回调做好了
            });
        };
        this.progress = function (time_hours) {

        };
        this.finish = function () {

        };
        this.delete = function () {
            $.post('/api/task/operate_task.php',{type:'delete',task_id:this.content.id},function(){
                alert('成功删除任务');
                window.location.reload();//刷新页面。之后回调做好了会改
            });
        };
        this.detail = function () {//弹对话框显示细节，包括任务报告
        };
        this.show = function () {

            this.node.append('<div class="task t-title">' + this.content.title + '</div>');
            this.node.append('<div class="task t-creator">创建者 :' + this.content.creator + ' </div>');

            if(this.content.status >=2 )//任务已经开始
            {
                this.node.append('<div class="task t-starttime">开始时间 : ' + this.content.start_date + ' </div>');
                this.node.append('<div class="task t-owner">执行者 : ' + this.content.owner + ' </div>');
            }
            if(this.content.status ==3)//任务已经完成
                this.node.append('<div class="task t-endtime">完成时间 : ' + this.content.finish_date + ' </div>');
            this.node.append('<div class="task t-close">预期完成时间 :' + this.content.close_date + ' </div>');
            var hours = this.content.hours_needed - this.content.hours_gone
            this.node.append('<div class="task t-hours">剩余小时 :' + hours.toString() + '</div>');
            //下面是一些操作
            this.node.append('<div class="op" style="visibility:hidden"><a class="t-detail"> 完整信息 </a>|<a class="t-progress">进展任务</a>|<a class="t-take">领取任务</a>|<a class="t-finish">完成任务</a>|<a class="t-delete">删除任务</a>');
            var operation_block = this.node.children('div.op');//上面刚刚创建的那个标签的对象
            if (this.content.status == 1) //未领取
            {
                operation_block.children('.t-progress').attr("hidden", "hidden");
                operation_block.children('.t-finish').attr("hidden", "hidden");
            }
            else if (this.content.status == 2) //进行中
            {
                operation_block.children('.t-take').attr("hidden", "hidden");
            }
            else //已完成
            {
                operation_block.children('.t-take').attr("hidden", "hidden");
                operation_block.children('.t-progress').attr("hidden", "hidden");
                operation_block.children('.t-finish').attr("hidden", "hidden");
            }

            operation_block.children('.t-detail').click(this.detail);

            if (/*g_usr_privilege < 2 */ false) { //一个在网页验证用户登陆时使用的全局变量
                //后端会验证用户有无权限，所以不怕用户自己修改js变量的值
                operation_block.children('.t-delete').attr("hidden", "hidden");
            }
            else {
                operation_block.children('.t-delete').click(this.delete);
            }
            //鼠标移到block上时，才显示相应操作
            operation_block.parent().hover(
            function(){operation_block.css('visibility','visible');} ,
            function(){operation_block.css('visibility','hidden');}
            );

        };
    }
}




class List {
    constructor(_node /*列表对应的html元素,jquery节点对象*/, _content /*包含所有任务的json数组 */) {
        this.node = _node;
        this.tasks = new Array();
        _content.forEach(tsk => { //创建所有任务对象
            _node.append('<div class="well well-task well-lg" task_id="'+tsk.id+'"></div>');
            this.tasks[tsk.id] =  new Task(tsk,null,_node.children(":last"));
        });
        this.ShowAllTasks = function()//展示所有任务
        {
            this.tasks.forEach(function(tsk) {
                
                tsk.show(_node.children('.well-task'));
            });
        };
        _node.click(function(){
            var target = $(event.target);
            var type = $(event.target).attr('class');
            if(target.parent.attr('class') != 'op')
                return;//如果不是点击操作任务的标签，则不返回任何值
            var task_id = target.parent.parent.attr('task_id');
            switch(type)
            {
            case 'delete':
                this.tasks[task_id].delete();
            break;
            case 'take':
                this.tasks[task_id].take();
            break;
            case 'progress':
            break;
            case 'finish':
            break;
            case 'detail':
            break;
            default:
                alert("Unrecognized Operation Type");
            }
        })
        this.DeleteATask()
        {

        }
    };

}
