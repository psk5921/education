<?php /*a:2:{s:74:"/www/wwwroot/education.kedaweilai.com/application/admin/view/good/add.html";i:1575449982;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>商品添加</title>
    <meta name="keywords" content="商品添加" />
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
    .layui-upload-drag .layui-icon {
        font-size: 50px;
        color: #009688;
    }
</style>
<div class="x-nav" id="app">
          <span class="layui-breadcrumb">
            <a href="JavaScript:void(0)">{{first_nav}}</a>
            <a href="JavaScript:void(0)">{{second_nav}}</a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right;" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right;margin-right: 15px;" onclick="location.href='<?php echo url('ls'); ?>'" title="返回商品列表">
        <i class="layui-icon layui-icon-return" style="line-height:30px"></i></a>

</div>
<form class="layui-form layui-form-pane form" action="" lay-filter="add">
    <div class="layui-form-item">
        <label class="layui-form-label">排序 </label>
        <div class="layui-input-inline">
            <input type="text" name="displayorder"  value="0" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">商品标题 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="title" lay-verify="required" placeholder="请输入商品标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label for="cid" class="layui-form-label">
           商品分类 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="cid" name="cid" class="valid">
              <?php if(is_array($types) || $types instanceof \think\Collection || $types instanceof \think\Paginator): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['shop_category_name']); ?></option>
             <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商品封面 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <div class="layui-upload-drag" id="simple">
                <i class="layui-icon"></i>
                <p>点击上传，或将文件拖拽到此处</p>
            </div>
            <div  style="margin-top:15px;display: none" id="simple_img" >
            </div>
            <input type="hidden" name="img">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商品多图 <span class="x-red">*</span></label>
        <div class="layui-input-inline" style="width: 70%">
            <div  class="layui-btn" id="multiple">多图片上传</div>
            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                预览图：
                <div class="layui-upload-list" id="preview"></div>
            </blockquote>
            <input type="hidden" name="thumb">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">商品简介 </label>
        <div class="layui-input-inline" style="width: 350px">
            <input type="text" name="short_title"  placeholder="请输入商品简介" autocomplete="off" class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商品价格 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="price" lay-verify="required" placeholder="请输入商品价格" autocomplete="off" class="layui-input">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">商品内容 </label>
        <div class="layui-input-block">
            <textarea id="content" style="display: none;" placeholder="请输入商品内容" name="content" lay-verify="content"></textarea>
        </div>
    </div>

    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">商品状态<span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="等待上架" checked="checked">
            <input type="radio" name="status" value="1" title="上架">
            <input type="radio" name="status" value="2" title="下架">
        </div>
    </div>






    <div class="layui-form-item" style="text-align: center">
        <div class="layui-btn "  style="width: 100px;height: 40px;line-height: 40px" onclick="location.href='<?php echo url('ls'); ?>'">返回商品列表</div>
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px" id="submit">确认</div>
    </div>
</form>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "商品列表",
            second_nav: "添加商品",
        },
        methods: {},
        //监听属性
        watch: {

        },
        //计算属性
        computed: {

        }

    });
    layui.use(['form','layedit','upload', 'jquery'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,$ = layui.jquery
            ,layedit  = layui.layedit
            ,upload = layui.upload;
        layedit.set({
            uploadImage: {
                url:  "<?php echo url('uploadGood'); ?>" //接口url
                ,type: 'post' //默认post
            }
        });
        var index =  layedit.build('content'); //建立编辑器

        //自定义验证规则
        form.verify({
            content: function(value){
                layedit.sync(index);
            }
        });
        form.render(null, 'submits');
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


        //多图片上传
        var mul = '';
        upload.render({
            elem: '#multiple'
            ,url: "<?php echo url('multiple'); ?>"
            ,multiple: true
            ,field: 'file[]'
            ,accept: 'images'
            ,exts: 'jpg|png|jpeg'
            ,size:2097152
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                //$('#preview').html('')
            }
            ,done: function(res){
                //上传完毕
                if(res.data.src !=''){

                    $.each(res.data.src,function (i,n) {
                        $('#preview').append('<a href="'+ n +'" target="_blank"><img src="'+ n +'"  class="layui-upload-img" width="150px" height="150px"></a>')
                        mul  += n + '|';
                    })
                    $('[name="thumb"]').val(mul);
                }

            }
        });
        //拖拽上传
        var muls = '';
        upload.render({
            elem: '#simple'
            ,url: "<?php echo url('simple'); ?>"
            ,accept: 'images'
            ,exts: 'jpg|png|jpeg'
            ,size:2097152
            /*,before: function(obj){
                //预读本地文件示例，不支持ie8
                $('#simple_img').show();
                $('#simple_img').html('');
                obj.preview(function(index, file, result){
                    $('#simple_img').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img" width="190px" height="148px">')
                });
            }*/
            ,done: function(res){
                $('#simple_img').show();
                $('#simple_img').html('');
                if(res.data.src !=''){
                    $('#simple_img').append('<a href="'+ res.data.src +'" target="_blank"><img src="'+ res.data.src +'"  class="layui-upload-img" width="190px" height="148px"></a>')
                    muls  = res.data.src + '|';
                    $('[name="img"]').val(muls);
                }
            }
        });

    });
</script>
