<?php /*a:2:{s:82:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\clbum\update.html";i:1571810483;s:83:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\public\header.html";i:1571656122;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>课程分类编辑</title>
    <meta name="keywords" content="课程分类编辑" />
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
<form class="layui-form layui-form-pane form" action="" lay-filter="update">
    <div class="layui-form-item">
        <label class="layui-form-label">班级名称 </label>
        <div class="layui-input-inline">
            <input type="text" name="class_name"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>


    <div class="layui-form-item">
        <label for="username" class="layui-form-label">
            <span class="x-red">*</span>课程名称</label>
        <div class="layui-input-inline">
            <select id="cid" name="cid" class="valid">
            <?php if(is_array($kecheng) || $kecheng instanceof \think\Collection || $kecheng instanceof \think\Paginator): $i = 0; $__LIST__ = $kecheng;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['course_title']); ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>


    <div class="layui-form-item">
        <label for="username" class="layui-form-label">
            <span class="x-red">*</span>状态</label>
        <div class="layui-input-inline">
            <select id="status" name="status" class="valid">
                <option value="0">等待上架</option>
                <option value="1">上架</option>
                <option value="0">下架</option>
            </select>
        </div>
    </div>


    <div class="layui-form-item">
        <label for="username" class="layui-form-label">
            <span class="x-red">*</span>是否删除</label>
        <div class="layui-input-inline">
            <select id="deleted" name="deleted" class="valid">
                <option value="0">正常</option>
                <option value="1">删除</option>
            </select>
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
                url: "<?php echo url('update'); ?>",
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
            "class_name": "<?php echo htmlentities($sel['class_name']); ?>", // "name": "value"
            "createtime": "<?php echo htmlentities($sel['createtime']); ?>" ,// "name": "value"
            "status": "<?php echo htmlentities($sel['status']); ?>" ,// "name": "value"
            "deleted": "<?php echo htmlentities($sel['deleted']); ?>" ,// "name": "value"
            "course_title":"<?php echo htmlentities($sel['course_title']); ?>",
        })
    });
</script>
