<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 17:18
 */

namespace app\api\controller\v1;

use app\code\Api;
use app\api\model\v1\Course;
use think\Db;
use think\Exception;
use app\api\model\v1\User as m_User;
use app\api\model\v1\CourseEvaluate;

class Courses extends Base
{
    const DEBUG = true;

    //依据课程分类获取课程列表postCourseList
    public function getCourseList()
    {
        $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
        $course_id = isset($this->_input['course_id']) ? (int)$this->_input['course_id'] : null;
        if (empty($course_id)) return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        $Course = new Course;
        $list = $Course->getCourseByFilter($course_id, $page, $pagesize);
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], empty($list) ? null : $list);
    }

    //课程详情
    public function getCourseDetail()
    {

        $course_id = isset($this->_input['course_id']) ? (int)$this->_input['course_id'] : null;
        if (empty($course_id)) return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        $Course = new Course;
        $res = $Course->getCourseById($course_id);
        if ($res && !empty($res['course_thumb'])) {
            $res['course_thumb'] = unserialize(mb_unserialize($res['course_thumb']));
            $res['course_description'] = htmlspecialchars_decode($res['course_description']);
        }
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], empty($res) ? null : $res);
    }


    //点击支付弹出层信息
    public function getCourseAlertInfo()
    {
        $course_id = isset($this->_input['course_id']) ? (int)$this->_input['course_id'] : null;
        if (empty($course_id)) return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        $Course = new Course;
        $res = $Course->getCourseByIdToAlert($course_id);
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], empty($res) ? null : $res);
    }


    //弹框选中数据之后预下单返回数据
    public function createCourseOrder()
    {
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        $course_id = isset($this->_input['course_id']) ? $this->_input['course_id'] : null;
        $class_id = isset($this->_input['class_id']) ? $this->_input['class_id'] : null;
        $time_id = isset($this->_input['time_id']) ? $this->_input['time_id'] : null;
        $package_id = isset($this->_input['package_id']) ? $this->_input['package_id'] : null;
        if (empty($openid) || empty($course_id) || empty($class_id) || empty($time_id) || empty($package_id)) {
            return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        }
        $Course = new Course;
        if (!$Course->validateCourseOrder($openid, $course_id, $class_id, $time_id, $package_id)) {
            return api_json(Api::PARAM_ERROR[0], '预下单失败,系统错误');
        }
        $res = $Course->createCourseOrder($openid, $course_id, $class_id, $time_id, $package_id);
        return $res;
    }

    /**
     * 查看合同信息
     */
    public function getContractInfo()
    {
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if (empty($openid)) {
            return api_json(Api::OPENID_ERROR[0], Api::OPENID_ERROR[1]);
        }
        $html = <<< EOT
<p>Obligations of Party A 甲方的权利与义务</p>
<p>1.Party A agrees to the above curriculum and tuition fees, and keeps up payment in full a according to payment timetable. Students who cancel their lessons will forfeit their deposit; 甲方认同以上课程组合安排和收费，并按付款时间表付清所有款项，定金一旦支付不予以退还；</p>
<p>2.Party A agrees that if a student is discovered to be unwell, physically or psychologically, or to suffer from any infectious diseases, Party B has the right to deny the student entrance to the classroom. Party A agrees that a student is refused entry
    the classroom if he/she is late for class by more than 15 minutes. 甲方同意当乙方发现学员有身体／精神上的不适应或患有传染病，有权拒绝其进入教室。甲方认同如其迟到超过15分钟将被拒绝进入教室。</p>
<p>3.Should a student have been absent from class for three consecutive weeks, Party A agrees about the automatic cancellation of the student's membership. Party A can re-apply for a class schedule and complete the curriculum within the duration of this
    contract. Party A agrees that a student must adopt the schedule based on Party B;s teaching procedure. 甲方认同如连续3周上课旷课，其学位将被系统自动取消。甲方应向其中心提出重新安排课时要求，并于此协议期内完成所有课程。甲方认知其学员必须依据乙方的规定流程来配合上课。</p>
<p>4.Party A agrees that a bonus lessons (if any) comes into effect after the completion off the regular course. Should Party A cancel the contract early, the bonus lessons (if any)are cancelled automatically.Party A is aware that bonus lessons are not part
    of the original course sold and/or listed on this contract. Party B reserves the right for its own interpretation of the bonus lessons. Party A agrees that bonus lessons (if applicable) must be completed within the duration of this contract and becomes
    invalid if it exceeds the time limit. 甲方认同获赠课程（如有）须在正式课程完成之后开始生效，如甲方提前解约获赠课程将自动取消。甲方认知获赠课程不属于本协议销售的课程，具体由乙方负责解释。甲方同意获赠课程将在本协议有效期内完成，逾时作废。</p>
<p>5.Party A agrees that, should Party A decide to change the schedule due to his/her personal reasons, Party A have to apply a new schedule in two weeks in advance. Party B has the rights to accept or deny the purposed changes to the original time-table.
    If accepted, Party A could face a price increase difference, caused by the changes to the original schedule and Party A will be required to compensate Party B for the difference. 甲方认同，如因甲方个人原因决定改变上课时段，须提前两周与乙方申请；乙方根据实际排课情况酌情决定是否予以同意，且甲方应承担调整时间段后可能产生的差价部分补差。</p>
<p>6.Party A agrees that, should a student require not attending a scheduled class, due to Party A’s personal reasons, Party A must inform the customer service department of Party B by email, twenty four hours in advance. Patty A, also understands that,
    the curriculum continues despite the student’s attendance until the duration date of the contract. Any lessons where a student is absent without asking for leave in advance, will be counted as consumed lessons. 甲方认同，如因甲方原因不得不缺课的，应至少提前1天向客服邮件请假，同时于此协议期内完成所有课程。乙方未收到任何未请假的缺课，将计入甲方已消耗的课次。</p>
<p>Obligations of Party B乙方的权利与义务</p>
<p>Party B is aware of and agrees to the following articles of the contract. During the valid period of the contract. Party B must: 乙方认知及同意以下的条款内容，在此协议的有效期内，乙方必须：</p>
<p>1.Guarantee that all of its employed tutors have been formally interviewed and justly selected for employment and are qualified to teach students between 2-12 years old the subject of English Language. 保证所有指导师皆经过平等筛选及正规面试后才得以聘用，并符合英语语言指导2-12岁学员上课的资格。</p>
<p>2.Provide and design the English curriculum in accordance to the student’s age and ensure that all students are placed in a class with a low number of students. 提供按学员不同年龄设计的语言课程，并保证小班制度。</p>
<p>3.Utilize safe teaching materials and devices. 使用注重学员安全的教材与设备。 his contract becomes invalid automatically on the contract’s termination date.本协议到期日截止时，本协议将自动终止。</p>
<p>Transfer of ownership of the contract本协议转让：</p>
<p>If Party A is not able to complete his/her curriculum due to his/her personal reasons,under the following circumstances, Party A can transfer the contract to a third Party: 如因甲方原因无法完成全部课程，满足以下条件可以转让：</p>
<p>1.Party A can apply to Party B for a transfer, Party A by submit a written application and the identification of the third Party. With Party B’s permission and requirements, patty A and the third Party, must meet at the designated location , in which
    the student attended and sign a transfer contract between third Party and the school. The un-consumed lessons are to be transferred to the third Party from the date of signing the transfer contract. 甲方向乙方申请转让时，应提交书面的转让申请和第三方的相关身份证明文件。经乙方同意后，甲方应当根据乙方的要求和该第三方一起至校方指定的地点与校方签署转让协议，甲方未完成的课时自第三方签署转让协议之日起被转让给第三方。</p>
<p>2.Party A has no former tarsier records. 甲方没有任何历史转让记录；</p>
<p>3.The third Party is not a potential student of Party B.(The classifications of potential students, is not limited to those whose information has been obtained and/or saved to Party B’s database via various channels) 受让方不是乙方潜在学员（潜在学员包括不仅限于通过各种渠道在乙方处留有具体资料的人员）</p>
<p>4.Party A understands that they have given up the bonus lessons and not transferred to the third Party. 甲方放弃赠送课程</p>
<p>5.Party A agrees to pay for 1000RMB as a transfer fee. 甲方同意支付转让费1000元。</p>
<p>Withdrawal from the curriculum and refund退学及退费</p>
<p>If Party B , due to his/her personal reasons, withdraws from the program and asks for a refund, his/her bonus lessons(if applicable)are to be cancelled and the refund application is to be processed as follows: 甲方因个人原因提出退费，赠送即取消。</p>
<p>1.Once the contract becomes valid, even if the lessons have not started, 90% of paid fee will be refunded. 合同一旦生效，课程未启动可退还已付费用的90%。</p>
<p>2.Should Party A withdraw, from the contract and the consumed lessons ate less than one third of its overall arm or the contract has been valid for a time less than one third of its term(whichever comes first) half of the paid fee will be refunded. 上课次数未过全期三分之一（课时数或有效期，以先到者为准）以内课程者，可退还已付费用的一半。</p>
<p>3.Should Party A withdraw, from the contract, beyond one third of its full term, we regret to inform you, the fee is not refundable. 上课次数已过（包括）全期三分之一以上课程者，恕不退还任何费用。</p>
<p>Should there be any disputes regarding the contract occur between Party A and B,both Parties shall negotiate with each other in a friendly and respectful manner. If a mutual agreement cannot be reached, then the case shall be submitted to Party B’s local
    People’s Court for a court decision. 甲乙双方对本协议若发生争议应友好协商，若无法达成一致意见则提交乙方所在地的人民法院管辖。</p>
<p>If there’s any alteration/modification or cancellation as to the content of the contract, a supplementary contract shall be signed between the two Parties. A supplementary contract becomes valid only when it’s received the seal of Party B. 本协议内部分或全部内容的修改或取消，双方另外签订补充协议。补充协议需有乙方盖章方可生效。</p>
<p>衷心祝您的孩子在童趣英语之家度过快乐而有意义的时光！</p>
<p style="text-align: right">Childtime保留对该协议条款的最终解释权</p>
EOT;
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], $html);
    }


    /**
     * 课程订单付款
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function payCourseOrder()
    {
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if (empty($openid)) {
            return api_json(Api::OPENID_ERROR[0], Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if (empty($uid)) {
            return api_json(Api::SYSTEM_ERROR[0], '用户信息查询有误');
        }
        $ordersn = isset($this->_input['ordersn']) ? $this->_input['ordersn'] : null;
        if (empty($ordersn)) {
            return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        }
        $child_name = isset($this->_input['child_name']) ? $this->_input['child_name'] : null;
        $gender = isset($this->_input['gender']) ? $this->_input['gender'] : null;
        if(empty($child_name) || empty($gender)){
            return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        }
        $o_where = ['ordersn' => $ordersn];
        if (!($order = Db::name('course_order')->where($o_where)->find())) {
            return api_json(Api::SYSTEM_ERROR[0], '订单信息查询有误');
        }
        //修改订单信息
        $json = [
            'child_name'=>$child_name,
            'gender'=>$gender
        ];
        $contract_json = json_encode($json,JSON_UNESCAPED_UNICODE);
        Db::name('course_order')->where($o_where)->update(compact('contract_json'));

        //TODO 微信预下单 返回小程序唤醒微信支付的相关数据
        $Weixin = new Weixin;
        $body = '课程订单';
        if (self::DEBUG) {
            $total_fee = 0.01;
        } else {
            $total_fee = $order['price'];
        }
        $notify_url = "https://education.kedaweilai.com/api/notify/notifyCourseCallback";
        $preorder = $Weixin->getAppletPayParams($ordersn, $body, $total_fee, $notify_url, $openid);
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], $preorder);
    }


    /**
     * 课程评价
     * @return string
     */
    public function giveCourseEvaluate()
    {
        $id = isset($this->_input['id']) ? (int)$this->_input['id'] : null;
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        $content = isset($this->_input['content']) ? $this->_input['content'] : null;
        if (empty($id)) {
            return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if (empty($uid)) {
            return api_json(Api::SYSTEM_ERROR[0], '用户信息查询有误');
        }
        if (empty($content)) {
            return api_json(Api::PARAM_ERROR[0], '请输入评价内容');
        }
        if (mb_strlen($content) > 500) {
            return api_json(Api::PARAM_ERROR[0], '评价内容超限');
        }
        $where = ['id' => $id, 'status' => 0, 'deleted' => 0];
        $info = Db::name('teacher')->where($where)->count(1);
        if (empty($info)) {
            return api_json(Api::SYSTEM_ERROR[0], '教师信息查询有误');
        }

        $e_where = ['UserCourse.uid' => $uid];
        $evaluate = Db::view('UserCourse', 'id')
            ->view('Course', 'course_id', 'UserCourse.course_id=Course.id')
            ->where($e_where)
            ->find();
        if ($evaluate['course_id'] != $id) {
            return api_json(Api::PARAM_ERROR[0], '只有购买该课程才能评价');
        }

        //todo 该课程增加一条评价记录  现在默认审核通过
        $CourseEvaluate = new CourseEvaluate;
        $cid = $id;
        $status = 1;
        $map = compact('cid', 'uid', 'content', 'status');
        $res = $CourseEvaluate->_insert($map);
        return $res;
    }


    /**
     * 获取指定课程全部评价带分页
     * @return string
     */
    public function getAllCourseEvaluate()
    {
        $id = isset($this->_input['id']) ? (int)$this->_input['id'] : null;
        if (empty($id)) {
            return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
        }
        $where = ['id' => $id, 'status' => 1, 'deleted' => 0];
        $info = Db::name('course')->where($where)->count(1);
        if (empty($info)) {
            return api_json(Api::SYSTEM_ERROR[0], '课程信息查询有误');
        }

        $CourseEvaluate = new CourseEvaluate;
        $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
        $list = $CourseEvaluate->getList($id, $page, $pagesize);
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], empty($list) ? null : $list);
    }

}