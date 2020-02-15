<?php /*a:2:{s:75:"/www/wwwroot/education.kedaweilai.com/application/admin/view/role/perm.html";i:1575467571;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1572258006;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>分配权限</title>
    <meta name="keywords" content="分配权限" />
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
    .border{
        border: 1px solid #ccc;
    }
    .pad20{
        padding:10px 20px;
    }
    .line{
        height: 40px;
        line-height: 40px;
        padding-left: 20px;
        background: #f7f5f6;
    }
    .line2{
        margin-bottom: 10px;
    }
</style>
<form class="layui-form layui-form-pane form" action="" lay-filter="perm">
    <?php if($perm): foreach($perm as $first): ?>
    <div class="foreach">
    <div class="border ">
        <p class="line first"> <input type="checkbox" name="perm[]" lay-skin="primary" title="<?php echo htmlentities($first['name']); ?>" value="<?php echo htmlentities($first['id']); ?>" <?php if(in_array($first['id'],$menu_id)): ?> checked <?php endif; ?> lay-filter="check1"></p>
        <!--<p class="line"><?php echo htmlentities($first['name']); ?></p>-->

    </div>
    <?php if($first['child']): ?>
    <div class="content">
    <?php foreach($first['child'] as $second): ?>
    <div class="pad20">
        <p class="line2 second"> <input type="checkbox" name="perm[]" lay-skin="primary" title="<?php echo htmlentities($second['name']); ?>" value="<?php echo htmlentities($second['id']); ?>" <?php if(in_array($second['id'],$menu_id)): ?> checked <?php endif; ?>   lay-filter="check2"></p>
        <?php if($second['child']): ?>
        <p class="third">
        <?php foreach($second['child'] as $third): ?>
            <input type="checkbox" name="perm[]" lay-skin="primary" title="<?php echo htmlentities($third['name']); ?>" value="<?php echo htmlentities($third['id']); ?>" <?php if(in_array($third['id'],$menu_id)): ?> checked <?php endif; ?> lay-filter="check3">
        <?php endforeach; ?>
        </p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <input type="hidden" name="id" value="<?php echo htmlentities($id); ?>">
    <div class="layui-form-item" style="   text-align: center">
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认</div>
    </div>
</form>
<script>
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer

        //一级菜单
        form.on('checkbox(check1)', function(data){
            var obj1 = data.othis.parent('p.first').parent().next('.content').find('p.second');
            var obj2 = data.othis.parent('p.first').parent().next('.content').find('p.third');
            if(data.elem.checked){
                obj1.each(function(){
                    $(this).find('input').prop('checked',true);
                });
                obj2.each(function(){
                    $(this).find('input').prop('checked',true);
                });
            }else{
                obj1.each(function(){
                    $(this).find('input').prop('checked',false);
                });
                obj2.each(function(){
                    $(this).find('input').prop('checked',false);
                });
            }
            form.render();
        });
        //二级菜单
        form.on('checkbox(check2)', function(data){
            var obj = data.othis.parent('p.second').next('p.third').find('input');
            if(data.elem.checked){
                obj.each(function(){
                    $(this).prop('checked',true);
                });
            }else{
                obj.each(function(){
                    $(this).prop('checked',false);
                });

            }
            form.render();
            checkall($(this).parentsUntil('div.foreach'));
        });
        //三级菜单
        form.on('checkbox(check3)', function(data){
            var obj = data.othis.parent('p.third').find('input');
            var length = data.othis.parent('p.third').find('input').length;
            var i = 0;
            obj.each(function(){
                if(!$(this).prop('checked')){
                    i++;
                }
            });
            if(i==length){
                data.othis.parent('p.third').siblings('p.second').find('input').prop("checked", false)
            }else{
                data.othis.parent('p.third').siblings('p.second').find('input').prop("checked", true);
            }
            form.render();
            checkall($(this).parentsUntil('div.foreach'));
        });

        function checkall(obj){
            var obj1 = obj.find('p.second input');
            var length1 = obj.find('p.second input').length;
            var obj2 =obj.find('p.third input');
            var length2 =obj.find('p.third input').length;
            var i = 0,j=0;
            obj1.each(function(){
                if(!$(this).prop('checked')){
                    i++;
                }
            });
            obj2.each(function(){
                if(!$(this).prop('checked')){
                    j++;
                }
            });
            var r1 = i+j;
            var r2 = length1+length2;
            if( r1 ==  r2 ){
              obj.prev().find("p.first>input").prop("checked", false);
            }else{
              obj.prev().find("p.first>input").prop("checked", true);
            }
            form.render();
        }

        //监听提交
        form.on('submit(submits)', function(data){
            $.ajax({
                type: "post",
                url: "<?php echo url('perm'); ?>",
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
                        layer.msg('分配权限成功',{anim:0,shade:0.6},function () {
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
