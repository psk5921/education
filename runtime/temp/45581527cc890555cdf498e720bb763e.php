<?php /*a:2:{s:72:"/www/wwwroot/education.kedaweilai.com/application/admin/view/vip/ls.html";i:1575450345;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>会员列表</title>
    <meta name="keywords" content="会员列表" />
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
                            <input class="layui-input"  autocomplete="off" placeholder="开始时间" name="start" id="start">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="结束时间" name="end" id="end">
                        </div>

                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="nickname" id="nickname" placeholder="请输入会员昵称" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" type="button"  lay-filter="search" id="search"><i class="layui-icon">&#xe615;</i>查询</button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <!--<button class="layui-btn" id="add_vip"  onclick="xadmin.open('添加会员','<?php echo url('add'); ?>',550)"><i class="layui-icon"></i>添加</button>-->
                </div>
                <div class="layui-card-body">
                    <table id="vip" lay-filter="vip"></table>
                </div>

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
            second_nav: "会员列表",
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
            elem: '#vip'
            ,url: "<?php echo url('ls'); ?>" //数据接口
            ,page: true //开启分页
            ,cols: [
                [ //表头
                    {field: 'id', title: 'ID', align:'center',width:80}
                    ,{field: 'nickname', title: '会员昵称',align:'center'}
                    ,{field: 'mobile', title: '手机号码',align:'center',width:130}
                    ,{field: 'avatar', title: '用户头像', minWidth: 150, align: "center",templet:function(d){var img="<img src='"+d.avatar+"' style='width:80px;height:80px;' />";
                    return img;}}
                    ,{field: 'gender', title: '性别',align:'center',width:80}
                    ,{field: 'credit', title: '会员积分',align:'center',width:130}
                    ,{field: 'createtime', title: '注册时间',align:'center'}
                    ,{field: 'identity', title: '身份标识' ,align:'center',width:80}
                    ,{field: 'is_verification', title: '能否核销',align:'center',width:80}
                    ,{title:'操作', templet:'#barDemo' ,templet:function(d){
                        return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>\n'/* +
                        ' <a class="layui-btn layui-btn-xs" lay-event="address" data-id="'+d.id+'">家庭住址</a>\n'*/ + '<a class="layui-btn layui-btn-xs" lay-event="jifen">积分记录</a>\n' +
                            '<a class="layui-btn layui-btn-xs" lay-event="baby">会员宝宝</a>\n' /*+
                            '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>'*/
                    },align:'center',width:'20%'}
                ]
            ]
            ,id: 'vip',
        });


        //监听工具条
        table.on('tool(vip)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('update'); ?>?id="+data.id;
                location.href =url;
                //xadmin.open('编辑会员',url,600)
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
            }else if(obj.event === 'baby'){
                var url = "<?php echo url('baby_list'); ?>?id="+data.id;
                location.href =url;
               // xadmin.open('会员宝宝',url,1000,600)
            }else if(obj.event === 'address'){
                var url = "<?php echo url('address_ls'); ?>?id="+data.id;
                location.href =url;
               // xadmin.open('家庭住址',url,1000,600)
            }else if(obj.event === 'jifen') {
                var url = "<?php echo url('jifen_ls'); ?>?id="+data.id;
                location.href =url;
              //  xadmin.open('积分记录', url, 1000, 600)

            }
        });












        //重载

        $('#search').on('click', function(){
            var name = $('#nickname');
            var start = $('#start');
            var end = $('#end');
            //执行重载
            table.reload('vip', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    name: name.val(),
                    start: start.val(),
                    end: end.val(),
                }
            }, 'data');
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
