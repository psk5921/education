<?php /*a:2:{s:80:"/www/wwwroot/education.kedaweilai.com/application/admin/view/teacher/update.html";i:1575450212;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>教师编辑</title>
    <meta name="keywords" content="教师编辑" />
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
<script type="text/javascript" src="/source/kdwl_admin/js/jquery.min.js"></script>
<style>
    .form{
        padding: 10px 10px 0 10px;
        background: #fff;
    }
    .ft30{
        font-size: 30px!important;
    }
</style>
<div class="x-nav" id="app">
          <span class="layui-breadcrumb">
            <a href="JavaScript:void(0)">{{first_nav}}</a>
            <a href="JavaScript:void(0)">{{second_nav}}</a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right;margin-right: 15px;"
       onclick="location.href='<?php echo url('ls'); ?>'" title="返回">
        <i class="layui-icon layui-icon-return" style="line-height:30px"></i></a>

</div>
<form class="layui-form layui-form-pane form" action="" lay-filter="update">

    <div class="layui-form-item">
        <label class="layui-form-label">教师名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="teacher_name" lay-verify="required" placeholder="请输入教师名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">关联会员 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text"  value="<?php echo htmlentities($user['info']); ?>" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>


    <!---->
    <div class="layui-form-item">
        <label class="layui-form-label">教师封面 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <div class="layui-upload-drag" id="simple">
                <i class="layui-icon ft30"></i>
                <p>点击上传，或将文件拖拽到此处</p>
            </div>
            <div class="layui-form-mid layui-word-aux" style="color: red!important">温馨提示：仅支持jpg|png|jpeg格式的图片且文件大小小于2M</div>
            <?php if($sel['teacher_img']): ?>
            <div style="margin-top:15px;" id="simple_img">
                <a href="<?php echo htmlentities($sel['teacher_img']); ?>" target="_blank"><img src="<?php echo htmlentities($sel['teacher_img']); ?>" class="layui-upload-img" width="190px" height="148px"></a>
            </div>
            <?php else: ?>
            <div style="margin-top:15px;display: none" id="simple_img">
            </div>
            <?php endif; ?>

            <input type="hidden" name="teacher_img">
        </div>
    </div>
    <!---->

    <div class="layui-form-item">
        <label  class="layui-form-label">
            所教科目 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="teacher_subject" name="teacher_subject" class="valid" lay-search>
                <option value="1">English</option>
                <option value="2">Maths</option>
                <option value="3">History</option>
                <option value="4">Economics</option>
                <option value="5">ILETS</option>
                <option value="6">SAT</option>
                <option value="7">Physics</option>
                <option value="8">Art</option>
                <option value="9">Spanish</option>
                <option value="10">French</option>
                <option value="11">Business English</option>
                <option value="12">Travel English</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label  class="layui-form-label">
            课程分类<span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="category_id" name="category_id" class="valid">
                <?php if(is_array($types) || $types instanceof \think\Collection || $types instanceof \think\Paginator): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['edu_category_name']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教师描述 <span class="x-red">*</span></label>
        <div class="layui-input-inline" style="width: 600px">
            <input type="text" name="teacher_short" lay-verify="required" placeholder="请输入教师描述" autocomplete="off" class="layui-input">
            <!-- <textarea name="teacher_short" id="" cols="40" rows="5" placeholder="请输入教师描述" autocomplete="off"></textarea>-->
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label">点赞数量 </label>
        <div class="layui-input-inline">
            <input type="text" name="zans"  placeholder="请输入点赞数量" autocomplete="off" class="layui-input">
        </div>
    </div>







    <div class="layui-form-item">
        <label class="layui-form-label">教师年龄 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="teacher_age" lay-verify="required" placeholder="请输入教师年龄" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教师国籍 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="teacher_nation" lay-verify="required" placeholder="请输入教师国籍" autocomplete="off" class="layui-input">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label">教师简介 <span class="x-red">*</span></label>
        <div class="layui-input-block">
          <textarea name="teacher_introduce" id="teacher_introduce" lay-verify="teacher_introduce"
                    style="display: none;"></textarea>
        </div>
    </div>
    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">状态 <span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="正常" checked>
            <input type="radio" name="status" value="1" title="禁用">
        </div>
    </div>

    <input type="hidden" name="id">
    <div class="layui-form-item" style="text-align: center">
        <div class="layui-btn " style="width: 100px;height: 40px;line-height: 40px"
             onclick="location.href='<?php echo url('ls'); ?>'">返回列表
        </div>
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">
            确认
        </div>
    </div>
</form>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "教师列表",
            second_nav: "教师编辑",
        },
        methods: {},
        //监听属性
        watch: {

        },
        //计算属性
        computed: {

        }

    });
    layui.use(['form', 'layedit', 'upload', 'jquery'], function(){
        var form = layui.form
            , layer = layui.layer
            , $ = layui.jquery
            , layedit = layui.layedit
            , upload = layui.upload;
        layedit.set({
            uploadImage: {
                url: "<?php echo url('uploadTeacher'); ?>" //接口url
                , type: 'post' //默认post
            }
        });

        form.render(null, 'submits');
        var index = layedit.build('teacher_introduce'); //建立编辑器
        //核心代码就是这一条   进行赋值	请求语句我就省略
        layedit.setContent(index, '<?php echo $sel['teacher_introduce']; ?>');
        //自定义验证规则
        form.verify({
            teacher_introduce: function (value) {
                layedit.sync(index);
            }
        });

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
                            parent.location.reload();
                            xadmin.del_tab();
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
        //拖拽上传
        var muls = '';
        upload.render({
            elem: '#simple'
            , url: "<?php echo url('simple'); ?>"
            , accept: 'images'
            , exts: 'jpg|png|jpeg'
            , size: 2097152
            /*,before: function(obj){
                //预读本地文件示例，不支持ie8
                $('#simple_img').show();
                $('#simple_img').html('');
                obj.preview(function(index, file, result){
                    $('#simple_img').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img" width="190px" height="148px">')
                });
            }*/
            , done: function (res) {
                if(res.code !=0){
                    layer.open({
                        type: 4,
                        content: [res.msg, '#simple'] //数组第二项即吸附元素选择器或者DOM
                        ,time:2000
                    });
                    return false;
                }
                $('#simple_img').show();
                $('#simple_img').html('');
                if (res.data.src != '') {
                    $('#simple_img').append('<a href="' + res.data.src + '" target="_blank"><img src="' + res.data.src + '"  class="layui-upload-img" width="190px" height="148px"></a>')
                    muls = res.data.src + '|';
                    $('[name="teacher_img"]').val(muls);
                }
            }
        });
        //表单初始赋值
        form.val('update', {
            "id": "<?php echo htmlentities($sel['id']); ?>", // "name": "value"
            "teacher_name": "<?php echo htmlentities($sel['teacher_name']); ?>", // "name": "value"
            "category_id": "<?php echo htmlentities($sel['category_id']); ?>", // "name": "value"
            "teacher_subject": "<?php echo htmlentities($sel['teacher_subject']); ?>", // "name": "value"
            "teacher_img": "<?php echo htmlentities($sel['teacher_img']); ?>" ,// "name": "value"
            "teacher_short": "<?php echo htmlentities($sel['teacher_short']); ?>" ,// "name": "value"
            "zans": "<?php echo htmlentities($sel['zans']); ?>" ,// "name": "value"
            "teacher_age": "<?php echo htmlentities($sel['teacher_age']); ?>" ,// "name": "value"
            "teacher_nation": "<?php echo htmlentities($sel['teacher_nation']); ?>" ,// "name": "value"
            "status": "<?php echo htmlentities($sel['status']); ?>" ,// "name": "value"
            "teacher_introduce": "<?php echo htmlentities($sel['teacher_introduce']); ?>" ,// "name": "value"
        })
    });
</script>
