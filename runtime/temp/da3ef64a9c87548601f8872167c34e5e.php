<?php /*a:2:{s:83:"/www/wwwroot/education.kedaweilai.com/application/admin/view/appointment/lists.html";i:1575449697;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/Public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>会员预约</title>
    <meta name="keywords" content="会员预约" />
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

 <!--   <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.config.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.all.min.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/lang/zh-cn/zh-cn.js"></script>-->
   
</head>

<script type="text/javascript" src="/source/kdwl_admin/js/xadmin.js"></script>
<body>
<style>
    .layui-table-cell {
        height: auto;
        min-height: 28px;
        line-height: 28px;
        padding: 0 15px;
        position: relative;
        box-sizing: border-box;
    }
</style>
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
                            <input class="layui-input"  autocomplete="off" placeholder="预约开始时间" name="start" id="start">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="预约结束时间" name="end" id="end">
                        </div>

                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="name" id="name" placeholder="请输入会员昵称" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" type="button"  lay-filter="search" id="search"><i class="layui-icon">&#xe615;</i>查询</button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table id="appointment" lay-filter="appointment"></table>
                </div>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="preview">查看预约信息</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>

                </script>



            </div>
        </div>
    </div>
</div>
</body>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "会员管理",
            second_nav: "会员预约",
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
            elem: '#appointment'
            ,url: "<?php echo url('lists'); ?>" //数据接口
            ,page: true //开启分页
            ,cols: [
                [ //表头
                     {field: 'uid', title: '会员昵称',align: "center"}
                    ,{field: 'tid', title: '教师名称',align: "center"}
                    ,{field: 'free_id', title: '上课时间',align: "center"}
                    ,{field: 'hope_time', title: '希望上门时间',align:'center'}
                    ,{field: 'mobile', title: '联系方式',align:'center'}
                    ,{field: 'baby_sex', title: '宝宝性别',align:'center'}
                    ,{field: 'baby_name', title: '宝宝中文名称',align:'center'}
                    ,{field: 'baby_en_name', title: '宝宝英文名称',align:'center'}
                    ,{field: 'birth', title: '宝宝生日',align:'center'}
                    /*,{field: 'baby_school', title: '宝宝学校',align:'center'}
                    ,{field: 'address', title: '家庭住址',align:'center'}
                    ,{field: 'remarks', title: '备注',align:'center'}*/
                     ,{field: 'createtime', title: '预约时间',align:'center'}
                    ,{title:'操作', templet:'#barDemo',align:'center',width:"15%"}
                ]
            ]
            ,id: 'appointment',
        });


        //监听工具条
        table.on('tool(appointment)', function(obj){
            var data = obj.data;
            if(obj.event === 'preview'){
                var url = "<?php echo url('preview'); ?>?id="+data.id;
                location.href = url;
                //xadmin.open('查看预约信息',url,450,500)
            }else if(obj.event === 'del'){
                layer.confirm('确认要删除该预约信息吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('deletes'); ?>",
                        dataType: "json",
                        data:{id:data.id},
                        success: function(data, textStatus, request){
                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    parent.location.reload();
                                    xadmin.del_tab();
                                    xadmin.close();
                                });
                            }else if( data.code == 1 ){
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
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
            }

        });




        //重载

        $('#search').on('click', function(){
            var type = $(this).data('type');
            var name = $('#name');
            var start = $('#start');
            var end = $('#end');
            //执行重载
            table.reload('appointment', {
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
