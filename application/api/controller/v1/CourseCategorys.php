<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 16:23
 */

namespace app\api\controller\v1;

use app\api\model\v1\CourseCategory;
use app\code\Api;
class CourseCategorys extends Base
{
    public function getCourseCategory(){
        $CourseCategory = new CourseCategory;
        $list = $CourseCategory->getCourseCategory();
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],count($list)>0?$list:null);
    }
}