<?php /*a:2:{s:85:"/www/wwwroot/education.kedaweilai.com/application/admin/view/order/good_order_ls.html";i:1575902303;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>课程订单</title>
    <meta name="keywords" content="课程订单" />
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
                            <input type="text" name="title" id="title" placeholder="请输入订单编号" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <select id="status" lay-search>
                                <option value="">请选择订单状态</option>
                                <option value="-1">未支付</option>
                                <option value="1">已支付</option>
                            </select>
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" type="button"  id="search"><i class="layui-icon">&#xe615;</i>查询</button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn" id="add_vip"  onclick="xadmin.open('添加课程订单','<?php echo url('good_order_add'); ?>',455,600)"><i class="layui-icon"></i>添加课程订单</button>
                </div>
                <div class="layui-card-body ">
                    <table id="order" lay-filter="order"></table>
                </div>
                <script type="text/html" id="barDemo">

                    {{# if(d.status == 0 ){ }}
                    <a class="layui-btn layui-btn-xs" lay-event="payment">编辑</a>
                    {{# } }}
                    {{# if(d.status == 1 ){ }}
                    <a class="layui-btn layui-btn-xs" lay-event="edit_contract">预览合同</a>
                   <!-- <span style="color:#23c6c8">暂无操作</span>-->
                    {{# } }}
                   <!-- <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>-->
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
            first_nav: "订单管理",
            second_nav: "课程订单",
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
            elem: '#order'
            ,url: "<?php echo url('good_order_ls'); ?>" //数据接口
            ,page: true //开启分页
            ,cols: [
                [ //表头
                    /*{field: 'id', title: 'ID', align:'center'}*/
                    {field: 'ordersn', title: '订单编号', align:'center',width:'15%'}
                    ,{field: 'nickname', title: '用户名', align:'center',width:'120'}
                    ,{field: 'mobile', title: '联系方式', align:'center',width:'120'}
                    ,{field: 'baby_name', title: 'Baby',align:'center',width:'100'}
                    ,{field: 'course_title', title: '课程名称', align:'center',width:'22%'}
                    ,{field: 'package_time', title: '课程次数', align:'center',width:'80'}
                    ,{field: 'course_end', title: '结束时间', align:'center',width:'120'}
                    ,{field: 'price', title: '订单费用', align:'center',width:'100'}
                  /*  ,{field: 'class_name', title: '班级信息', align:'center'}
                    ,{field: 'package', title: '套餐信息', align:'center'}
                    ,{field: 'time', title: '上课信息', align:'center'}
                    ,{field: 'pay_type', title: '订单类型', align:'center'}*/
                    ,{field: 'pay_type', title: '订单类型', align:'center',width:'100'}
                    ,{field: 'createtime', title: '下单时间', align:'center',width:'107'}
                    ,{field: 'status_desc', title: '状态', align:'center',width:'100'}
                    ,{title:'操作', templet:'#barDemo', align:'center',width:'100'}

                ]
            ]
            ,id: 'order',
        });


        //监听工具条
        table.on('tool(order)', function(obj){
            var data = obj.data;
            console.log(obj.event);
            if(obj.event === 'payment'){
                layer.confirm('确认该课程订单付款吗？操作不可逆哦！！',{title:'提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('course_payment'); ?>",
                        dataType: "json",
                        data:{id:data.id},
                        success: function(data, textStatus, request){

                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    parent.location.reload();
                                    xadmin.del_tab();
                                    xadmin.close();
                                    //parent.location.href = "<?php echo url('login/index'); ?>"
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
            }else if( obj.event === "edit_contract"){
                 xadmin.open("预览合同","<?php echo url('edit_contract'); ?>?id="+data.id,'','',true);
            }
        });




        //重载

        $('#search').on('click', function(){
            var name = $('#title');
            var start = $('#start');
            var end = $('#end');
            var status = $('#status');
            //执行重载
            table.reload('order', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    name: name.val(),
                    start: start.val(),
                    end: end.val(),
                    status: status.val(),
                }
            }, 'data');
            //active[type] ? active[type].call(this) : '';
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
