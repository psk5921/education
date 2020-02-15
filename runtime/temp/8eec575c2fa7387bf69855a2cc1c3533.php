<?php /*a:2:{s:81:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\user\update.html";i:1571656122;s:83:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\Public\header.html";i:1571656122;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>编辑管理员</title>
    <meta name="keywords" content="编辑管理员" />
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
        <label class="layui-form-label">管理员名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="username" lay-verify="required" placeholder="请输入管理员名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">登录密码 </label>
        <div class="layui-input-inline">
            <input type="password" name="password"  placeholder="不填不修改密码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
            <?php if($role): ?>
            <select name="role_id" lay-filter="role_id" lay-search>
                <option value="">请选择角色</option>
                <?php foreach($role as $item): ?>
                <option value="<?php echo htmlentities($item['id']); ?>" <?php if($item['id'] == $user['role_id']): ?> selected <?php endif; ?>><?php echo htmlentities($item['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php else: ?>
            <input type="text"  name="role_id" autocomplete="off" class="layui-input" placeholder="请先去添加角色"  disabled>
            <?php endif; ?>
        </div>
    </div>
    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">账号状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="正常">
            <input type="radio" name="status" value="2" title="限制登录">
        </div>
    </div>


    <input type="hidden" name="id" value="">
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
                        layer.msg('修改管理员成功',{anim:0,shade:0.6},function () {
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
              "username": "<?php echo htmlentities($user['username']); ?>",
              "id": "<?php echo htmlentities($user['id']); ?>",
              "status": "<?php echo htmlentities($user['status']); ?>",
          })
    });
</script>