<?php /*a:2:{s:81:"/www/wwwroot/education.kedaweilai.com/application/admin/view/vip/add_address.html";i:1571992658;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1571970586;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>管理员添加</title>
    <meta name="keywords" content="管理员添加" />
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
<style>
    .form{
        padding: 10px 10px 0 10px;
        background: #fff;
    }
</style>
<form class="layui-form layui-form-pane form" action="" lay-filter="add">
    <div class="layui-form-item">
        <label class="layui-form-label">省份 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="province" lay-verify="required" placeholder="请输入省份" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">市 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="city" lay-verify="required" placeholder="请输入市" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">详细地址 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="address" lay-verify="required" placeholder="请输入详细地址" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">区 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="country" lay-verify="required" placeholder="请输入区" autocomplete="off" class="layui-input">
        </div>
    </div>


 



    <div class="layui-form-item" style="    position: fixed;
    bottom: 0;
    left: 38%;">
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认</div>
    </div>
</form>

<script>
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer
        //监听提交
        form.on('submit(submits)', function(data){
            $.ajax({
                type: "post",
                url: "<?php echo url('add_address'); ?>",
                dataType: "json",
                data:data.field,
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            parent.location.href = "<?php echo url('login/index'); ?>"
                            xadmin.close();
                        });
                    }else if( data.code == 1 ){
                        layer.msg('添加会员成功',{anim:0,shade:0.6},function () {
                            parent.location.reload();
                            xadmin.close();
                        });
                    }else{
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            return  false;
                        });
                    }
                }
            });
            /*layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            })*/
            return false;
        });

        //表单初始赋值
        /*  form.val('add', {
              "username": "贤心" // "name": "value"
              ,"password": "123456"
              ,"interest": 1
              ,"like[write]": true //复选框选中状态
              ,"close": true //开关状态
              ,"sex": "女"
              ,"desc": "我爱 layui"
          })*/


    });
</script>
