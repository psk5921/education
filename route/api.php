<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@   用户板块  START  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
/**
 * 用户登录 POST
 */
Route::post('api/users/bindUserInfo', '@api/v1.users/bindUserInfo')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 获取用户openid POST
 */
Route::post('api/users/getOpenid', '@api/v1.users/getOpenid')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 * 绑定手机号 POST
 */
Route::post('api/users/bindMobile', '@api/v1.users/bindMobile')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 * 用户是否登录 POST
 */
Route::post('api/users/isLogin', '@api/v1.users/isLogin')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);



/**
 * 用户收货地址添加/修改 POST
 */
Route::post('api/users/address', '@api/v1.users/address')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 用户收货地址设置默认 POST
 */
Route::post('api/users/setDefault', '@api/v1.users/setDefault')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 用户收货地址批量删除 POST
 */
Route::post('api/users/removeAddress', '@api/v1.users/removeAddress')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 用户收货地址查看 post
 */
Route::post('api/users/viewAddress', '@api/v1.users/viewAddress')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 用户收货地址查看 post
 */
Route::post('api/users/addressList', '@api/v1.users/addressList')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 * 查询用户信息 POST
 */
Route::post('api/users/getUserInfo', '@api/v1.users/getUserInfo')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 获取用户的订单课程 POST
 */
Route::post('api/users/getMyCourseOrder', '@api/v1.users/getMyCourseOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 核销订单 POST
 */
Route::post('api/users/scanOrder', '@api/v1.users/scanOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 核销订单 POST
 */
Route::post('api/users/getMyEwmCourseOrder', '@api/v1.users/getMyEwmCourseOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 *  生成二维码 POST
 */
Route::post('api/users/qrcode', '@api/v1.users/qrcode')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  用户家庭地址 POST
 */
Route::post('api/users/homeAddress', '@api/v1.users/homeAddress')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  用户意见反馈 POST
 */
Route::post('api/users/feedback', '@api/v1.users/feedback')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);



/**
 *  获取我的消息列表 POST
 */
Route::post('api/users/getMyMessageList', '@api/v1.users/getMyMessageList')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  读取指定消息详情 POST
 */
Route::post('api/users/getMyMessageInfo', '@api/v1.users/getMyMessageInfo')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@   用户板块   END  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@



//*********************************************** 课程板块  START ***********************************************************


/**
 * 获取课程分类  post
 */
Route::post('api/course_categorys/getCourseCategory', '@api/v1.course_categorys/getCourseCategory')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/** 
 * 根据课程分类获取课程  post
 */
Route::post('api/courses/getCourseList', '@api/v1.courses/getCourseList')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 * 获取指定课程信息  post
 */
Route::post('api/courses/getCourseDetail', '@api/v1.courses/getCourseDetail')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 * 获取指定课程信息弹出层 post
 */
Route::post('api/courses/getCourseAlertInfo', '@api/v1.courses/getCourseAlertInfo')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);

/**
 * 创建预定义课程订单 POST
 */
Route::post('api/courses/createCourseOrder', '@api/v1.courses/createCourseOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 获取指定课程信息的评价  post
 */
Route::post('api/courses/getAllCourseEvaluate', '@api/v1.courses/getAllCourseEvaluate')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 * 创建预定义课程订单异步回调 POST
 */
Route::post('api/notify/notifyCourseCallback', '@api/v1.notify/notifyCourseCallback')->https(true);


/**
 *  给课程评价  POST
 */
Route::post('api/courses/giveCourseEvaluate', '@api/v1.courses/giveCourseEvaluate')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  确认购买课程  POST
 */
Route::post('api/courses/payCourseOrder', '@api/v1.courses/payCourseOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 * 查看合同信息 POST
 */
Route::post('api/courses/getContractInfo', '@api/v1.courses/getContractInfo')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


//*********************************************** 课程板块  END  ***********************************************************



















//###########################################  教师板块  START  ##############################################################

/**
 *  join us 信息提交 POST
 */
Route::post('api/teachers/join_us', '@api/v1.teachers/join_us')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  join us 图片提交 POST
 */
Route::post('api/teachers/imageUpload', '@api/v1.teachers/imageUpload')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 *  获取教师列表 post
 */
Route::post('api/teachers/teachers_list', '@api/v1.teachers/teachers_list')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 *  获取教师详情 post
 */
Route::post('api/teachers/teachers_detail', '@api/v1.teachers/teachers_detail')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 *  获取指定教师全部评价带分页 post
 */
Route::post('api/teachers/getAllTeacherEvaluate', '@api/v1.teachers/getAllTeacherEvaluate')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);


/**
 *  给老师点赞  POST
 */
Route::post('api/teachers/giveTeacherLike', '@api/v1.teachers/giveTeacherLike')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  给老师评价  POST
 */
Route::post('api/teachers/giveTeacherEvaluate', '@api/v1.teachers/giveTeacherEvaluate')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  给老师评价  POST
 */
Route::post('api/teachers/giveTeacherEvaluate', '@api/v1.teachers/giveTeacherEvaluate')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 *  上门预约 预约上门（包含在教师详情页面预约以及个人中心预约）  POST
 */
Route::post('api/teachers/getHomeBook', '@api/v1.teachers/getHomeBook')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);

/**
 *  上门预约 预约上门（包含在教师详情页面预约以及个人中心预约）  POST
 */
Route::post('api/teachers/homeBook', '@api/v1.teachers/homeBook')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);
//###########################################  教师板块  END  ##############################################################







//################################## 购物车板块  START #################################################################


/**
 *  加入购物车  POST
 */
Route::post('api/carts/addCart', '@api/v1.carts/addCart')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  购物车列表 POST
 */
Route::post('api/carts/cartList', '@api/v1.carts/cartList')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);



/**
 *  购物车选中/不选中 POST
 */
Route::post('api/carts/setOptionsSelected', '@api/v1.carts/setOptionsSelected')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 *  购物车数量增加/减少 POST
 */
Route::post('api/carts/setOptionsNum', '@api/v1.carts/setOptionsNum')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  购物车批量删除 POST
 */
Route::post('api/carts/removeCart', '@api/v1.carts/removeCart')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);



//################################## 购物车板块  END ###################################################################







//################################## 商城板块  START ###################################################################
/**
 *  商城首页  post
 */
Route::post('api/shops/shopIndex', '@api/v1.shops/shopIndex')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);

/**
 *  商品详情 post
 */
Route::post('api/shops/shopDetail', '@api/v1.shops/shopDetail')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);

/**
 *  商品详情点击购买 POST
 */
Route::post('api/shops/getCreatePreOrderForOnce', '@api/v1.shops/getCreatePreOrderForOnce')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 *  购物车点击购买 POST
 */
Route::post('api/shops/getCreatePreOrderForCart', '@api/v1.shops/getCreatePreOrderForCart')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  通过订单号获取微信支付参数 POST
 */
Route::post('api/shops/payShopOrder', '@api/v1.shops/payShopOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  商品详情确认购买 POST
 */
Route::post('api/shops/postCreatePreOrderForOnce', '@api/v1.shops/postCreatePreOrderForOnce')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 *  购物车点击购买进来以后确认购买 POST
 */
Route::post('api/shops/postCreatePreOrderForCart', '@api/v1.shops/postCreatePreOrderForCart')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);


/**
 *  购物车点击购买进来以后确认购买 POST
 */
Route::post('api/notify/notifyOrderCallback', '@api/v1.notify/notifyOrderCallback')->https(true);
//################################## 商城板块  END ###################################################################


//################################## 订单板块  START ###################################################################
/**
 * 我的订单 POST
 */
Route::post('api/orders/myOrder', '@api/v1.orders/myOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 * 取消订单 POST
 */
Route::post('api/orders/cancelOrder', '@api/v1.orders/cancelOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 * 催单 POST
 */
Route::post('api/orders/cuidan', '@api/v1.orders/cuidan')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 * 确认收货 POST
 */
Route::post('api/orders/confirmOrder', '@api/v1.orders/confirmOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 * 删除订单 POST
 */
Route::post('api/orders/delOrder', '@api/v1.orders/delOrder')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 * 订单详情 POST
 */
Route::post('api/orders/orderDetail', '@api/v1.orders/orderDetail')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);

/**
 * 订单支付成功 POST
 */
Route::post('api/orders/orderPaySuccessed', '@api/v1.orders/orderPaySuccessed')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->middleware('user')->https(true);
//################################## 订单板块  END ###################################################################


/**
 * 获取地区数据 POST
 */
Route::post('api/areas/getArea', '@api/v1.areas/getArea')->ext()->header([
    'Access-Control-Allow-Origin'=>'*',
    'Access-Control-Allow-Methods' => 'POST',
])->https(true);





return [

];
