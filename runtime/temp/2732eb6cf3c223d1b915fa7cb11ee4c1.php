<?php /*a:2:{s:81:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\category\ls.html";i:1571970173;s:83:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\Public\header.html";i:1571656122;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <meta name="keywords" content="后台管理" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/source/kdwl_admin/css/font.css">
    <link rel="stylesheet" href="/source/kdwl_admin/css/login.css">
    <link rel="stylesheet" href="/source/kdwl_admin/css/xadmin.css">
    <script type="text/javascript" src="/source/kdwl_admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/source/kdwl_admin/js/vue2.5.16.min.js"></script>
    <script src="/source/kdwl_admin/lib/layui/layui.js" charset="utf-8"></script>
    <!--[if lt IE 9]>
    <script src="/source/kdwl_admin/js/html5.min.js"></script>
    <script src="/source/kdwl_admin/js/respond.min.js"></script>
    <script src="/source/kdwl_admin/js/vue2.5.16.min.js"></script>
    <![endif]-->
</head>
<script type="text/javascript" src="/source/kdwl_admin/js/xadmin.js"></script>
<body>
<div class="x-nav" id="app">
          <span class="layui-breadcrumb">
            <a href="JavaScript:void(0)">{{first_nav}}</a>
            <a href="JavaScript:void(0)">{{second_nav}}</a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" id="searchs">
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end">
                        </div>

                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="course_title" id="course_title" placeholder="请输入课程名称" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" type="button"  lay-filter="search" id="search"><i class="layui-icon">&#xe615;</i>查询</button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn" id="add_vip"  onclick="xadmin.open('添加课程分类','<?php echo url('add'); ?>',400,500)"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <table id="category" lay-filter="category"></table>
                </div>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="talk">课程评论</a>
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>

                </script>

                <script type="text/html" id="category_teacher">
                    <a href="#" onclick="xadmin.open('查看老师','<?php echo url('teacher_ls'); ?>',1000,300)" style="color:green">
                        {{d.teacher_name}}
                    </a>
                </script>

            </div>
        </div>
    </div>
</div>
</body>
<script>
    function icon(){
        var url = "<?php echo url('#'); ?>";
        xadmin.open('图标库',url,800,600);
    }
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "课程管理",
            second_nav: "课程列表",
        },
        methods: {},
        //监听属性
        watch: {

        },
        //计算属性
        computed: {

        }

    });
    layui.use(['table'], function(){
        var table = layui.table;
        //第一个实例
        table.render({
            elem: '#category'
            ,url: "<?php echo url('ls'); ?>" //数据接口
            ,page: true //开启分页
            ,cols: [
                [ //表头
                    {field: 'id', title: 'ID', width:80,}
                    ,{field: 'course_title', title: '课程名称'}
                    ,{field: 'course_short', title: '课程短标题'}
                    /*,{field: 'course_img', title: '课程封面'}*/
                    ,{field: 'course_img', title: '课程封面', minWidth: 150, align: "center",templet:function(d){
                        var img="<img src='"+d.course_img+"' style='width:30px;height:30px;' />";
                        return img;
                    },align: "center"}
                    ,{field: 'course_thumb', title: '课程轮播图'}
                    ,{field: 'edu_category_name', title: '课程分类',templet: '#category_type'}
                    ,{field: 'evaluate_total', title: '评价总次数'}
                    ,{field: 'course_description', title: '课程简介'}
                    ,{field: 'teacher_name', title: '课程老师',align:'center'}
                    ,{field: 'course_class', title: '班级说明'}
                    ,{field: 'course_total', title: '已报名人数'}
                    ,{field: 'agreement', title: '课程销售协议'}
                    ,{field: 'createtime', title: '创建时间'}
                    ,{field: 'status', title: '状态'}
                    ,{field: 'deleted', title: '是否删除',width:80}
                    ,{title:'操作', templet:'#barDemo'}
                ]
            ]
            ,id: 'category',
        });


        //监听工具条
        table.on('tool(category)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('update'); ?>?id="+data.id;
                xadmin.open('编辑会员',url,400,500)
            }else if(obj.event === 'del'){
                layer.confirm('确认要删除该管理员吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('delete'); ?>",
                        dataType: "json",
                        data:{id:data.id},
                        success: function(data, textStatus, request){
                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    xadmin.del_tab();
                                    xadmin.close();
                                    parent.location.href = "<?php echo url('login/index'); ?>"
                                });
                            }else if( data.code == 1 ){
                                layer.msg('删除会员成功',{anim:0,shade:0.6},function () {
                                    location.reload();
                                    xadmin.close();
                                });
                            }else{
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    return  false;
                                });
                            }
                        }
                    });
                    layer.close(index);
                });
            }else if(obj.event === 'talk'){
                var url = "<?php echo url('course_review'); ?>?id="+data.id;
                xadmin.open('课程评论列表',url,1000,600)
            }

        });




        //重载

        $('#search').on('click', function(){
            var type = $(this).data('type');
            var name = $('#course_title');
            var start = $('#start');
            var end = $('#end');
            //执行重载
            table.reload('category', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    name: name.val(),
                    start: start.val(),
                    end: end.val(),
                }
            }, 'data');
            active[type] ? active[type].call(this) : '';
        });
    });

    //日期范围选择
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        laydate.render({
            elem: '#start'
        });
        //日期范围选择
        laydate.render({
            elem: '#end'
        });
    })



</script>
</html>
