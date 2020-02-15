<?php /*a:2:{s:74:"/www/wwwroot/education.kedaweilai.com/application/admin/view/perm/add.html";i:1575450090;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>菜单添加</title>
    <meta name="keywords" content="菜单添加" />
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
<style>
    .form{
        padding: 10px 10px 0 10px;
        background: #fff;
    }
</style>
<form class="layui-form layui-form-pane form" action="" lay-filter="add">
    <div class="layui-form-item">
        <label class="layui-form-label">排序 </label>
        <div class="layui-input-inline">
            <input type="text" name="sortnum" lay-verify="number" placeholder="请输入序号" autocomplete="off" class="layui-input" value="0">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">菜单名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">父级菜单</label>
        <div class="layui-input-inline">
            <select name="pid" lay-search>
                <option value="0">顶级分类</option>
                <?php if($top_menu): foreach($top_menu as $menu): ?>
                <option value="<?php echo htmlentities($menu['id']); ?>" ><?php echo htmlentities($menu['name']); ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">控制器名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="controller" lay-verify="required" placeholder="请输入控制器名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">方法名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="action" lay-verify="required" placeholder="请输入方法名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">Class名称</label>
        <div class="layui-input-inline">
            <input type="text" name="iconfont"  placeholder="只支持iconfont样式class名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item" pane="" >
        <label class="layui-form-label" style="line-height: 20px">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="显示" checked="">
            <input type="radio" name="status" value="0" title="隐藏">
        </div>
    </div>
    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">是否是菜单项</label>
        <div class="layui-input-block">
            <input type="radio" name="is_menu" value="1" title="是" checked="">
            <input type="radio" name="is_menu" value="0" title="否">
        </div>
    </div>
    <div class="layui-form-item" style="text-align: center;">
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
                url: "<?php echo url('add'); ?>",
                dataType: "json",
                data:data.field,
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            parent.location.reload();
                            xadmin.del_tab();
                            xadmin.close();
                        });
                    }else if( data.code == 1 ){
                        layer.msg('添加菜单成功',{anim:0,shade:0.6},function () {
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
