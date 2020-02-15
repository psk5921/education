<?php /*a:2:{s:88:"/www/wwwroot/education.kedaweilai.com/application/admin/view/category/course_update.html";i:1575449767;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>课程编辑</title>
    <meta name="keywords" content="课程编辑" />
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
<!--<script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.config.js"></script>
<script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.all.min.js"></script>
<script src="https://education.kedaweilai.com/static/plus/ueditor/lang/zh-cn/zh-cn.js"></script>-->
<style>
    .form {
        padding: 10px 10px 0 10px;
        background: #fff;
    }

    .ft30 {
        font-size: 30px !important;
    }
</style>
<body>
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
        <label class="layui-form-label">排序 </label>
        <div class="layui-input-inline">
            <input type="text" name="displayorder" value="0" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            课程分类 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="course_id" name="course_id" class="valid">
                <?php if(is_array($types) || $types instanceof \think\Collection || $types instanceof \think\Paginator): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['edu_category_name']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">课程名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="course_title" lay-verify="required" placeholder="请输入课程名称" autocomplete="off"
                   class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">关键词描述 <span class="x-red">*</span></label>
        <div class="layui-input-inline" style="width: 600px;">
            <input type="text" name="course_short" lay-verify="required" placeholder="请输入关键词描述" autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <!---->
    <div class="layui-form-item">
        <label class="layui-form-label">课程封面 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <div class="layui-upload-drag" id="simple">
                <i class="layui-icon ft30"></i>
                <p>点击上传，或将文件拖拽到此处</p>
            </div>
            <div class="layui-form-mid layui-word-aux" style="color: red!important">温馨提示：仅支持jpg|png|jpeg格式的图片且文件大小小于2M
            </div>

            <?php if($sel['course_img']): ?>
            <div style="margin-top:15px" id="simple_img">
                <a href="<?php echo htmlentities($sel['course_img']); ?>" target="_blank"><img src="<?php echo htmlentities($sel['course_img']); ?>" class="layui-upload-img"
                                                                 width="190px" height="148px"></a>
            </div>
            <?php else: ?>
            <div style="margin-top:15px;display: none" id="simple_img">
            </div>
            <?php endif; ?>

            <input type="hidden" name="course_img">
        </div>
    </div>
    <!---->
    <div class="layui-form-item">
        <label class="layui-form-label">课程多图 <span class="x-red">*</span></label>
        <div class="layui-input-inline" style="width: 70%">
            <div class="layui-btn" id="multiple">多图片上传</div>

            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                预览图：
                <div class="layui-upload-list" id="preview">
                    <?php if($sel['course_thumb']): foreach($sel['course_thumb'] as $item): ?>
                    <a href="<?php echo htmlentities($item); ?>" target="_blank"><img src="<?php echo htmlentities($item); ?>" class="layui-upload-img" width="190px"
                                                           height="148px"></a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </blockquote>
            <div class="layui-form-mid layui-word-aux" style="color: red!important">温馨提示：仅支持jpg|png|jpeg格式的图片且文件大小小于2M
            </div>
            <input type="hidden" name="course_thumb">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            <span class="x-red">*</span>教师名称</label>
        <div class="layui-input-inline">
            <select id="teacher_id" name="teacher_id" class="valid">
                <?php if(is_array($teacher) || $teacher instanceof \think\Collection || $teacher instanceof \think\Paginator): $i = 0; $__LIST__ = $teacher;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['teacher_name']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">评价次数 </label>
        <div class="layui-input-inline">
            <input type="text" name="evaluate_total" placeholder="请输入评价次数" autocomplete="off"
                   class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">课程简介 <span class="x-red">*</span></label>
        <div class="layui-input-block">
            <textarea name="course_description" id="course_description" lay-verify="course_description"
                      style="display: none;"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 113px;">班级信息简介 <span class="x-red">*</span></label>
        <div class="layui-input-inline" style="width: 600px;">
            <input type="text" name="course_class" lay-verify="required" placeholder="请输入班级信息简介" autocomplete="off"
                   class="layui-input">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">已报名人数 </label>
        <div class="layui-input-inline">
            <input type="text" name="course_total" placeholder="请输入报名人数" autocomplete="off"
                   class="layui-input" value="0">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">销售协议 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <div class="layui-upload-drag" id="xy">
                <i class="layui-icon ft30"></i>
                <p>点击上传，或将文件拖拽到此处</p>
            </div>
            <div class="layui-form-mid layui-word-aux" style="color: red!important">温馨提示：仅支持jpg|png|jpeg格式的文件且文件大小小于2M</div>
            <?php if($sel['agreement']): ?>
            <div style="margin-top:15px;" id="simple_xy">
                <p style="color: green">文件已上传</p>
            </div>
            <?php else: ?>
            <div style="margin-top:15px;display: none" id="simple_xy">
            </div>
            <?php endif; ?>

            <input type="hidden" name="agreement">
        </div>
    </div>

    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">状态 <span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="等待上架" checked>
            <input type="radio" name="status" value="1" title="上架">
            <input type="radio" name="status" value="2" title="下架">
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
            first_nav: "课程列表",
            second_nav: "课程编辑",
        },
        methods: {},
        //监听属性
        watch: {},
        //计算属性
        computed: {}

    });
    layui.use(['form', 'layedit', 'upload', 'jquery'], function () {
        var form = layui.form
            , layer = layui.layer
            , $ = layui.jquery
            , layedit = layui.layedit
            , upload = layui.upload;
        layedit.set({
            uploadImage: {
                url: "<?php echo url('uploadCourse'); ?>" //接口url
                , type: 'post' //默认post
            }
        });
        form.render(null, 'submits');
        var index = layedit.build('course_description'); //建立编辑器
        //核心代码就是这一条   进行赋值	请求语句我就省略
        layedit.setContent(index, '<?php echo $sel['course_description']; ?>');
        //自定义验证规则
        form.verify({
            course_description: function (value) {
                layedit.sync(index);
            }
        });
        //监听提交
        form.on('submit(submits)', function (data) {
            $.ajax({
                type: "post",
                url: "<?php echo url('course_update'); ?>",
                dataType: "json",
                data: data.field,
                success: function (data, textStatus, request) {
                    if (data.code == 20006) {
                        //用户信息失效
                        layer.msg(data.msg, {anim: 0, shade: 0.6}, function () {
                            parent.location.reload();
                            xadmin.del_tab();
                            xadmin.close();
                        });
                    } else if (data.code == 1) {
                        layer.msg(data.msg, {anim: 0, shade: 0.6}, function () {
                            parent.location.reload();
                            xadmin.close();
                        });
                    } else {
                        layer.msg(data.msg, {anim: 0, shade: 0.6}, function () {
                            return false;
                        });
                    }
                }
            });
            /*layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            })*/
            return false;
        });

        //多图片上传
        var mul = '';
        upload.render({
            elem: '#multiple'
            , url: "<?php echo url('multiple'); ?>"
            , multiple: true
            , field: 'file[]'
            , accept: 'images'
            , exts: 'jpg|png|jpeg'
            , size: 2097152
            , before: function (obj) {
                //预读本地文件示例，不支持ie8
                //$('#preview').html('')
            }
            , done: function (res) {
                //上传完毕
                if (res.code != 0) {
                    layer.open({
                        type: 4,
                        content: [res.msg, '#multiple'] //数组第二项即吸附元素选择器或者DOM
                        , time: 2000
                    });
                    return false;
                }
                if (res.data.src != '') {

                    $.each(res.data.src, function (i, n) {
                        $('#preview').append('<a href="' + n + '" target="_blank"><img src="' + n + '"  class="layui-upload-img" width="150px" height="150px"></a>')
                        mul += n + '|';
                    })
                    $('[name="course_thumb"]').val(mul);
                }

            }
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
                if (res.code != 0) {
                    layer.open({
                        type: 4,
                        content: [res.msg, '#simple'] //数组第二项即吸附元素选择器或者DOM
                        , time: 2000
                    });
                    return false;
                }
                $('#simple_img').show();
                $('#simple_img').html('');
                if (res.data.src != '') {
                    $('#simple_img').append('<a href="' + res.data.src + '" target="_blank"><img src="' + res.data.src + '"  class="layui-upload-img" width="190px" height="148px"></a>')
                    muls = res.data.src + '|';
                    $('[name="course_img"]').val(muls);
                }
            }
        });


        var xys = '';
        upload.render({
            elem: '#xy'
            , url: "<?php echo url('xy'); ?>"
            , accept: 'file'
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
                if (res.code != 0) {
                    layer.open({
                        type: 4,
                        content: [res.msg, '#xy'] //数组第二项即吸附元素选择器或者DOM
                        , time: 2000
                    });
                    return false;
                }
                $('#simple_xy').show();
                $('#simple_xy').html('');
                if (res.data.src != '') {
                    $('#simple_xy').html('<p style="color: green">文件已上传</p>');
                    muls = res.data.src + '|';
                    $('[name="agreement"]').val(muls);
                }
            }
        });

        //表单初始赋值
        form.val('update', {
            "id": "<?php echo htmlentities($sel['id']); ?>", // "name": "value"
            "displayorder": "<?php echo htmlentities($sel['displayorder']); ?>", // "name": "value"
            "course_id": "<?php echo htmlentities($sel['course_id']); ?>", // "name": "value"
            "course_title": "<?php echo htmlentities($sel['course_title']); ?>", // "name": "value"
            "course_short": "<?php echo htmlentities($sel['course_short']); ?>", // "name": "value"
            "teacher_id": "<?php echo htmlentities($sel['teacher_id']); ?>",// "name": "value"
            "evaluate_total": "<?php echo htmlentities($sel['evaluate_total']); ?>",// "name": "value"
            "course_description": "<?php echo htmlentities($sel['course_description']); ?>",// "name": "value"
            "course_class": "<?php echo htmlentities($sel['course_class']); ?>",// "name": "value"
            "course_total": "<?php echo htmlentities($sel['course_total']); ?>",// "name": "value"
            "status": "<?php echo htmlentities($sel['status']); ?>",// "name": "value"
            "agreement": "<?php echo htmlentities($sel['agreement']); ?>",// "name": "value"
            "course_img": "<?php echo htmlentities($sel['course_img']); ?>",// "name": "value"
            "course_thumb": "<?php echo htmlentities($sel['course_thumbs']); ?>",// "name": "value"
        })
    });
</script>


