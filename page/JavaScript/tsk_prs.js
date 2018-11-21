//本Javascript文件由main.html task.html引用，用于在界面上展示任务。
//张笑语 11月9日 添加
//for debug only
g_usr_privilege = 2;
//
class Task {
    constructor(_content /*包含任务信息的json对象*/, list/*此参数暂时废弃 */,_node/*jquery选择器对象*/) {
        this.content = _content;
        this.list_of_task = list;
        this.node = _node;
        
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
                if(glb_user_name != this.content.owner)
                    operation_block.children('.t-finish').attr("hidden", "hidden");
                //只能完成自己的任务                    
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
            this.tasks[tsk.id] =  new Task(tsk,this,_node.children(":last"));
            var operation_block = _node.children(":last").children('.op');

        });
        this.tasks.sort((a,b) => {
            return a.content.id > b.content.id;
        })
        this.ShowAllTasks = function()//展示所有任务
        {
            this.tasks.forEach(function(tsk) {
                
                tsk.show(_node.children('.well-task'));

            });
        };

    }
    TryRemoveTaskFromList(task_id)
    {

    }
}
        /*
        _node.click(function(){
            var target = $(event.target);
            var optype = target.text;
            //这里使用了一个非常愚蠢的办法，就是用text来辨识操作类型
            //但是不知道为什么，其它的属性，比如class,id都无法从这里获得
            //只好出此下策
            if(target.parent.attr('class') != 'op')
                return;//如果不是点击操作任务的标签，则不返回任何值
            var task_id = target.parent.parent.attr('task_id');
            switch(optype)
            {
            case '删除任务':
                this.tasks[task_id].delete();
            break;
            case 't-take':
                this.tasks[task_id].take();
            break;
            case 't-progress':
                this.tasks[task_id].progress();
            break;
            case 't-finish':
                this.tasks[task_id].finish();
            break;
            case 't-detail':
                this.tasks[task_id].detail();
            break;
            default:
                alert("Unrecognized Operation Type");
            }
        });
*/
     //首先定义操作。。。

    //++++++++++++++++++++++++++++++=
function OperateTask(type,task_id,success_callback) {
    $.post('/api/task/operate_task.php',{type:type,task_id:task_id},function(){
        //此处添加操作任务后所有共性的操作

        //下面调用由每个操作提供的回调
        success_callback();
    });
}
function BindAllTaskMethods ()
{


    $('.t-delete').click(function(){
        var id = $(this).parents(".well-task").attr('task_id');
        OperateTask('delete',id,function(){
            alert("成功删除任务");
            window.location.reload();
        }); 
    });
    $('.t-take').click(function(){
        var id = $(this).parents(".well-task").attr('task_id');
        OperateTask('take',id, function(){
            alert("成功领取任务");
            window.location.reload();
        }); 
    });
    $('.t-progress').click(function(){
        var id = $(this).parents(".well-task").attr('task_id');
        OperateTask('progress',id); 
    });
    $('.t-finish').click(function(){//请优先测试完成任务的功能
        var id = $(this).parents(".well-task").attr('task_id');
        OperateTask('finish',id,function(){
            alert("已经将任务标记为完成");
            window.location.reload();
        }); 
    });
    $('.t-delete').click(function(){
        var id = $(this).parents(".well-task").attr('task_id');
        OperateTask('delete',id); 
	});
}


