<?php /*a:2:{s:81:"/www/wwwroot/education.kedaweilai.com/application/admin/view/vip/update_baby.html";i:1573626998;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1572258006;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>用户家庭住址编辑</title>
    <meta name="keywords" content="用户家庭住址编辑" />
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
        <label class="layui-form-label">宝宝姓名 </label>
        <div class="layui-input-inline">
            <input type="text" name="baby_name"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">英文姓名 </label>
        <div class="layui-input-inline">
            <input type="text" name="baby_en_name"   placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label" class="layui-input-inline">
            出生日期</label>
        <div class="layui-input-inline">
            <input class="layui-input"  autocomplete="off" placeholder="出生日期" name="babytime" id="start">

        </div>

    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label">
            宝宝性别</label>
        <div class="layui-input-inline">
            <select id="baby_sex" name="baby_sex" class="valid">
                <option value="0">未知</option>
                <option value="1">男</option>
                <option value="2">女</option>
            </select>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">宝宝学校</label>
        <div class="layui-input-inline">
            <input type="text" name="baby_school"  placeholder="请输入宝宝学校" autocomplete="off" class="layui-input">
        </div>
    </div>



    <input type="hidden" name="id">
    <div class="layui-form-item" style="    position: fixed;
    bottom: 0;
    left: 38%;">
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认修改</div>
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
                url: "<?php echo url('update_baby'); ?>",
                dataType: "json",
                data:data.field,
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            xadmin.del_tab();
                            xadmin.close();
                            location.href = "<?php echo url('login/index'); ?>"
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
            "id": "<?php echo htmlentities($sel['id']); ?>", // "name": "value"
            "baby_name": "<?php echo htmlentities($sel['baby_name']); ?>", // "name": "value"
            "baby_en_name": "<?php echo htmlentities($sel['baby_en_name']); ?>" ,// "name": "value"
            "baby_sex": "<?php echo htmlentities($sel['baby_sex']); ?>" ,// "name": "value"
            "baby_school": "<?php echo htmlentities($sel['baby_school']); ?>" ,// "name": "value"
            "babytime": "<?php echo htmlentities($sel['babytime']); ?>" ,// "name": "value"

        })
    });
</script>


