<?php /*a:2:{s:88:"/www/wwwroot/education.kedaweilai.com/application/admin/view/clbum/classtime_update.html";i:1573652202;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1572258006;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>上课时间编辑</title>
    <meta name="keywords" content="上课时间编辑" />
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

    <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.config.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.all.min.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/lang/zh-cn/zh-cn.js"></script>
   
</head>
<script type="text/javascript" src="/source/kdwl_admin/js/xadmin.js"></script>
<style>
    .form{
        padding: 10px 10px 0 10px;
        background: #fff;
    }
</style>
<form class="layui-form layui-form-pane form" action="" lay-filter="update">

    <div class="layui-form-item">
        <label  class="layui-form-label">
            排序</label>
        <div class="layui-input-inline">
            <input type="text" name="displayorder" value="0" autocomplete="off" class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">上课时间 <span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="text" name="time" placeholder="请输入上课时间" autocomplete="off" class="layui-input" lay-verify="required">
        </div>
    </div>






    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">状态 <span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="等待上架 " checked="checked">
            <input type="radio" name="status" value="1" title="上架">
            <input type="radio" name="status" value="2" title="下架">
        </div>
    </div>

    <input type="hidden" name="class_id">
    <input type="hidden" name="id" >
    <div class="layui-form-item" style="text-align: center">
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认</div>
    </div>
</form>

<script>

    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer
        form.on('radio(type)', function(data){
            var $val = data.value;
            if($val == 1){
                $('#start_week').show();
                $('#end_week').hide();
            }else if($val == 2){
                $('#start_week').show();
                $('#end_week').show();
            }else{
                $('#start_week').hide();
                $('#end_week').hide();
            }
        });
        //监听提交
        form.on('submit(submits)', function(data){
            $.ajax({
                type: "post",
                url: "<?php echo url('classtime_update'); ?>",
                dataType: "json",
                data:data.field,
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            xadmin.del_tab();
                            xadmin.close();
                            parent.location.href = "<?php echo url('login/index'); ?>"
                        });
                    }else if( data.code == 1 ){
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
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
        form.val('update', {
            "id": "<?php echo htmlentities($id); ?>", // "name": "value"
            "class_id": "<?php echo htmlentities($class_id); ?>", // "name": "value"
            "time": "<?php echo htmlentities($sel['time']); ?>", // "name": "value"
            "status": "<?php echo htmlentities($sel['status']); ?>", // "name": "value"
        })
    });



    //日期范围选择
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        laydate.render({
            elem: '#start'
            ,type: 'time'
        });
        //日期范围选择
        laydate.render({
            elem: '#end'
            ,type: 'time'
        });
    })
</script>


