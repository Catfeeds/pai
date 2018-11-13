<?php
/**
 * 拍一拍订单Service
 *-------------------------------------------------------------------------------------------
 * 版权所有 杭州市拍吖吖科技有限公司
 * 创建日期 2018-07-04
 * 版本 v.1.0.0
 * 网站：www.pai.com
 *--------------------------------------------------------------------------------------------
 */

namespace app\data\service\orderPai;

use app\data\model\orderPai;
use app\data\model\orderPai\OrderPaiModel as OrderPaiModel;
use app\data\service\goodsProduct\GoodsProductService as GoodsProductService;
use app\data\service\goodsDiscounttype\GoodsDiscounttypeService as GoodsDiscounttypeService;
use app\data\service\income\IncomeService;
use app\data\service\member\MemberService as MemberService;
use app\data\service\orderAwardcode\OrderAwardcodeService as OrderAwardcodeService;
use app\data\service\config\ConfigService as ConfigService;
use app\data\service\promoters\PromotersFrozenService;
use app\data\service\system_msg\SystemMsgService as SystemMsgService;
use app\data\service\goods\GoodsService;
use app\data\service\BaseService as BaseService;
use think\Cookie;
use think\Db;


class OrderPaiService extends BaseService
{
    protected $cache = 'order_pai';

    public function __construct()
    {
        parent::__construct();
        $this->orderPai = new OrderPaiModel();
        $this->cache = 'order_pai';
    }

    /**
     * 查询拍一拍订单列表
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiList($where = '', $field = '*', $order = 'o_id asc')
    {
        $list = $this->orderPai->getList($where, $order, $field, $this->cache);
        return $list;
    }

    /**
     * 获取拍一拍订单信息
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiInfo($where = '', $field = '*')
    {
        $info = $this->orderPai->getInfo($where, $field, $this->cache);
        return $info;
    }

    /**
     * 获取拍一拍订单详细信息 order_pai,member,storer,goods_product,goods
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function getMoreInfo($where = '', $field = '*')
    {
        $info = $this->orderPai->getMoreInfo($where, $field, $this->cache);
        return $info;
    }

    /**
     * 条件统计拍一拍订单数量
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiCount($where = '')
    {
        $Count = $this->orderPai->getCount($where);
        return $Count;
    }

    /**
     * 更新某个字段的值
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiSetField($where = '', $field = '', $data = '')
    {
        $SetField = $this->orderPai->getSetField($where, $field, $data, $this->cache);
        return $SetField;
    }

    /**
     * 自增数据
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiSetInc($where = '', $field = '', $data = '')
    {
        $SetInc = $this->orderPai->getSetInc($where, $field, $data, $this->cache);
        return $SetInc;
    }

    /**
     * 查询某一列的值
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiColumn($where = '', $field = '')
    {
        $Column = $this->orderPai->getColumn($where, $field);
        return $Column;
    }

    /**
     * 添加一条拍一拍订单数据
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiAdd($data = '')
    {
        $list = $this->orderPai->getAdd($data, $this->cache);
        return $list;
    }

    /**
     * 添加一条拍一拍订单数据(返回自增id)
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiAdd_getId($data = '')
    {
        $id = $this->orderPai->insertId($data, $this->cache);
        return $id;
    }


    /**
     * 添加多条拍一拍订单数据
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiAddAll($data = '')
    {
        $list = $this->orderPai->getAddAll($data, $this->cache);
        return $list;
    }

    /**
     * 拍一拍订单分页查询(旧)
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiPageList($where = '', $field = '*', $order = "o_id asc", $page = 15)
    {
        $list = $this->orderPai->getPageList($where, $field, $order, $page, $this->cache);
        return $list;
    }

    /**
     * 拍一拍订单分页查询(新)
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiPaginate($where = '', $field = '*', $order = "o_id asc", $page = 15)
    {
        $list = $this->orderPai->getPaginate($where, $field, $order, $page, $this->cache);
        return $list;
    }

    /**
     * 拍一拍订单分页查询
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function getOrderInfoPaginate($where = '', $field = '*', $order = "o_id asc", $page = 15)
    {
        $list = $this->orderPai->getInfoPaginate($where, $field, $order, $page, $this->cache);
        return $list;
    }
    /**
     * 拍一拍订单查询
     * 创建人 邓赛赛
     * 时间 2018-07-04 10:51:00
     */
    public function getOrderInfoSelect($where = '', $field = '*', $order = "o_id asc")
    {
        $list = $this->orderPai->getInfoSelect($where, $field, $order, $this->cache);
        return $list;
    }

    /**
     * 更新拍一拍订单数据
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiSave($where = "", $data = "")
    {
        $save = $this->orderPai->getSave($where, $data, $this->cache);
        return $save;
    }

    /**
     * 删除一条拍一拍订单数据
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiDel($where = '')
    {
        $del = $this->orderPai->getDel($where, $this->cache);
        return $del;
    }

    /**
     * 删除一条拍一拍订单数据（非物理刪除,事务）
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiDelete($o_id)
    {
        $del = $this->orderPai->getDelete($o_id);
        return $del;
    }

    /**
     * 删除多条拍一拍订单数据
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiDelMost($id_arr = '')
    {
        $delAll = $this->orderPai->getDelMost($id_arr, $this->cache);
        return $delAll;
    }

    /**
     * 拍一拍订单列表分页
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiListShow($type = 0)
    {
        $where = array();

        $where['o_id'] = array('>', 0);
        $lists = $this->orderPaiPageList($where);
        $volist = false;
        if ($lists && !$type) {
            $volist = $lists->toArray();
        } else if ($lists && $type) {
            $volist = $lists;
        }
        return $volist;
    }

    /**
     * 按条件更新拍一拍订单
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiRoomEdit()
    {
        $id = input('get.o_id');
        if ($id == '' || !is_numeric($id)) {
            return false;
        }
        $id = intval($id);
        $where = array();
        $where['o_id'] = $id;
        $result = false;
        $result = $this->orderPaiInfo($where);
        return $result;
    }

    /**
     * 按条件修改处理拍一拍订单
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiRoomEditDoo()
    {
        $id = input('post.o_id');
        if ($id == '' || !is_numeric($id)) {
            return false;
        }
        $id = intval($id);
        // 拍一拍订单POST数据
        $type = 'edit';
        $data = $this->inputData($type);
        $where = array();
        $where['o_id'] = $id;
        if ($this->orderPaiSave($where, $data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除拍一拍订单操作
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiRoomDel()
    {
        $id = input('post.o_id');
        if ($id == '' || !is_numeric($id)) {
            return false;
        }
        $id = intval($id);
        $where = array();
        $where['o_id'] = $id;
        $info = $this->brandInfo($where);
        if ($info && $this->brandDel($where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 批量删除拍一拍订单
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiRoomDelMost()
    {
        $id = input('post.delid');
        if ($this->brandDelMost($id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 添加一条拍一拍订单
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function orderPaiRoomAdd()
    {
        // 拍一拍订单POST数据
        $type = 'add';
        $data = $this->inputData($type);
        if ($this->brandAdd($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 拍一拍订单POST数据
     * 创建人 刘勇豪
     * 时间 2018-07-04 10:51:00
     */
    public function inputData($type)
    {
        $data = array();
        switch ($type) {
            case 'edit';
                break;
            case 'add';
                break;
        }
        $data['Brandname'] = input('post.brandname');
        return $data;
    }

    /**
     * 数据验证
     * 创建人 刘勇豪
     * 时间 2018-06-26 14:44:00
     */
    public function checkData($data = [])
    {
        $error_msg = "";
        //收件人
        if (!isset($data['o_receiver']) || $data['o_receiver'] == '') {
            $error_msg .= "收件人不能为空；";
        }

        //收件人手机
        if (!isset($data['o_mobile']) || !preg_match("/^1[34578]{1}\d{9}$/", $data['o_mobile'])) {
            $error_msg .= '请输入合法的手机号码；';
        }

        //收件人地址
        if (!isset($data['o_address']) || $data['o_address'] == '') {
            $error_msg .= "收件地址不能为空；";
        }

        //订单金额
        if (!isset($data['o_totalmoney']) || !is_numeric($data['o_totalmoney'])) {
            $error_msg .= "请输入合法的订单金额；";
        }

        return $error_msg;
    }


    /**
     * 生成订单SN
     * 创建人 刘勇豪
     */
    public function createOrderSN()
    {
        $o_sn = "pai";
        $rand = rand(100000, 999999);
        $time = time();
        $o_sn = $o_sn . $time . $rand;
        return $o_sn;
    }

    /**
     * 判断指定拍品的最高期数
     * 创建人 刘勇豪
     * @param $gdr_id 商品折扣人数表id
     * @return array
     */
    public function maxPeriods($gdr_id)
    {
        $periods = 0;
        $error_msg = '';

        if (!$gdr_id) {
            return ['status' => 0, 'msg' => "参数有误！"];
        }

        $where_pai['gdr_id'] = $gdr_id; // 指定拍品折扣
        $where_pai['o_paystate'] = ['gt',1]; // 已付款
        $list = $this->orderPaiList($where_pai, '*', 'o_periods desc');
        if (!empty($list)) {
            $periods = $list[0]['o_periods'];
        }
        return ['status' => 1, 'msg' => "ok！", 'data' => $periods];
    }

    /**判断并生成新下订单的期数
     * 创建人 刘勇豪
     *
     * @param $gdr_id 拍品的折扣
     * @return array
     */
    public function createPeriods($gdr_id)
    {
        $periods = 1;
        if (!$gdr_id) {
            return ['status' => 0, 'msg' => "参数有误！"];
        }

        // 最大期数
        $res = $this->maxPeriods($gdr_id);
        if ($res['status'] == 0) {
            return ['status' => 0, 'msg' => $res['msg']];
        }
        $maxPeriods = $res['data'];

        // 拍品设置的单品每期最少参与人数
        $peopleNum = $this->getPeopleNum($gdr_id);

        // 计算期数
        if ($maxPeriods != 0) {
            $periods = $maxPeriods;
            $where_award['gdr_id'] = $gdr_id; //指定拍品
            $where_award['oa_state'] = ['lt', 4]; // 废弃的除外
            $where_award['o_periods'] = $maxPeriods; // 指定期数
            $num = $this->countPaiNum($where_award);
            if ($num >= $peopleNum) {
                $periods++;
            }
        }
        return ['status' => 1, 'msg' => 'ok', 'data' => $periods];
    }

    /**
     * 统计指定拍品的抽奖码数量（这里是以实际抽奖码数量为准）
     * 创建人 刘勇豪
     */
    public function countPaiNum($where)
    {
        $num = Db('order_awardcode')->where($where)->count();
        return $num;
    }

    /**
     * 获取指定拍品的设定参与人数
     * 创建人 刘勇豪
     * @param $gdr_id
     * @return int
     */
    public function getPeopleNum($gdr_id)
    {
        $num = 0;
        $where_discount['gdr_id'] = $gdr_id;
        $info = Db::table("pai_goods_dt_record")
            ->where($where_discount)
            ->find();
        if (!empty($info)) {
            $num = $info['gdr_membernum'];
        }
        return $num;
    }

    public function get_json_member($where, $field, $order = 'o.o_id desc')
    {
        $list = Db($this->cache)
            ->alias('o')
            ->where($where)
            ->field($field)
            ->order($order)
            ->join('pai_member m', 'o.m_id=m.m_id', 'left')
            ->select();
        return $list;
    }

    /**
     * 获取指定拍品的用户剩余最大购买量(废弃)
     * 创建人 刘勇豪
     * @param $m_id 用户id,
     * @param $gp_id 拍品id。
     * @return array
     */
    public function get_max_pai_num($m_id, $gp_id)
    {
        if (!$m_id || !$gp_id) {
            return ['status' => 0, 'msg' => '参数错误！'];
        }

        $max_pai_num = 0; //初始值

        // 1.查询库存
        // 2.查询拍品设置的用户最大拍买数量
        $where['gp.gp_id'] = $gp_id;
        $info = Db::table("pai_goods_product")->alias('gp')
            ->where($where)
            ->field("gp.gp_stock,g.g_limited")
            ->join('pai_goods g', 'gp.g_id=g.g_id', 'left')
            ->find();

        $gp_stock = 0; // 初始化库存
        $g_limited = 0; // 初始化拍品设置的用户最大拍买数量

        if (!empty($info)) {
            if (!empty($info['gp_stock'])) {
                $gp_stock = $info['gp_stock'];
            }
            if (!empty($info['g_limited'])) {
                $g_limited = $info['g_limited'];
            }
        }
        $max_pai_num = $g_limited;


        // 1.判断用户已下单的数量
        $where_pai['m_id'] = $m_id;
        $where_pai['gp_id'] = $gp_id;
        $pai_num = $this->countPaiNum($where_pai);
        if ($max_pai_num > $pai_num) {
            $max_pai_num = $max_pai_num - $pai_num;
        } else {
            $max_pai_num = 0;
        }

        // 3.判断库存
        if ($gp_stock < 1) {
            $max_pai_num = 0;
        }
        return ['status' => 1, 'msg' => 'success！', 'data' => $max_pai_num];
    }

    /**
     * 获取指定拍品的用户剩余最大购买量（现在已改成只限制本期的了）
     * 创建人 刘勇豪
     * @param int $m_id 用户id
     * @param int $gdr_id 拍品折扣id。
     * @param int $periods 拍品折扣id。(如果没有指定就是获取最新一期的)
     * @return array|int
     */
    public function get_max_pai_num_bygdrid($m_id=0, $gdr_id = 0,$periods = 0)
    {
        $max_pai_num = 0; //初始值

        // 1.查询库存
        // 2.查询拍品设置的用户最大拍买数量
        $where['gdr.gdr_id'] = $gdr_id;

        $info = Db("goods_dt_record")->alias("gdr")
            ->where($where)
            ->field("gp.gp_stock,gp.gp_id,g.g_limited,gdr.gdr_membernum,gdr.gdr_limited")
            ->join('pai_goods g', 'gdr.g_id=g.g_id', 'left')
            ->join('pai_goods_product gp', 'gp.g_id=g.g_id', 'left')
            ->find();

        $gp_stock = 0; // 初始化库存
        $g_limited = 0; // 初始化拍品设置的用户最大拍买数量
        $gdr_membernum = 0; // 初始化拍品折扣的成团人数
        $gdr_limited = 0;// 初始化折拍限制人数

        if(empty($info)){
            return ['status'=>0, 'msg'=>"拍品不存在！"];
        }

        if (!empty($info['gp_stock'])) {
            $gp_stock = $info['gp_stock'];
        }
        if (!empty($info['g_limited'])) {
            $g_limited = $info['g_limited'];
        }
        if (!empty($info['gdr_membernum'])) {
            $gdr_membernum = $info['gdr_membernum'];
        }
        if (!empty($info['gdr_limited'])) {
            $gdr_limited = $info['gdr_limited'];
        }
        $gp_id = $info['gp_id'];
        $max_pai_num = $gdr_limited;

        // 1.判断当前用户已下单的数量 和 拍品限购
        // 本期期数
        $periods = 0;
        $call_periods = $this->createPeriods($gdr_id);
        if(!$call_periods['status']){
            return $call_periods;
        }
        $periods = $call_periods['data'];

        $where_pai['m_id'] = $m_id;
        $where_pai['gp_id'] = $gp_id;
        $where_pai['gdr_id'] = $gdr_id;
        $where_pai['o_periods'] = $periods;
        $my_pai_num = $this->countPaiNum($where_pai);

        if ($max_pai_num > $my_pai_num) {
            $max_pai_num = $max_pai_num - $my_pai_num;
        } else {
            $max_pai_num = 0;
        }

        // 2.判断所有用户下单数量 和 判断折扣剩余份数
        $where_pai = [];
        $where_pai['gdr_id'] = $gdr_id;
        if(!$periods){
            $call_periods = $this->createPeriods($gdr_id);
            if(!$call_periods['status']){
                return $call_periods;
            }
            $periods = $call_periods['data'];
        }
        $where_pai['o_periods'] = $periods;
        $total_pai_num = $this->countPaiNum($where_pai);
        $left_gdr_membernum = $gdr_membernum - $total_pai_num;
        if($left_gdr_membernum < $max_pai_num){
            $max_pai_num = $left_gdr_membernum;
        }

        // 3.判断库存
        if ($gp_stock < 1) {
            $max_pai_num = 0;
        }

        $data['left_max_pai_num'] = $max_pai_num;
        $data['gdr_membernum'] = $gdr_membernum;
        $data['g_limited'] = $g_limited;
        $data['gdr_limited'] = $gdr_limited;
        $data['gp_stock'] = $gp_stock;
        $data['my_pai_num'] = $my_pai_num;
        return ['status' => 1, 'msg' => 'success！', 'data' => $data];
    }


    /**
     * 订单支付
     * @param $o_id 订单编号
     * @param $m_id 用户id
     * @param $pwd 支付密码（md5之后的）
     * @return array
     */
    public function order_pay($o_id, $m_id, $pwd)
    {
        if (!$o_id || !$m_id || !$pwd) {
            return ['status' => 0, 'msg' => '非法请求！'];
        }

        // 用户信息
        $member = new MemberService();
        $mem_info = $member->get_info(['m_id' => $m_id]);
        $m_money = $mem_info['m_money']; // 账号资金
        $m_payment_pwd = $mem_info['m_payment_pwd']; // 会员支付密码
        $m_name = $mem_info['m_name'];
        $m_levelid = $mem_info['m_levelid'];
        $popularity = $mem_info['popularity'];//人气值。
        // 判断支付密码
        if ($pwd != $m_payment_pwd) {
            // 支付错误日志。。。TODO。。。

            // 支付错误的配置信息
            $res1 = Db("config")->where(['c_code'=>'PIN_PAY'])->find();
            $max_error_num = 5;
            if(!empty($res1)){
                $max_error_num = $res1['c_value'];
            }
            $res2 = Db("config")->where(['c_code'=>'ACCT_FROZEN'])->find();
            $max_frozen_time = 6;
            if(!empty($res2)){
                $max_frozen_time = $res2['c_value'];
            }
            $pwd_error_num = Cookie::get("pwd_error_num");
            if(!$pwd_error_num){
                $pwd_error_num = 1;
            }else{
                $pwd_error_num++;
            }
            Cookie::set("pwd_error_num",$pwd_error_num);
            if($pwd_error_num < $max_error_num){
                return ['status' => 2, 'msg' => "密码输入错误".$pwd_error_num."次，输错5次账号将会被冻结".$max_frozen_time."小时！"];
            }else{
                $frozen_time = Cookie::get("frozen_time");
                if(!$frozen_time){
                    $frozen_time = time();
                    Cookie::set("frozen_time",$frozen_time);
                }
                $ltfe_time = $frozen_time + 6*60*60 - time();
                if($ltfe_time>0){
                    $lefthour = $this->Sec2Time($ltfe_time);
                    return ['status' => 2, 'msg' => "密码输入错误次数过多，请".$lefthour."后重试！"];
                }
            }
        }
        Cookie::delete("pwd_error_num");
        Cookie::delete("frozen_time");
        // 订单详情
        $orderpai = new OrderPaiService();
        $orderInfo = $orderpai->orderPaiInfo(['o_id' => $o_id]);
        if (empty($orderInfo)) {
            return ['status' => 0, 'msg' => '服务器繁忙，订单信息不见了！'];
        }
        if ($orderInfo['o_paystate'] > 1) {
            return ['status' => 0, 'msg' => '这笔订单已经付过钱了哦！'];
        }

        // 商品详情
        $gp_id = $orderInfo['gp_id'];
        $gdr_id = $orderInfo['gdr_id'];
        $goods_product = new GoodsProductService();
        $where_discount['gp.gp_id'] = $gp_id;
        $where_discount['gdr.gdr_id'] = $gdr_id;
        $goods_info = $goods_product->getGoodsDiscountInfo($where_discount);
        if (empty($goods_info)) {
            return ['status' => 0, 'msg' => '支付商品已下架！', 'data' => $goods_info];
        }
        $g_name = $goods_info['g_name'];//商品名称
        $gdt_name = $goods_info['gdt_name'];//几折拍
        $g_img = $goods_info['g_img'];//商品图片
        $g_id = $goods_info['g_id'];//商品
        $gp_stock = $goods_info['gp_stock'];// 库存
        $is_fudai = $goods_info['is_fudai'];//是否是福袋

        // 判断库存
        if( $gp_stock < 1 ){
            return ['status' => 0, 'msg' => '对不起亲！这件商品已经被别人抢光了~', 'data' => $gp_stock];
        }

        $num = $orderInfo['gp_num']; //购买数量
        $gdr_membernum = $goods_info['gdr_membernum']; //折扣的成团人数
        $g_limited = $goods_info['g_limited']; //单人的购买受限数

        // 判断受限次数
        $call_max_pai_num = $orderpai->get_max_pai_num_bygdrid($m_id, $gdr_id);
        if(!$call_max_pai_num['status']){
            return $call_max_pai_num;
        }
        if ($num > $call_max_pai_num['data']['left_max_pai_num']) {
            return ['status' => 0, 'msg' => '本期购买量已超出购买上限了！'];
        }

        // 判断支付
        $o_totalmoney = $orderInfo['o_totalmoney'];
        if ($m_money < $o_totalmoney) {
            return ['status' => 0, 'msg' => '资金不足，请充值！'];
        }

        // 判断抽奖期数
        $periodsInfo = $orderpai->createPeriods($gdr_id);
        if (!$periodsInfo['status']) {
            return ['status' => 0, 'msg' => $periodsInfo['msg']];

        }
        $periods = $periodsInfo['data'];



        // 人气活动: 产生人气转化率
        $config = new ConfigService();
        $pop_rate_info = $config->configInfo(['c_code'=>'POP_RATE']);
        $pop_rate = 0.1;//默认值
        if(!empty($pop_rate_info)){
            $pop_rate = $pop_rate_info['c_value'];
        }

        // 订单处理 （事务：扣费 -> 更新order_pai表 -> 生成抽奖码->未中奖退款->经验。。。）
        Db::startTrans();
        try {

            // 1.扣费
            $newdata_mem['m_money'] = $m_money - $o_totalmoney;
            $where_mem['m_id'] = $m_id;
            $save1 = Db::table('pai_member')->where($where_mem)->update($newdata_mem);
            // 判断是否扣费成功
            if (!$save1) {
                throw new \Exception("扣费失败！");
            }

            // 2.更新order_pai表
            $where_order = [];
            $where_order['o_id'] = $o_id;
            $order_data['o_paytype'] = 3;//支付方式：余额支付
            $order_data['o_paystate'] = 2;//支付状态 1待付款，2已付款
            $order_data['o_paytime'] = time();//支付时间
            $order_data['o_periods'] = $periods;//拍品期数
            $order_data['gs_id'] = $goods_info['g_typeid'];//商品特殊属性
            $order_data['o_paymoney'] = $o_totalmoney;//支付金额
            $save2 = Db::table("pai_order_pai")->where($where_order)->update($order_data);
            // 2-1判断订单是否更新成功
            if (!$save2) {
                throw new \Exception("订单更新失败！");
            }

            // 3.记录扣款日志
            if($o_id){
                $money_log['ml_type'] = 'reduce';
                $money_log['ml_reason'] = '订单付款';
                if($is_fudai==1){
                    $money_log['ml_reason'] = '购买福袋';
                }
                $money_log['ml_table'] = 6;
                $money_log['ml_money'] = $o_totalmoney;
                $money_log['money_type'] = 1;
                $money_log['nickname'] = $m_name;
                $money_log['position'] = $m_levelid;
                $money_log['member_type'] = 1;
                $money_log['income_type'] = 2;
                $money_log['add_time'] = time();
                $money_log['from_id'] = $o_id;
                $money_log['m_id'] = $m_id;
                $log_id = Db::table('pai_money_log')->insertGetId($money_log);
            }

            // 4.更新下单用户的经验
            $exp = floor($o_totalmoney);//一元对应一经验
            if($exp){
                $res = Db::table('pai_member')->where(['m_id' => $m_id])->setInc('experience', $exp);
                if(!$res){
                    throw new \Exception("用户的经验添加失败");
                }
            }

            // 5.判断用户是否升级
            $callback = $this->mem_levelup($m_id);
            if(!$callback['status']){
                throw new \Exception($callback['msg']);
            }

            // pop1.人气活动 产生人气
            $add_pop = $pop_rate * $o_totalmoney;
            $max_add_pop = 100 - $popularity;
            if($add_pop > $max_add_pop){
                $add_pop = $max_add_pop;
            }
            if($add_pop){
                $res = Db("member")->where(['m_id'=>$m_id])->setInc('popularity',$add_pop);
                if(!$res){
                    throw new \Exception("数据库繁忙，人气添加失败！");
                }

                // pop2.人气值日志
                $data_log['pl_type'] = "add";
                $data_log['pl_num'] = $add_pop;
                $data_log['pl_time'] = time();
                $data_log['m_id'] = $m_id;
                $data_log['pl_reason'] = "人气值自动更新";
                $log_id = Db("popularity_log")->insertGetId($data_log);
            }

            // 6 判断订单是否免手续费
            $calback = $orderpai->is_notip($o_id);
            if(!$calback['status']){
                throw new \Exception($calback['msg']);
            }
            $o_is_no_tip = $calback['data'];
            if($o_is_no_tip == 2){
                $where_order = [];
                $where_order['o_id'] = $o_id;
                $save3 = Db::table("pai_order_pai")->where($where_order)->update(['o_is_no_tip'=>$o_is_no_tip]);
                if(!$save3){
                    throw new \Exception("数据库繁忙，订单免手续费状态更新失败！");
                }
            }
            //参拍金额大于
            if($o_totalmoney >= 50  && $mem_info['is_auction'] == 2){
                // x.判断是否是首次下订单（邓赛赛）
                $promoters = Db::table('pai_invitation_log il')
                    ->where(['il.m_id'=>$m_id,'descendants_num'=>1])
                    ->join('pai_member m','il.group_gene=m.m_id','left')
                    ->join('pai_member il_m','il.m_id=il_m.m_id','left')
                    ->field('il.m_id,m.m_id promoters_mid,m.is_promoters,il.descendants_num,il_m.is_auction')
                    ->find();
                if($promoters && !empty($promoters['m_id']) && ($promoters['descendants_num'] == 2 || $promoters['descendants_num'] == 1) && ($promoters['is_promoters'] == 4 || $promoters['is_promoters'] == 5) && $promoters['promoters_mid'] && $promoters['is_auction'] == 2){
                    $callback = $this->promoters_descendants_isfirstorder($m_id,$promoters['promoters_mid'],$promoters['is_promoters'],$promoters['descendants_num']);
                    if(!$callback['status']){
                        throw new \Exception($callback['msg']);
                    }
                }else{
                    // x.判断是否是首次下订单
                    $callback = $this->if_myfirstorder($m_id);
                    if(!$callback['status']){
                        throw new \Exception($callback['msg']);
                    }
                }
            }

            // 7.生成抽奖码
            $order_awardcode = new OrderAwardcodeService();
            $awardcode = $order_awardcode->getAwardcode($o_id);
            if(!$awardcode['status']){
                throw new \Exception($awardcode['msg']);
            }

            $arr_awardcode = $awardcode['data'];
            if(empty($arr_awardcode)){
                throw new \Exception("订单错误！生成幸运码失败鸟~'");
            }
            $add_all = $order_awardcode->orderAwardcodeAddAll($arr_awardcode);
            if(!$add_all){
                throw new \Exception("数据库繁忙！");
            }

            // 8.判断并产生幸运码
            // 已参团人数
            $where_sum = [];
            $where_sum['gdr_id'] = $gdr_id;// 折id
            $where_sum[ 'o_periods'] = $periods;// 期数
            $sum_gp_num = Db('order_awardcode')->where($where_sum)->count();
            $is_mem_full = 0;

            if($sum_gp_num >= $gdr_membernum){
                $is_mem_full = 1;
                // 开始抽奖
                $now_time = time();

                $newNum = mt_rand(0,5);

                $mod = time()%2;
                if($mod){
                    $now_time = $now_time + $newNum;
                }else{
                    $now_time = $now_time - $newNum;
                }

                // 幸运数字
                $luck_id = $now_time % $sum_gp_num + 1;
                $data['luck_id'] = $luck_id;

                $where_luck = [];
                $where_luck['oa_number'] = $luck_id;
                $where_luck['gdr_id'] = $gdr_id;
                $where_luck['o_periods'] = $periods;
                $luck_info = Db('order_awardcode')->where($where_luck)->find();
                if(empty($luck_info)){
                    throw new \Exception("gdr_id：".$gdr_id."o_periods:".$periods."中拍号码:".$luck_id."中拍信息不存在！");
                }

                $luck_oa_id = $luck_info['oa_id']; // 中拍码id
                $luck_o_id = $luck_info['o_id']; // 订单id
                $luck_gp_id = $luck_info['gp_id'];// 拍品id

                // 幸运订单详情
                $luck_order_info = Db('order_pai')->where(['o_id'=>$luck_o_id])->find();
                if(empty($luck_order_info)){
                    throw new \Exception("gdr_id：".$gdr_id."o_periods:".$periods."中拍号码:".$luck_id."中拍订单不存在！");
                }

                //4-1.更新中拍码状态
                $where_awardcode = [];
                $where_awardcode['gdr_id'] = $gdr_id;// 折id
                $where_awardcode[ 'o_periods'] = $periods;// 期数
                $res1 = Db('order_awardcode')->where($where_awardcode)->update(['oa_state'=>3]);
                $res2 = Db('order_awardcode')->where(['oa_id'=>$luck_oa_id])->update(['oa_state'=>2]);
                // 判断中拍码状态更新成功
                if (!$res1 || !$res2) {
                    throw new \Exception("中拍码状态更新失败！");
                }

                //4-1-1.更新order_pai表付款时间
                $where_order = [];
                $where_order['o_id'] = $o_id;
                $order_data['o_paytime'] = $now_time;//支付时间
                Db::table("pai_order_pai")->where($where_order)->update($order_data);

                // 4-2.更新订单状态
                $where_order = [];
                $where_order['gdr_id'] = $gdr_id;
                $where_order['o_periods'] = $periods;
                $where_order['o_state'] = 1;
                $where_order['o_paystate'] = 2;

                // 4-2-1福袋订单不需要退款
                if($is_fudai == 1){
                    $res3 = Db('order_pai')->where($where_order)->update(['o_state'=>12]);
                }else{
                    $res3 = Db('order_pai')->where($where_order)->update(['o_state'=>10,'o_paystate'=>3]);
                }

                if($luck_order_info['gp_num'] > 1){
                    $res4 = Db('order_pai')->where(['o_id'=>$luck_o_id])->update(['o_state'=>2,'o_publishtime'=>$now_time]);
                }else{
                    $res4 = Db('order_pai')->where(['o_id'=>$luck_o_id])->update(['o_state'=>2,'o_paystate'=>2,'o_publishtime'=>$now_time]);
                }
                // 判断订单码状态更新成功
                if (!$res3 || !$res4) {
                    $luck_info = Db('order_pai')->where(['o_id'=>$luck_o_id])->find();
                    if(!empty($luck_info) && $luck_info['o_state'] != 2){
                        throw new \Exception("订单码状态更新失败！");
                    }
                }

                // 发送抽奖信息
                $systemMsg = new SystemMsgService();

                if($is_fudai == 1){
                   // 如果是福袋
                    // 中拍信息
                    $luck_order_info = Db("order_pai")->where(['o_id'=>$luck_o_id])->find();
                    $msg_data['sm_addtime'] = time();//系统消息添加时间
                    $msg_data['sm_display'] = 2;//2中拍消息
                    $msg_data['sm_title'] = "福袋信息";//消息标题
                    $msg_data['sm_brief'] = "恭喜您获得惊喜大福袋！";//消息简介
                    $msg_data['sm_content'] = "恭喜您！您在商品：'".$g_name."'的'".$gdt_name."'活动中的第'".$periods."'期获得惊喜大福袋 ";//消息内容
                    $msg_data['sm_img'] = $g_img;//图片(商品等简介图片)
                    $msg_data['g_id'] = $g_id;//商品ID
                    $msg_data['shop_price'] = $luck_order_info['o_money'] + $luck_order_info['o_deliverfee'];//商品价格 + 运费
                    $msg_data['o_id'] = $luck_order_info['o_id'];//订单ID
                    $msg_data['to_mid'] = $luck_order_info['m_id'];//收消息人ID
                    $res = $systemMsg->add_msg($msg_data);

                    //未中拍信息
                    $where_msg = $where_order;
                    $where_msg['o_state'] = 12;
                    $where_msg['gp_id'] = $gp_id;
                    $where_msg['o_periods'] = $periods;
                    $where_msg['o_paystate'] = ['gt',2];//退款状态
                    $msg_order_list = Db("order_pai")->where($where_msg)->select();
                    if(!empty($msg_order_list)){
                        foreach($msg_order_list as $vo){
                            $msg_data['sm_addtime'] = time();//系统消息添加时间
                            $msg_data['sm_display'] = 2;//2中拍消息
                            $msg_data['sm_title'] = "福袋信息";//消息标题
                            $msg_data['sm_brief'] = "恭喜您获得超值福袋！";//消息简介
                            $msg_data['sm_content'] = "恭喜您！您在商品：'".$g_name."'的'".$gdt_name."'活动中的第'".$periods."'期参拍中获得超值福袋";//消息内容
                            $msg_data['sm_img'] = $g_img;//图片(商品等简介图片)
                            $msg_data['g_id'] = $g_id;//商品ID
                            $msg_data['shop_price'] = $vo['o_money'] + $vo['o_deliverfee'];// 商品价格 + 运费
                            $msg_data['o_id'] = $vo['o_id'];//订单ID
                            $msg_data['to_mid'] = $vo['m_id'];//收消息人ID
                            $res = $systemMsg->add_msg($msg_data);
                        }
                    }
                }else{
                    //普通商品// 中拍信息
                    $luck_order_info = Db("order_pai")->where(['o_id'=>$luck_o_id])->find();
                    $msg_data['sm_addtime'] = time();//系统消息添加时间
                    $msg_data['sm_display'] = 2;//2中拍消息
                    $msg_data['sm_title'] = "参拍结果信息";//消息标题
                    $msg_data['sm_brief'] = "恭喜您！您的参拍订单号为：".$luck_order_info['o_sn']."的订单抽中拍品啦！";//消息简介
                    $msg_data['sm_content'] = "恭喜您！您在商品：'".$g_name."'的'".$gdt_name."'活动中的第'".$periods."'期摘得奖品，本期中拍数字为 ".$luck_id;//消息内容
                    $msg_data['sm_img'] = $g_img;//图片(商品等简介图片)
                    $msg_data['g_id'] = $g_id;//商品ID
                    $msg_data['shop_price'] = $luck_order_info['o_money'] + $luck_order_info['o_deliverfee'];//商品价格 + 运费
                    $msg_data['o_id'] = $luck_order_info['o_id'];//订单ID
                    $msg_data['to_mid'] = $luck_order_info['m_id'];//收消息人ID
                    $res = $systemMsg->add_msg($msg_data);

                    //未中拍信息
                    $where_msg = $where_order;
                    $where_msg['o_state'] = 10;
                    $where_msg['gp_id'] = $gp_id;
                    $where_msg['o_periods'] = $periods;
                    $where_msg['o_paystate'] = ['gt',2];//退款状态
                    $msg_order_list = Db("order_pai")->where($where_msg)->select();
                    if(!empty($msg_order_list)){
                        foreach($msg_order_list as $vo){
                            $msg_data['sm_addtime'] = time();//系统消息添加时间
                            $msg_data['sm_display'] = 2;//2中拍消息
                            $msg_data['sm_title'] = "参拍结果信息";//消息标题
                            $msg_data['sm_brief'] = "好遗憾啊！您的参拍订单号为：".$vo['o_sn']."的订单与奖品擦肩而过！";//消息简介
                            $msg_data['sm_content'] = "很遗憾！您在商品：'".$g_name."'的'".$gdt_name."'活动中的第'".$periods."'期参拍中未能摘得奖品，本期中拍数字为 ".$luck_id;//消息内容
                            $msg_data['sm_img'] = $g_img;//图片(商品等简介图片)
                            $msg_data['g_id'] = $g_id;//商品ID
                            $msg_data['shop_price'] = $vo['o_money'] + $vo['o_deliverfee'];//商品价格 + 运费
                            $msg_data['o_id'] = $vo['o_id'];//订单ID
                            $msg_data['to_mid'] = $vo['m_id'];//收消息人ID
                            $res = $systemMsg->add_msg($msg_data);
                        }
                    }
                }

                // 4-3.更新库存
                $where_gp['gp_id'] = $luck_gp_id;

                $gp_stock = Db('goods_product')->where($where_gp)->value("gp_stock");
                if($gp_stock < 1){
                    return ['status' => 0, 'msg' => '对不起亲！这件商品已经被别人抢光了~', 'data' => $gp_stock];
                }
                $res5 = Db('goods_product')->where($where_gp)->setDec('gp_stock',1); // 拍品库存减1
                // 判断是否扣库存成功
                if (!$res5) {
                    throw new \Exception("拍品库存更新失败！");
                }

                //销售量+1
                $res6 = Db('goods_product')->where($where_gp)->setInc("gp_salenum",1);
                // 判断是否销售数量是否添加成功
                if (!$res6) {
                    throw new \Exception("拍品库存更新失败！");
                }

                // x.判断商品是否已卖完
                $callback = $this->if_no_stock($gp_id);
                if(!$callback['status']){
                    throw new \Exception($callback['msg']);
                }

            }

            // 执行提交操作
            Db::commit();
            $back = '';
            $back['is_mem_full'] = $is_mem_full;
            return ['status' => 1, 'msg' => '扣费成功！','data'=>$back];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            // 错误日志
            $error_data['el_type_id'] = 1;
            $error_data['el_description'] = $e->getMessage();
            $error_data['m_id'] = $m_id;
            $error_data['el_time'] = time();
            Db::table('pai_error_log')->data($error_data)->insert();

            // 获取提示信息
            return ['status' => 0, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 首次参拍给推广员提供推荐费
     * 邓赛赛
     */
    public function promoters_descendants_isfirstorder($m_id,$promoters_mid,$is_promoters,$descendants_num){
        if(!($is_promoters == 4) && !($is_promoters == 5)){
            return ['status'=>0,'msg'=>"推荐者非推广员状态"];
        }
        $where_find['m_id'] = $m_id;
        $where_find['o_paystate'] = ['gt',1];
        $is_auction = Db::table('pai_member')->where('m_id',$m_id)->value('is_auction');
        if($is_auction == 2){

            Db::table('pai_member')->where('m_id',$m_id)->update(['is_auction'=>1,'edit_time'=>time()]);

            $first_oid = Db("order_pai")->field("o_id")->where($where_find)->order('o_id desc')->value('o_id');
            $mem_info = Db("member")->where(['m_id'=>$m_id])->find();
            if(empty($mem_info)){
                return ['status'=>0,'msg'=>"下单账号信息不存在！"];
            }
            $m_mobile = $this->decrypt($mem_info['m_mobile']);//手机号
            $m_secret_mobile = substr($m_mobile,0,3)."****".substr($m_mobile,7,4);
            $m_name = $mem_info['m_name'];
            $m_levelid = $mem_info['m_levelid'];
                // 查看上一级的等级详情
                $p_mem_info = Db::table('pai_member')->alias("m")
                    ->join('pai_member_level ml', 'ml.ml_id = m.m_levelid', 'left')
                    ->where(['m.m_id'=>$promoters_mid])
                    ->find();
                $ml_newbuyer_exp = 1;//默认 新推荐会员并拍购的多少经验/人
                if(!empty($p_mem_info) && !empty($p_mem_info['ml_newbuyer_exp'])){
                    $ml_newbuyer_exp = $p_mem_info['ml_newbuyer_exp'];
                }
                // 增加经验值
                $res = Db::table('pai_member')->where(['m_id' => $promoters_mid])->setInc('experience', $ml_newbuyer_exp);
                if(!$res){
                    return ['status'=>0,'msg'=>"来自账号{".$m_mobile."}的首次参拍经验收益添加失败了"];
                }
                $config = new ConfigService();
                //推广员收益 领会员推荐费直推10元/人（ 间推2元/人）
                if($is_promoters == 5){
                    //说明考核期此粉丝未参拍,粉丝首次参拍再给正式推广员10元直推推荐奖

                    $where = [
                            'c_state'=>0,
                            'c_code'=>'TGY_ZT'
                        ];
                    $TGY_ZT = $config->configGetValue($where,'c_value');//直推
                    $data_income['i_time'] = time();
                    $data_income['i_typeid'] = 5;
                    $data_income['m_id'] = $promoters_mid;
                    $data_income['i_state'] = 8;
                    $data_income['i_money'] = $TGY_ZT;
                    $data_income['i_reason'] = '来自账号{'.$m_secret_mobile."}直推会员的首次参拍收益";
                    $data_income['i_from_mid'] = $m_id;
                    $data_income['i_from_id'] = $first_oid;
                    $income_id = Db::table('pai_income')->insertGetId($data_income);
                    if($income_id){
                        // 金钱日志
                        $money_log['ml_type'] = 'add';
                        $money_log['ml_reason'] = "下级首次参拍收益";
                        $money_log['ml_table'] = 3;
                        $money_log['ml_reason_id'] = 7;
                        $money_log['ml_money'] = $TGY_ZT;
                        $money_log['money_type'] = 2;
                        $money_log['nickname'] = $m_name;
                        $money_log['position'] = $m_levelid;
                        $money_log['member_type'] = 1;
                        $money_log['income_type'] = 2;
                        $money_log['add_time'] = time();
                        $money_log['from_id'] = $m_id;
                        $money_log['m_id'] = $promoters_mid;
                        $money_log['state'] = 8;
                        $log_id = Db::table('pai_money_log')->insertGetId($money_log);
                    }
                    if(!$income_id){
                        return ['status'=>0,'msg'=>"来自账号{".$m_mobile."}直推会员的首次参拍收益添加失败了"];
                    }
                    Db::table('pai_member')->where('m_id',$promoters_mid)->setInc('m_income', $TGY_ZT);
                }else if($is_promoters == 4){
                    //读取配置文件奖励
                    $where = [
                        'c_state'=>0,
                        'c_code'=>'TGY_ZT'
                    ];
                    $c_value = $config->configGetValue($where,'c_value');
                    //插入冻结收益
                    $pa_info = [
                        'm_id'=>$promoters_mid,
                        'from_id'=>$m_id,
                        'p_money'=>$c_value,
                        'e_money'=>10,
                        'descendants_num'=>1,
                        'type'=>1,
                        'state'=>1,
                        'add_time'=>time(),
                    ];
                    $promoters = new PromotersFrozenService();
                    $promoters->get_add($pa_info);
                }
                    //推广员收益 领会员推荐费直推10元/人（ 间推2元/人）
                    $top_member = Db::table('pai_member m')
                        ->where(['m.m_id'=>$promoters_mid,'m.m_state'=>0])
                        ->join('pai_member tm','m.tj_mid = tm.m_id','left')
                        ->field('tm.m_id,tm.is_promoters')
                        ->find();
                    if(!empty($top_member) && $top_member['is_promoters'] == 5){
                        //说明考核期此粉丝未参拍,粉丝首次参拍再给正式推广员2元间推推荐奖
                        $where = [
                            'c_state'=>0,
                            'c_code'=>'TGY_JT'
                        ];
                        $TGY_JT = $config->configGetValue($where,'c_value');//间推
                        $data_income['i_time'] = time();
                        $data_income['i_typeid'] = 6;
                        $data_income['m_id'] = $top_member['m_id'];
                        $data_income['i_state'] = 8;
                        $data_income['i_money'] = $TGY_JT;
                        $data_income['i_reason'] = '来自账号{'.$m_secret_mobile."}间推会员的首次参拍收益";
                        $data_income['i_from_mid'] = $m_id;
                        $data_income['i_from_id'] = $first_oid;
                        $income_id = Db::table('pai_income')->insertGetId($data_income);
                        if($income_id){
                            // 金钱日志
                            $money_log['ml_type'] = 'add';
                            $money_log['ml_reason'] = "推广员间推会员首次参拍收益";
                            $money_log['ml_table'] = 3;
                            $money_log['ml_reason_id'] = 7;
                            $money_log['ml_money'] = $TGY_JT;
                            $money_log['money_type'] = 2;
                            $money_log['nickname'] = $m_name;
                            $money_log['position'] = $m_levelid;
                            $money_log['member_type'] = 1;
                            $money_log['income_type'] = 2;
                            $money_log['add_time'] = time();
                            $money_log['from_id'] = $m_id;
                            $money_log['m_id'] = $top_member['m_id'];
                            $money_log['state'] = 8;
                            $log_id = Db::table('pai_money_log')->insertGetId($money_log);
                        }
                        if(!$income_id){
                            return ['status'=>0,'msg'=>"来自账号{".$m_mobile."}直推会员的首次参拍收益添加失败了"];
                        }
                        Db::table('pai_member')->where('m_id',$top_member['m_id'])->setInc('m_income', $TGY_JT);
                    }else if(!empty($top_member) && $top_member['is_promoters'] == 4){
                        $where3 = [
                            'c_state'=>0,
                            'c_code'=>'TGY_JT'
                        ];
                        $c_value = $config->configGetValue($where3,'c_value');
                            //插入冻结收益
                            $pa_info = [
                                'm_id'=>$top_member['m_id'],
                                'from_id'=>$m_id,
                                'p_money'=>$c_value,
                                'e_money'=>0,
                                'descendants_num'=>2,
                                'type'=>2,
                                'state'=>1,
                                'add_time'=>time(),
                            ];
                            $promoters = new PromotersFrozenService();
                            $promoters->get_add($pa_info);
                    }
            return ['status'=>6,'msg'=>"首次参拍推广员已获得收益!", 'data'=>$m_id];
        }else{
            return ['status'=>8,'msg'=>"首次参拍收益已产生!", 'data'=>$m_id];
        }
    }
    /**
     * 秒转换小时 分钟 秒
     * @param $time
     * @return string
     */
    function Sec2Time($time)
    {
        $value = array(
            "hours" => 0,
            "minutes" => 0,
            "seconds" => 0
        );

        if (is_numeric($time)) {

            if ($time >= 3600) {
                $value["hours"] = floor($time / 3600);
                $time = ($time % 3600);
            }
            if ($time >= 60) {
                $value["minutes"] = floor($time / 60);
                $time = ($time % 60);
            }
            $value["seconds"] = floor($time);
        }

        $t = $value["hours"] . "小时" . $value["minutes"] . "分" . $value["seconds"] . "秒";
        Return $t;
    }


    /**
     * 订单详情页数据
     * 刘勇豪
     * @param $o_id 订单编号
     * @return array
     */
    public function order_info_page($o_id)
    {

        if (!$o_id) {
            return ['status' => 0, 'msg' => "id参数错误！"];
        }

        $where['o.o_id'] = $o_id;
        $info = $this->getMoreInfo($where);

        $o_addtime = $info['o_addtime'];

        // 支付状态1待付款，2已付款，3退款中，4退款完成
        $o_paystate = $info['o_paystate'];

        // 订单状态1参拍中，2已中拍，3已发货，4已签收，5待评价，6交易完成，10未中拍
        $o_state = $info['o_state'];

        // 剩余支付时间
        $live_paytime = $o_addtime + 24 * 60 * 60 - time();
        $liva_date = $this->Sec2Time($live_paytime);

        $info['live_paytime'] = $live_paytime; //剩余支付时间
        $info['liva_date'] = $liva_date; // 剩余支付时间（格式化）

        $info['o_mobile_secret'] = substr($info['o_mobile'], 0, 3) . "****" . substr($info['o_mobile'], 7, 4);

        // 中拍揭晓时间
        $call_publish = $this->get_pai_publish_time($info['gdr_id'], $info['o_periods']);
        $publish_time = 0;
        if($call_publish['status']){
            $publish_time = $call_publish['data'];
        }
        $info['o_publishtime'] = $publish_time;

        return ['status' => 1, 'msg' => "success !", 'data' => $info];
    }

    /**
     * @param $gdr_id
     * @param $o_periods
     * @return float 2位小数,百分比*100
     */
    public function get_orderpai_rate($gdr_id, $o_periods)
    {

        $where['gdr_id'] = $gdr_id;
        $where['o_periods'] = $o_periods;

        $sum_gp_num = Db('order_awardcode')->where($where)->count();
        $sum_gp_num = intval($sum_gp_num);

        $gdr_membernum = Db('goods_dt_record')
            ->where(['gdr_id' => $gdr_id])
            ->value('gdr_membernum');
        $gdr_membernum = intval($gdr_membernum);

        $rate = round($sum_gp_num * 100 / $gdr_membernum, 1);
        if ($rate > 100) {
            $rate = 100;
        }

        return $rate;
    }

    /**
     * 分页查询订单详细列表（order_pai,member）
     * 创建人 刘勇豪
     * 时间 2018-07-11 16:09:00
     */
    public function orderLimitList($where = '', $order = 'o.o_id asc', $field = '*', $limit = "0,5", $cache = 'order_pai')
    {
        $limit_list = $this->orderPai->getOrderLimitList($where, $order, $field, $limit, $cache = 'order_pai');
        return $limit_list;
    }

    /**
     * 应前端的要求下拉加载的时候一定要统计总条数
     * 刘勇豪
     * @param $where
     * @return int|string
     */
    public function orderCount($where){
        $count = Db("order_pai")->alias('o')
            ->where($where)
            ->join('pai_member m', 'm.m_id = o.m_id', 'left')
            ->count();
        return $count;
    }


    /**
     * 检查并生成幸运码
     * 创建人 刘勇豪
     * @param $gdr_id
     * @param $o_periods
     * @return array
     */
    public function set_luck_awardcode($gdr_id, $o_periods)
    {
        if (!$gdr_id || !$o_periods) {
            return ['status' => 0, 'msg' => "参数错误！"];
        }

        $data = []; //成功的返回信息

        // $m_id
        $m_id = Cookie::get("m_id");
        $mem = new MemberService();
        $m_id = $mem->decrypt($m_id); //解密账号id
        $m_id = str_replace('abcde', '', $m_id); //删除多余字符

        // 设置的折 对应的人数
        $gdr_membernum = Db("goods_dt_record")->where(['gdr_id' => $gdr_id])->value("gdr_membernum");

        // 已参团人数
        $where_sum['gdr_id'] = $gdr_id; // 折id
        $where_sum['o_periods'] = $o_periods; // 期数
        $sum_gp_num = Db('order_awardcode')->where($where_sum)->count();

        $data['m_id'] = $m_id;
        $data['gdr_id'] = $gdr_id;
        $data['o_periods'] = $o_periods;
        $data['sum_gp_num'] = $sum_gp_num;
        $data['gdr_membernum'] = $gdr_membernum;

        if ($sum_gp_num >= $gdr_membernum) {
            // 开始抽奖
            $now_time = time();

            // 幸运数字
            $luck_id = $now_time % $sum_gp_num;
            $data['luck_id'] = $luck_id;

            $where_luck['oa_number'] = $luck_id;
            $where_luck['gdr_id'] = $gdr_id;
            $where_luck['o_periods'] = $o_periods;
            $luck_info = Db('order_awardcode')->where($where_luck)->find();
            if (empty($luck_info)) {
                // 错误日志
                $error_data['el_type_id'] = 1;
                $error_data['el_description'] = "gdr_id：" . $gdr_id . "o_periods:" . $o_periods . "中拍号码:" . $luck_id . "中拍信息不存在！";
                $error_data['m_id'] = $m_id;
                $error_data['el_time'] = time();
                Db::table('pai_error_log')->data($error_data)->insert();
                return ['status' => 0, 'msg' => "中拍号码不存在！", 'data' => $data];
            }

            $oa_id = $luck_info['oa_id']; // 中拍码id
            $o_id = $luck_info['o_id']; // 订单id
            $gp_id = $luck_info['gp_id']; // 拍品id

            Db::startTrans();
            try {
                //1.更新中拍码状态
                $where_awardcode['gdr_id'] = $gdr_id; // 折id
                $where_awardcode['o_periods'] = $o_periods; // 期数
                $res1 = Db('order_awardcode')->where($where_awardcode)->update(['oa_state' => 3]);
                $res2 = Db('order_awardcode')->where(['oa_id' => $oa_id])->update(['oa_state' => 2]);
                // 判断中拍码状态更新成功
                if (!$res1 || !$res2) {
                    throw new \Exception("中拍码状态更新失败！");
                }

                // 2.更新订单状态
                $where_order['gdr_id'] = $gdr_id;
                $where_order['o_periods'] = $o_periods;
                $where_order['o_state'] = 1;
                $res3 = Db('order_pai')->where($where_order)->update(['o_state' => 10]);
                $res4 = Db('order_awardcode')->where(['o_id' => $o_id])->update(['o_state' => 2]);
                // 判断订单码状态更新成功
                if (!$res3 || !$res4) {
                    throw new \Exception("订单码状态更新失败！");
                }

                // 3.更新库存
                $where_gp['gp_id'] = $gp_id;
                $res5 = Db('goods_product')->where($where_gp)->setDec('gp_stock', 1); // 拍品库存减1
                // 判断是否扣费成功
                if (!$res5) {
                    throw new \Exception("拍品库存更新失败！");
                }

                // 执行提交操作
                Db::commit();
                return ['status' => 8, 'msg' => '本期中拍信息已产生！', 'data' => $data];
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();

                // 错误日志
                $error_data['el_type_id'] = 1;
                $error_data['el_description'] = $e->getMessage();
                $error_data['m_id'] = $m_id;
                $error_data['el_time'] = time();
                Db::table('pai_error_log')->data($error_data)->insert();

                // 获取提示信息
                return ['status' => 0, 'msg' => $e->getMessage()];
            }
        }
        return ['status' => 1, 'msg' => "本期抽奖名额没满，不能抽奖 !", 'data' => $data];
    }

    /**
     * 用户升级
     * 刘勇豪
     * @param $m_id:用户id
     * @return mixed
     */
    public function mem_levelup($m_id){
        $mem_info = Db("member")->where(["m_id"=>$m_id])->find();

        if(empty($mem_info)){
            return ['status'=>0,'msg'=>"用户".$m_id."的信息不存在",'data'=>''];
        }

        $m_levelid = $mem_info['m_levelid'];// 用户等级ID
        $experience = $mem_info['experience'];// 经验值

        // 只查大于当前等级的等级列表
        $where_level['ml_id'] = ['gt', $m_levelid];
        $levellist = Db('member_level')->where($where_level)->order('ml_id asc')->select();
        $new_ml_id = $m_levelid;
        if (!empty($levellist)) {
            foreach ($levellist as $v) {
                $ml_tj1 = $v['ml_tj1'];
                $ml_tj2 = $v['ml_tj2'];
                if ($experience >= $ml_tj1 && $experience < $ml_tj2) {
                    $new_ml_id = $v['ml_id'];
                    break;
                }
            }
        }

        // 当用户升级
        if ($new_ml_id > $m_levelid) {
            // 1.更新用户等级
            $data_update['m_levelid'] = $new_ml_id;
            $res = Db::table('pai_member')->where(['m_id' => $m_id])->update($data_update);
            if(!$res){
                return ['status'=>0,'msg'=>"数据库繁忙，用户升级失败！",'data'=>''];
            }
            $m_levelid = $new_ml_id;
        }

        $back['m_id'] = $m_id;
        $back['levelid'] = $m_levelid;
        return ['status'=>8,'msg'=>'success!','data'=>$back];
    }


    /**
     * 判断用户首次升级
     * @param $m_id
     * @param int $from_id
     * @return array
     */
    public function mem_first_levelup($m_id,$from_id=0){

        // 参拍用户信息
        $mem_info = Db("member")->where(["m_id"=>$m_id])->find();
        if(empty($mem_info)){
            return ['status'=>0,'msg'=>"用户".$m_id."的信息不存在",'data'=>''];
        }

        $tj_mid = $mem_info['tj_mid'];// 推荐上级ID
        $m_name = $mem_info['m_name'];// 会员名
        $m_mobile = $this->decrypt($mem_info['m_mobile']);//手机号
        $m_secret_mobile = substr($m_mobile,0,3)."****".substr($m_mobile,7,4);
        $m_type = $mem_info['m_type'];// 账号类型0为普通会员，1为商家会员（以后会用）
        $m_levelid = $mem_info['m_levelid'];// 用户等级ID
        $experience = $mem_info['experience'];// 经验值

        if($m_levelid > 1){
            return ['status'=>1,'msg'=>"此用户的升级收益已经发放过了哟~"];
        }

    }

    /**
     * 判断是否第一次下单，
     * 第一次下单要给我的上一家推荐人
     * @param $m_id
     * @return array
     */
    public function if_myfirstorder($m_id){
        $where_find['m_id'] = $m_id;
        $where_find['o_paystate'] = ['gt',1];
        $where_find['o_totalmoney'] = ['>=',50];
        $list = Db("order_pai")->field("o_id")->where($where_find)->limit("2")->select();
        //此用户是否有给上家奖励
        $where_log = [
            'income_type'=>1,
            'from_id'   =>$m_id,
            'state'     =>8
        ];
        $is_tj_money = Db('money_log')->where($where_log)->count();
        //第一次参拍和未给上家提供过推荐奖励
        if(count($list) == 1 && empty($is_tj_money)){
            $first_oid = $list[0]['o_id'];
            $mem_info = Db("member")->where(['m_id'=>$m_id])->find();
            if(empty($mem_info)){
                return ['status'=>0,'msg'=>"下单账号信息不存在！"];
            }
            $tj_mid = $mem_info['tj_mid'];
            $m_mobile = $this->decrypt($mem_info['m_mobile']);//手机号
            $m_secret_mobile = substr($m_mobile,0,3)."****".substr($m_mobile,7,4);
            $m_name = $mem_info['m_name'];
            $m_levelid = $mem_info['m_levelid'];

            //用户参拍状态
            Db::table('pai_member')->where('m_id',$m_id)->update(['is_auction'=> 1,'edit_time'=>time()]);

            // 如果有上一级
            if($tj_mid){
                // 查看上一级的等级详情
                $p_mem_info = Db::table('pai_member')->alias("m")
                    ->join('pai_member_level ml', 'ml.ml_id = m.m_levelid', 'left')
                    ->where(['m.m_id'=>$tj_mid])
                    ->find();
                $ml_newbuyer_exp = 1;//默认 新推荐会员并拍购的多少经验/人
                if(!empty($p_mem_info) && !empty($p_mem_info['ml_newbuyer_exp'])){
                    $ml_newbuyer_exp = $p_mem_info['ml_newbuyer_exp'];
                }
                // 增加经验值
                $res = Db::table('pai_member')->where(['m_id' => $tj_mid])->setInc('experience', $ml_newbuyer_exp);
                if(!$res){
                    return ['status'=>0,'msg'=>"来自账号{".$m_mobile."}的首次参拍经验收益添加失败了"];
                }

                //上级得收益 领会员推荐费（10元/人）
                $ml_newbuyer_income = 10;//默认 新推荐会员并拍购的多少收益/人
                if(!empty($p_mem_info) && !empty($p_mem_info['ml_newbuyer_income'])){
                    $ml_newbuyer_income = $p_mem_info['ml_newbuyer_income'];
                }
                $p_profit = $ml_newbuyer_income;
                Db::table('pai_member')->where(['m_id' => $tj_mid])->setInc('m_income', $p_profit);
                $data_income['i_time'] = time();
                $data_income['i_typeid'] = 2;
                $data_income['m_id'] = $tj_mid;
                $data_income['i_state'] = 8;
                $data_income['i_money'] = $p_profit;
                $data_income['i_reason'] = '来自账号{'.$m_secret_mobile."}的首次参拍收益";
                $data_income['i_from_mid'] = $m_id;
                $data_income['i_from_id'] = $first_oid;
                $income_id = Db::table('pai_income')->insertGetId($data_income);
                if($income_id){
                    // 金钱日志
                    $money_log['ml_type'] = 'add';
                    $money_log['ml_reason'] = "下级首次参拍收益";
                    $money_log['ml_table'] = 3;
                    $money_log['ml_money'] = $p_profit;
                    $money_log['money_type'] = 2;
                    $money_log['nickname'] = $m_name;
                    $money_log['position'] = $m_levelid;
                    $money_log['member_type'] = 1;
                    $money_log['income_type'] = 2;
                    $money_log['add_time'] = time();
                    $money_log['from_id'] = $income_id;
                    $money_log['m_id'] = $tj_mid;
                    $log_id = Db::table('pai_money_log')->insertGetId($money_log);
                }

                //上上家是否为正式推广员
                $top_member = Db::table('pai_invitation_log il')
                    ->where(['il.m_id'=>$m_id,'descendants_num'=>2])
                    ->join('pai_member m','il.group_gene=m.m_id','left')
                    ->join('pai_member il_m','il.m_id=il_m.m_id','left')
                    ->field('il.m_id,m.m_id promoters_mid,m.is_promoters,il.descendants_num,il_m.is_auction')
                    ->find();
                if(!empty($top_member) && $top_member['is_promoters'] == 5){
                    $config = new ConfigService();
                    //说明考核期此粉丝未参拍,粉丝首次参拍再给正式推广员2元间推推荐奖
                    $where = [
                        'c_state'=>0,
                        'c_code'=>'TGY_JT'
                    ];
                    $TGY_JT = $config->configGetValue($where,'c_value');//间推
                    $data_income['i_time'] = time();
                    $data_income['i_typeid'] = 6;
                    $data_income['m_id'] = $top_member['m_id'];
                    $data_income['i_state'] = 8;
                    $data_income['i_money'] = $TGY_JT;
                    $data_income['i_reason'] = '来自账号{'.$m_secret_mobile."}间推会员的首次参拍收益";
                    $data_income['i_from_mid'] = $m_id;
                    $data_income['i_from_id'] = $first_oid;
                    $income_id = Db::table('pai_income')->insertGetId($data_income);
                    if($income_id){
                        // 金钱日志
                        $money_log['ml_type'] = 'add';
                        $money_log['ml_reason'] = "推广员间推会员首次参拍收益";
                        $money_log['ml_table'] = 3;
                        $money_log['ml_reason_id'] = 7;
                        $money_log['ml_money'] = $TGY_JT;
                        $money_log['money_type'] = 2;
                        $money_log['nickname'] = $m_name;
                        $money_log['position'] = $m_levelid;
                        $money_log['member_type'] = 1;
                        $money_log['income_type'] = 2;
                        $money_log['add_time'] = time();
                        $money_log['from_id'] = $m_id;
                        $money_log['m_id'] = $top_member['promoters_mid'];
                        $money_log['state'] = 8;
                        $log_id = Db::table('pai_money_log')->insertGetId($money_log);
                    }
                    Db::table('pai_member')->where('m_id',$top_member['promoters_mid'])->setInc('m_income', $TGY_JT);
                    if(!$income_id){
                        return ['status'=>0,'msg'=>"来自账号{".$m_mobile."}的首次参拍收益添加失败了"];
                    }
                }else if(!empty($top_member) && $top_member['is_promoters'] == 4){
                    $config = new ConfigService();
                    $where3 = [
                        'c_state' => 0,
                        'c_code' => 'TGY_JT'
                    ];
                    $c_value = $config->configGetValue($where3, 'c_value');
                    //插入冻结收益
                    $pa_info = [
                        'm_id' => $top_member['promoters_mid'],
                        'from_id' => $m_id,
                        'p_money' => $c_value,
                        'e_money' => 0,
                        'descendants_num' => 2,
                        'type' => 2,
                        'state' => 1,
                        'add_time' => time(),
                    ];
                    $promoters = new PromotersFrozenService();
                    $promoters->get_add($pa_info);
                }

            }
        }
        return ['status'=>8,'msg'=>"首次参拍收益已产生!", 'data'=>$m_id];
    }


    /**
     * 参拍者数量统计
     * 刘勇豪
     * @param $gp_id
     * @param int $gdr_id
     * @param int $o_periods
     * @return array
     */
    public function count_paier($gp_id,$gdr_id=0,$o_periods=0){
        if(!$gp_id){
            return ['status'=>0,'msg'=>"参数错误"];
        }

        $where['gp_id'] = $gp_id;
        if($gdr_id){
            $where['gdr_id'] = $gdr_id;
        }
        if($o_periods){
            $where['o_periods'] = $o_periods;
        }
        $where['oa_state'] = ['lt',4];//废弃的除外
        $count_awardcode = Db("order_awardcode")->where($where)->count("oa_id");// 已参拍人次

        // 剩余参拍人次
        $where_gdr["gp.gp_id"] = $gp_id;
        if($gdr_id){
            $where_gdr["gdr.gdr_id"] = $gdr_id;
        }
        $gdr_list = Db('goods_dt_record')->alias("gdr")
            ->join('pai_goods_product gp', 'gp.g_id = gdr.g_id', 'left')
            ->where($where_gdr)
            ->select();

        if(empty($gdr_list)){
            return ['status'=>0,'msg'=>"商品折扣不存在"];
        }

        $total_left_num = 0;
        $maxPeriods_str = '';
        foreach($gdr_list as $k=>$v){
            $v_gdr_id = $v['gdr_id'];
            $v_gdr_membernum = $v['gdr_membernum'];
            $maxPeriods = $this->createPeriods($v_gdr_id);

            $where_count['gdr_id'] = $v_gdr_id;
            $where_count['o_periods'] = $maxPeriods['data'];
            $this_count_awardcode = Db("order_awardcode")->where($where_count)->count("*");// 已参拍人次

            $this_left_num = $v_gdr_membernum - $this_count_awardcode;
            if($this_left_num < 0){
                $this_left_num = 0;
            }
            $total_left_num += $this_left_num;
            $maxPeriods_str .= $maxPeriods['data'];
        }

        $data['count_awardcode'] = $count_awardcode;
        $data['total_left_num'] = $total_left_num;


        return ['status'=>8,'msg'=>"success !", 'data'=>$data];
    }

    /**
     * 根据$gp_id查找所有gdr_id
     * 刘勇豪
     * @param $gp_id
     * @return array
     */
    public function get_gdrlist_by_gpid($gp_id){
        $goods_product = Db("goods_product")->where(['gp_id'=>$gp_id])->find();
        if(empty($goods_product)){
            return ['status'=>0, 'msg'=>"拍品不存在"];
        }
        $g_id = $goods_product['g_id'];

        $list = Db("goods_dt_record")->alias("gdr")
            ->join('pai_goods_discounttype gdt', 'gdt.gdt_id = gdr.gdt_id', 'left')
            ->where(['gdr.g_id'=>$g_id])
            ->order('gdr.gdt_id asc')
            ->select();

        if(empty($list)){
            return ['status'=>0, 'msg'=>"当前拍品没有设置参拍折扣", "data"=>$list];
        }
        foreach($list as $k=>$v){
            $gdr_id = $v['gdr_id'];
            $call_reatePeriods = $this->createPeriods($gdr_id);
            if(!$call_reatePeriods['status']){
                return ['status'=>0, 'msg'=>$call_reatePeriods['msg']];
            }
            $list[$k]['createPeriods'] = $call_reatePeriods['data'];
        }

        return ['status'=>1, 'msg'=>"success ！", "data"=>$list];
    }

    /**
     * 分页查询订单详细列表（order_pai,goods_product,goods,store）
     * 创建人 刘勇豪
     * 时间 2018-07-11 16:09:00
     *
     * 备注：o_paystate、o_addtime，gdr_id,o_periods是必须的
     */
    public function get_order_detail_limit_list($where = '', $order = 'o.o_id desc', $field = '*', $limit = "0,5", $cache = 'order_pai')
    {
        $list = Db($this->cache)->alias("o")
            ->field($field)
            ->join('pai_goods_product gp', 'gp.gp_id = o.gp_id', 'left')
            ->join('pai_goods g', 'g.g_id = gp.g_id', 'left')
            ->join('pai_store s', 's.store_id = g.g_storeid', 'left')
            ->where($where)
            ->order($order)
            ->limit($limit)
            ->select();

        // 待支付订单保留时间
        $res = Db("config")->where(['c_code'=>'PO_UNPAID'])->find();
        $max_pay_time = 24;
        if(!empty($res1)){
            $max_pay_time = $res['c_value'];
        }

        if(!empty($list)){
            foreach($list as $k=>$v){
                // 幸运码数量
                $where_count['o_id'] = $v['o_id'];
                $pai_num = $this->countPaiNum($where_count);
                $list[$k]['pai_num'] = $pai_num;

                // 订单是否关闭
                $is_close = 0;

                if($v['o_paystate'] == 1 && $v['o_addtime'] < ( time() - $max_pay_time * 60 * 60 )){
                    $is_close = 1;
                }
                $list[$k]['is_close'] = $is_close;

                //参拍揭晓时间（当期最后下单时间）
                $gdr_id = $v['gdr_id'];
                $o_periods = $v['o_periods'];
                $call_publish = $this->get_pai_publish_time($gdr_id, $o_periods);
                $publish_time = 0;
                if($call_publish['status']){
                    $publish_time = $call_publish['data'];
                }
                $list[$k]['o_publishtime'] = $publish_time;

            }
        }
        return $list;
    }

    /**
     * 统计分页查询订单详细列表总条数
     * 刘勇豪
     * @param string $where
     * @return int|string
     */
    public function get_order_detail_count($where = ''){
        $total = 0;
        $count = Db($this->cache)->alias("o")
            ->join('pai_goods_product gp', 'gp.gp_id = o.gp_id', 'left')
            ->join('pai_goods g', 'g.g_id = gp.g_id', 'left')
            ->join('pai_store s', 's.store_id = g.g_storeid', 'left')
            ->where($where)
            ->count();
        if($count){
            $total = $count;
        }
        return $total;
    }
    /**
     * 卖家分页查询订单详细列表（order_pai,goods_product,goods,store,member）
     * 创建人 刘勇豪
     * 时间 2018-07-11 16:09:00
     *
     * 备注：o_paystate、o_addtime，gdr_id,o_periods是必须的
     */
    public function seller_order_detail_limit_list($where = '', $order = 'o.o_id desc', $field = '*', $limit = "0,5", $cache = 'order_pai')
    {
        $list = Db($this->cache)->alias("o")
            ->field($field)
            ->join('pai_goods_product gp', 'gp.gp_id = o.gp_id', 'left')
            ->join('pai_goods g', 'g.g_id = gp.g_id', 'left')
            ->join('pai_store s', 's.store_id = o.store_id', 'left')
            ->join('pai_member m', 'm.m_id = s.m_id', 'left')
            ->where($where)
            ->order($order)
            ->limit($limit)
            ->select();

        if(!empty($list)){
            foreach($list as $k=>$v){
                // 幸运码数量
                $where_count['o_id'] = $v['o_id'];
                $pai_num = $this->countPaiNum($where_count);
                $list[$k]['pai_num'] = $pai_num;

                //参拍揭晓时间（当期最后下单时间）
                $gdr_id = $v['gdr_id'];
                $o_periods = $v['o_periods'];
                $call_publish = $this->get_pai_publish_time($gdr_id, $o_periods);
                $publish_time = 0;
                if($call_publish['status']){
                    $publish_time = $call_publish['data'];
                }
                $list[$k]['o_publishtime'] = $publish_time;

                // 中奖者信息
                $orderAwardcode = new OrderAwardcodeService();
                $awards_mem_info = $orderAwardcode->get_awards_mem($gdr_id,$o_periods);

                $award_m_avatar = '';//中奖者头像
                $award_m_name = '';//中奖者名称
                $award_m_name_secret = '';//中奖者名称保密
                if(!empty($awards_mem_info) && !empty($awards_mem_info['m_name_secret'])){
                    $award_m_avatar = $awards_mem_info['m_avatar'];//中奖者头像
                    $award_m_name = $awards_mem_info['m_name'];//中奖者名称
                    $award_m_name_secret = $awards_mem_info['m_name_secret'];//中奖者名称保密
                }
                $list[$k]['award_m_avatar'] = $award_m_avatar;
                $list[$k]['award_m_name'] = $award_m_name;
                $list[$k]['award_m_name_secret'] = $award_m_name_secret;
            }
        }
        return $list;
    }
    /**
     * 统计卖家分页查询订单详细列表条数
     * 刘勇豪
     * @param string $where
     * @return int|string
     */
    public function seller_order_detail_count($where = ''){
        $total = 0;
        $count = Db($this->cache)->alias("o")
            ->join('pai_goods_product gp', 'gp.gp_id = o.gp_id', 'left')
            ->join('pai_goods g', 'g.g_id = gp.g_id', 'left')
            ->join('pai_store s', 's.store_id = o.store_id', 'left')
            ->join('pai_member m', 'm.m_id = s.m_id', 'left')
            ->where($where)
            ->count();
        if($count){
            $total = $count;
        }
        return $total;
    }

    /**
     * 我卖出的页面获取数据
     * 刘勇豪
     * @param int $m_id
     * @param int $page
     * @param int $size
     * @param int $i
     * @param string $keyword
     * @return array
     */
    public function get_sell_list_page($m_id=0,$page=1,$size=5,$i=0,$keyword=''){

        if(!$m_id){
            return ['status' => 0, 'msg' => '参数错误', 'data' => ''];
        }
        $where['s.m_id'] = $m_id;
        if($keyword){
            $where['g.g_name'] = ['like','%'.$keyword.'%'];
        }
        //订单状态 0全部 1待发货 2已发货 3待结算 4退款售后
        switch ($i)
        {
            case 0:
                // 全部
                $where['o.o_paystate'] = ['gt','1'];
                $where['o.o_state'] = ['in','2,,3,4,5'];
                break;
            case 1:
                // 待发货
                $where['o.o_paystate'] = ['gt','1'];
                $where['o.o_state'] = 2;
                break;
            case 2:
                // 已发货
                $where['o.o_paystate'] = ['gt','1'];
                $where['o.o_state'] = ['in','3,4'];
                break;
            case 3:
                // 已完成
                $where['o.o_paystate'] = ['gt','1'];
                $where['o.o_state'] = 5;
                break;
//            case 4:
//                // 退款售后
//                $where['o.o_paystate'] = ['in','3,4'];
//                break;
            default:
                // 全部
                $where['o.o_paystate'] = ['gt','1'];
                $where['o.o_state'] = ['in','2,,3,4,5'];
                break;
        }

        $order='o.o_id desc';
        $field = "o.o_id,o.store_id,o.o_money,o.o_paystate,o.o_state,o.gp_id,o.gp_num,o.o_addtime,o.o_paytime,o.o_totalmoney,o.gdr_id,o.o_periods,o.o_isdelete,o.o_gp_settlement_price,gp.gp_market_price,gp.g_id,gp.gp_img,g.g_name,g.g_endtime,g.g_typeid";

        $limit = (($page-1) * $size).','.$size;
        $list = $this->seller_order_detail_limit_list($where,$order,$field,$limit);
        if(empty($list)){
            return ['status' => 0, 'msg' => '没有数据', 'data' => $list];
        }

        foreach($list as $k => $v){
            $g_id = $v['g_id'];
            $min_price = 0;
            $max_price = 0;
            $res_min = Db("goods_dt_record")->where(['g_id'=>$g_id])->order("gdr_price asc")->find();
            $res_max = Db("goods_dt_record")->where(['g_id'=>$g_id])->order("gdr_price desc")->find();
            if(!empty($res_min)){
                $min_price = $res_min['gdr_price'];
            }
            if(!empty($res_max)){
                $max_price = $res_max['gdr_price'];
            }
            $list[$k]['min_price'] = $min_price;
            $list[$k]['max_price'] = $max_price;
        }
        // 统计总条数
        $total_num = $this->get_order_detail_count($where);
        return ['status' => 1, 'msg' => 'success', 'data' => $list,'total_num'=>$total_num];
    }


    /**
     * 确认订单
     * 刘勇豪
     * @param $o_id
     * @param $m_id
     * @param $admin_id
     * @return array
     */
    public function confirm_order($o_id,$m_id,$admin_id=0){
        if(!$o_id && !$m_id){
            return ['status'=>0,'msg'=>'缺少参数'];
        }

        $order_info = Db($this->cache)->where(['o_id'=>$o_id])->find();
        if(empty($order_info)){
            return ['status'=>0,'msg'=>'订单信息不存在！'];
        }

        if($order_info['m_id'] != $m_id && $admin_id !=1){
            return ['status'=>0,'msg'=>'订单用户不匹配！'];
        }


        $member = new MemberService();

        // 退款、转花生的比例参数
        $config = new ConfigService();
        $con_peanut_info = $config->configInfo(['c_code'=>'PEANUT']);
        //$peanut_rate = 0.05;//默认值
        $peanut_rate = 0;//默认值
        if(!empty($con_peanut_info) && !empty($con_peanut_info['c_value'])){
            $peanut_rate = $con_peanut_info['c_value'];
        }

        // 事务
        Db::startTrans();
        try{

            // 1.更新订单状态
            $data['o_state'] = 4;
            if($order_info['o_state'] == 13){
                $data['o_state'] = 14;
            }
            $data['o_dealtime'] = time();
            $data['o_receivetime'] = time();
            $res = Db($this->cache)->where(['o_id'=>$o_id])->update($data);
            if(!$res){
                $o_state = Db($this->cache)->where(['o_id'=>$o_id])->value("o_state");
                if($o_state != 4){
                    throw new \Exception("系统繁忙，请稍后再试！");
                }
            }
            // 执行提交操作
            Db::commit();
            return ['status' => 1, 'msg' => '订单签收成功！'];
        }catch(\Exception $e){
            // 回滚事务
            Db::rollback();
            // 错误日志
            $error_data['el_type_id'] = 1;
            $error_data['el_description'] = $e->getMessage();
            $error_data['m_id'] = $m_id;
            $error_data['el_time'] = time();
            Db::table('pai_error_log')->data($error_data)->insert();

            // 获取提示信息
            return ['status' => 0, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 删除订单
     * 刘勇豪
     * @param $o_id
     * @param $m_id
     * @return array
     */
    public function delete_order($o_id,$m_id){
        if(!$o_id && !$m_id){
            return ['status'=>0,'msg'=>'缺少参数'];
        }

        $order_info = Db($this->cache)->where(['o_id'=>$o_id])->find();
        if(empty($order_info)){
            return ['status'=>0,'msg'=>'订单信息不存在！'];
        }

        if($order_info['m_id'] != $m_id){
            return ['status'=>0,'msg'=>'订单用户不匹配！'];
        }

        $res = Db($this->cache)->where(['o_id'=>$o_id])->update(['o_isdelete'=>3]);
        if(!$res){
            $o_state = Db($this->cache)->where(['o_id'=>$o_id])->value("o_isdelete");
            if($o_state != 3){
                return ['status'=>0,'msg'=>'系统繁忙，请稍后再试！'];
            }
        }
        return ['status'=>1,'msg'=>'删除成功！','data'=>$res];
    }

    /**
     * 取消订单
     * 刘勇豪
     * @param $o_id
     * @param $m_id
     * @return array
     */
    public function cancel_order($o_id,$m_id){
        if(!$o_id && !$m_id){
            return ['status'=>0,'msg'=>'缺少参数'];
        }

        $order_info = Db($this->cache)->where(['o_id'=>$o_id])->find();
        if(empty($order_info)){
            return ['status'=>0,'msg'=>'订单信息不存在！'];
        }

        if($order_info['m_id'] != $m_id){
            return ['status'=>0,'msg'=>'订单用户不匹配！'];
        }

        $res = Db($this->cache)->where(['o_id'=>$o_id])->update(['o_isdelete'=>2]);
        if(!$res){
            $o_state = Db($this->cache)->where(['o_id'=>$o_id])->value("o_isdelete");
            if($o_state != 2){
                return ['status'=>0,'msg'=>'系统繁忙，请稍后再试！'];
            }
        }
        return ['status'=>1,'msg'=>'订单取消成功！','data'=>$res];
    }

    /**
     * 参拍揭晓时间，最后下单时间
     * 刘勇豪
     * @param $gdr_id
     * @param $operiods
     * @return array
     */
    public function get_pai_publish_time($gdr_id,$operiods){
        $publish_time = 0;
        if(!$gdr_id || !$operiods){
            return ['status'=>0,'msg'=>'参数错误！'];
        }

        $where['oa.gdr_id'] = $gdr_id;
        $where['oa.o_periods'] = $operiods;
        //$where['oa.oa_state'] = 2;
        $info = Db("order_awardcode")->alias("oa")
            ->where($where)
            ->join('pai_order_pai o', 'o.o_id = oa.o_id', 'left')
            ->order("oa.oa_id desc")
            ->find();
        if(empty($info)){
            return ['status'=>0,'msg'=>'本期参拍中拍信息还没有产生！'];
        }

        $publish_time = $info['o_paytime'];
        return ['status'=>1,'msg'=>'本期参拍中拍时间已经产生！','data'=>$publish_time];
    }


    /**
     *
     * @param int $o_id
     * @return array
     */
    public function new_logistics_page($o_id=0){

        if(!$o_id){
            return ['status'=>0,'msg'=>'参数错误！'];
        }

        $where["o.o_id"] = $o_id;
        $info = $this->order_detail($where);
        if(empty($info)){
            return ['status'=>0,'msg'=>'没有数据！','data'=>$info];
        }

        return ['status'=>1,'msg'=>'success ！','data'=>$info];
    }

    /**
     * 订单详情
     * 刘勇豪
     * @param $where
     * @param string $field
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function order_detail($where, $field="*"){
        $info = Db("order_pai")->alias("o")
            ->join('pai_goods_product gp', 'o.gp_id = gp.gp_id', 'left')
            ->join('pai_goods g', 'g.g_id = gp.g_id', 'left')
            ->join('pai_goods_dt_record gdr', 'gdr.gdr_id = o.gdr_id', 'left')
            ->join('pai_goods_discounttype gdt', 'gdt.gdt_id = gdr.gdt_id', 'left')
            ->join('pai_store s', 's.store_id = o.store_id', 'left')
            ->where($where)
            ->field($field)
            ->find();
        return $info;
    }

    /**
     * 折扣价详情
     * 刘勇豪
     * @param string $where
     * @param string $field
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function gdt_detail($where='',$field='*'){
        $gdt_info = Db("goods_discounttype")->alias("gdt")
            ->join('pai_goods_dt_record gdr', 'gdr.gdt_id = gdt.gdt_id', 'left')
            ->where($where)
            ->find();
        return $gdt_info;
    }

    /**
     * 根据条件group统计
     * 邓赛赛
     */
    public function get_group_count($where=[],$field='*',$group){
        $list =Db('order_pai')->where($where)
            ->field($field)
            ->group($group)
            ->select();
        return $list;
    }

    /**
     * 买家添加物流信息的表单验证
     * 刘勇豪
     * @param $data
     * @return array
     */
    public function check_logistics($data){
        // 订单id
        if(!isset($data['o_id']) || empty($data['o_id'])){
            return ['status'=>0,'msg'=>"缺少订单信息!"];
        }

        // 物流id
        if(!isset($data['express_corid']) || empty($data['express_corid'])){
            return ['status'=>0,'msg'=>"请填写物流信息!"];
        }

        // 物流信息
        if(!isset($data['express_way']) || empty($data['express_way'])){
            return ['status'=>0,'msg'=>"请填写物流信息!"];
        }

        // 快递单号
        if(!isset($data['express_num']) || empty($data['express_num'])){
            return ['status'=>0,'msg'=>"请填写快递单号!"];
        }

        // 卖家姓名
        if(!isset($data['seller_name']) || empty($data['seller_name'])){
            return ['status'=>0,'msg'=>"请填卖家姓名!"];
        }

        // 联系电话
        if(!isset($data['seller_mobile']) || empty($data['seller_mobile'])){
            return ['status'=>0,'msg'=>"请填联系电话!"];
        }

//        if(!preg_match('/^1[3-9][0-9]{9}$|^0\d{2,3}-?\d{7,8}$/',$data['seller_mobile'])){
//            return ['status'=>0,'msg'=>"联系电话格式错误!"];
//        }

        return ['status'=>1,'msg'=>"ok!"];
    }

    /**
     * 卖家发货（填写快递单）
     * 刘勇豪
     * @param $data
     * @return array
     *
     * 备注：
     * o_id ：订单id （必填字段）
     * express_corid ：快递公司id （必填字段）
     * express_way ：快递公司名称（必填字段）
     * express_num ：快递单号（必填字段）
     * seller_name ：卖家姓名（必填字段）
     * seller_mobile ：卖家联系方式（必填字段）
     * express_img：卖家发货图片（选填）
     */
    public function new_logistics_post($data){

        $call_back = $this->check_logistics($data);
        if(!$call_back['status']){
            return $call_back;
        }

        $o_id = $data['o_id'];
        $express_corid = $data['express_corid'];
        $express_way = trim($data['express_way']);
        $express_num = $data['express_num'];
        $seller_name = $data['seller_name'];
        $seller_mobile = $data['seller_mobile'];
        $express_img = empty($data['express_img'])?[]:$data['express_img'];

        //订单信息
        $order_info = Db("order_pai")->where(['o_id'=>$o_id])->find();
        if(empty($order_info)){
            return ['status'=>0,'msg'=>"订单不存在!",'data'=>''];
        }

        $new_data = [];
        $new_data['o_state'] = 3;//已发货
        if($order_info['o_state'] == 12){
            $new_data['o_state'] = 13;//已发货
        }
        $new_data['o_express_corid'] = $express_corid;//快递公司ID
        $new_data['o_express_way'] = $express_way;//快递名称
        $new_data['o_express_num'] = $express_num;//快递单号
        $new_data['o_seller_name'] = $seller_name;//卖家姓名
        $new_data['o_seller_mobile'] = $seller_mobile;//卖家联系方式
        $new_data['o_delivertime'] = time();//卖家发货时间
        $res1 = Db("order_pai")->where(['o_id'=>$o_id])->update($new_data);
        if(!$res1){
            $o_state = Db("order_pai")->where(['o_id'=>$o_id])->value('o_state');
            if(!$o_state || $o_state != 3){
                return ['status'=>0,'msg'=>"系统繁忙，请稍后重试!",'data'=>''];
            }
        }

        if(!empty($data['express_img']) && is_array($data['express_img']) ){
            $data['express_img'] = array_values(array_filter($data['express_img']));
            $imgs = $this->orderPai->ba64_img($data['express_img'],'express_img',300,300);
        }else{
            $imgs[0]='';
        }
        if(!empty($imgs[0])){
            foreach($imgs as $k =>$v){
                $data_img[$k]['oli_oid'] = $o_id;
                $data_img[$k]['oli_img'] = $v;
            }
            $add = Db("order_logistics_img")->insertAll($data_img);
            if(!$add){
                return ['status'=>0,'msg'=>"系统繁忙，图片保存不成功!",'data'=>''];
            }
        }
        return ['status'=>1,'msg'=>"物流信息填写成功！",'data'=>$new_data];
    }

    /**
     * 非普通商品直接发货成功
     * 刘勇豪
     * @param int $o_id
     * @param int $m_id
     * @return array
     */
    public function send_out($o_id=0,$m_id=0){
        if(!$o_id || !$m_id){
            return ['status'=>0,'msg'=>"参数错误!"];
        }

        $order_info = Db("order_pai")->where(['o_id'=>$o_id])->find();
        if(empty($order_info)){
            return ['status'=>0,'msg'=>"订单不存在!"];
        }

        $o_state = $order_info['o_state'];//订单状态
        $gs_id = $order_info['gs_id'];//商品属性
        $o_m_id = $order_info['m_id'];//订单所属

        if($gs_id == 1){
            return ['status'=>0,'msg'=>"普通物品是需要填写物流信息的!"];
        }

        if($o_state < 2 || $o_state > 3){
            return ['status'=>0,'msg'=>"订单状态有误!"];
        }
        if($o_state == 3){
            return ['status'=>1,'msg'=>"订单已发货!",'data'=>$o_state];
        }else{
            $res = Db("order_pai")->where(['o_id'=>$o_id])->update(["o_state"=>3,"o_delivertime"=>time()]);
            if(!$res){
                return ['status'=>0,'msg'=>"数据库繁忙，订单装填更新失败！!"];
            }

            return ['status'=>1,'msg'=>"订单发货成功！",'data'=>3];
        }

    }

    /**
     * 订单物流信息
     * 刘勇豪
     * @param string $where
     * @param string $field
     * @return array
     */
    public function order_logistics_info($where = '', $field = ''){
        $info = Db("order_pai")->where($where)->find();
        if(empty($info)){
            return ['status'=>0,'msg'=>"订单信息不存在!",'data'=>''];
        }

        $info['data_img'] = [];
        $img_info = Db("order_logistics_img")->where(['oli_oid'=>$info['o_id']])->limit('4')->select();
        $info['data_img'] = $img_info;
        return ['status'=>1,'msg'=>"ok!",'data'=>$info];
    }

    /**判断订单是否是当天第3次（动态配置）或联系7天（动态配置）之后的订单
     * 刘勇豪
     * @param int $o_id
     * @return array
     */
    public function is_notip($o_id=0){
        if(!$o_id){
            return ['status'=>0,'msg'=>"参数错误"];
        }
        $order_info = Db("order_pai")->where(['o_id'=>$o_id])->find();
        if(empty($order_info)){
            return ['status'=>0,'msg'=>"订单信息不存在"];
        }
        $o_paystate = $order_info['o_paystate'];
        $o_paytime = $order_info['o_paytime'];
        $m_id = $order_info['m_id'];

        if($o_paystate < 2){
            return ['status'=>0,'msg'=>"订单未支付，不能参与计算！"];
        }

        $day = date("Y-m-d",$o_paytime);
        $start_time = strtotime($day);
        $end_time = $start_time + 24 * 60 * 60 -1;

        // 当天连续几次参拍后的参拍不收手续费（次）
        $no_tip_time = 3;//默认值
        $db_no_tid_time = Db("config")->where(["c_code"=>"NO_TIP_TIME"])->value("c_value");
        if($db_no_tid_time){
            $no_tip_time = $db_no_tid_time;
        }
        $where_count['o_paystate'] = ['gt',1];
        $where_count['o_paytime'] = ['between',$start_time.",".$end_time];
        $where_count['m_id'] = $m_id;
        $db_count = Db("order_pai")->where($where_count)->count();

        $today_pai_num = 0;//设置默认值
        if($db_count){
            $today_pai_num = $db_count;
        }

        $is_no_tip = 1;

        if($today_pai_num > $no_tip_time){
            $is_no_tip = 2;
        }else{
            // 统计是否是连续7天之后下的订单
            $no_tip_days = 7;
            $db_no_tid_days = Db("config")->where(["c_code"=>"NO_TIP_DAYS"])->value("c_value");
            if($db_no_tid_days){
                $no_tip_days = $db_no_tid_days;
            }
            $call_back = $this->is_every_day($m_id, $o_paytime, $no_tip_days);
            if(!$call_back['status']){
                return $call_back;
            }
            $is_no_tip = $call_back['data'];
        }

        return ['status'=>1,'msg'=>"ok", 'data'=>$is_no_tip];

    }

    /**
     * 统计是否是连续7天之后下的订单
     * 刘勇豪
     * @param int $m_id
     * @param int $o_paytime
     * @param int $no_tip_days
     * @return array
     */
    public function is_every_day($m_id=0, $o_paytime=0, $no_tip_days=7){
        if(!$m_id || !$o_paytime){
            return ['status'=>0,'msg'=>'参数错误'];
        }

        $is_every_day = 2;//默认是每天都下单了
        $o_paytime = $o_paytime - 86400;


        $day = date("Y-m-d",$o_paytime);
        $start_time = strtotime($day);
        $end_time = $start_time + 24 * 60 * 60 -1;

        $where_count['o_paystate'] = ['gt',1];
        $where_count['o_paytime'] = ['between',$start_time.",".$end_time];
        $where_count['m_id'] = $m_id;
        $find = Db("order_pai")->where($where_count)->find();
        if(empty($find)){
            $is_every_day = 1;
            return ['status'=>1,'msg'=>'没有连续下单','data'=>$is_every_day];
        }

        if($no_tip_days > 1){
            $no_tip_days--;
            return $this->is_every_day($m_id,$o_paytime,$no_tip_days);
        }
        return ['status'=>1,'msg'=>'连续下单','data'=>$is_every_day];
    }

    /**
     * 判断拍品是不是自己发布的
     * 刘勇豪
     * @param int $gp_id
     * @param int $m_id
     * @return array
     */
    public function is_my_goods($gp_id=0,$m_id=0){
        if(!$gp_id){
            return ['status'=>0,'msg'=>'参数错误！'];
        }

        $where['gp.gp_id'] = $gp_id;
        $gp_info = Db("goods_product")->alias("gp")
            ->join('pai_goods g', 'g.g_id = gp.g_id', 'left')
            ->where($where)
            ->find();

        if(empty($gp_info)){
            return ['status'=>0,'msg'=>'拍品不存在！'];
        }

        $g_mid = $gp_info['g_mid'];

        if($g_mid == $m_id){
            return ['status'=>0,'msg'=>'您不能参拍您自己发布的商品哦~'];
        }

        return ['status'=>1,'msg'=>'这是别人发布的商品，可以购买！~'];
    }

    /**
     * 是否免手续费详情
     * 刘勇豪
     * @param $m_id
     * @return array
     */
    public function tip_detail($m_id){
        if(!$m_id){
            return ['status'=>0,'msg'=>'参数错误！'];
        }

        // 1.获取配置参数
        // 1.1当天连续几次参拍后的参拍不收手续费（次）
        $no_tip_time = 3;//默认值
        $db_no_tid_time = Db("config")->where(["c_code"=>"NO_TIP_TIME"])->value("c_value");
        if($db_no_tid_time){
            $no_tip_time = $db_no_tid_time;
        }
        // 1.2统计是否是连续7天之后下的订单
        $no_tip_days = 7;
        $db_no_tid_days = Db("config")->where(["c_code"=>"NO_TIP_DAYS"])->value("c_value");
        if($db_no_tid_days){
            $no_tip_days = $db_no_tid_days;
        }


        // 今天参拍多少单

        $day = date("Y-m-d",time());
        $start_time = strtotime($day);
        $end_time = $start_time + 24 * 60 * 60 -1;

        //今日参拍
        $where_count['o_paystate'] = ['gt',1];
        $where_count['o_paytime'] = ['between',$start_time.",".$end_time];
        $where_count['m_id'] = $m_id;
        $today_pai_num = Db("order_pai")->where($where_count)->count();

        //已累计参拍天数
        $call_back = $this->pai_days($m_id,time());
        if(!$call_back['status']){
            return $call_back;
        }

        $total_days = $call_back['data'];

        $back = [];
        $back['no_tip_time'] = $no_tip_time;
        $back['no_tip_days'] = $no_tip_days;
        $back['today_pai_num'] = $today_pai_num;
        $back['total_days'] = $total_days;

        return ['status'=>1,'msg'=>'success！','data'=>$back];

    }

    /**
     * 统计参拍天数
     * 刘勇豪
     * @param int $m_id
     * @param int $today_time
     * @param int $total_days
     * @param int $max_days
     * @return array
     */
    public function pai_days($m_id=0,$today_time = 0,$total_days=0,$max_days=100){
        if(!$m_id || !$today_time){
            return ['status'=>0,'msg'=>'参数错误！'];
        }

        $day = date("Y-m-d",($today_time - 24 * 60 * 60));
        $start_time = strtotime($day);
        $end_time = $start_time + 24 * 60 * 60 -1;

        $where_count['o_paystate'] = ['gt',1];
        $where_count['o_paytime'] = ['between',$start_time.",".$end_time];
        $where_count['m_id'] = $m_id;
        $find = Db("order_pai")->where($where_count)->find();

        if(empty($find) || $max_days < 1){
            return ['status'=>1,'msg'=>'ok！','data'=>$total_days];
        }else{
            $today_time = $today_time - 24 * 60 * 60;
            $total_days++;
            $max_days--;
            return $this->pai_days($m_id,$today_time,$total_days,100);
        }
    }

    /**
     * 我的消费金额总计
     * 刘勇豪
     * @param int $m_id
     * @return float|int|string
     */
    public function get_total_pay($m_id = 0){
        $where['m_id'] = $m_id;
        $where['o_paystate'] = ['gt',1];
        $total_o_totalmoney = Db("order_pai")->where($where)->sum("o_totalmoney");
        $total_o_totalmoney = $total_o_totalmoney?$total_o_totalmoney:0;

        //人气拍总支付
        $where = [];
        $where['m_id'] = $m_id;
        $where['pm_paystate'] = ['gt',1];
        $total_pop_pay = Db("popularity_member")->where($where)->sum("pm_paymoney");
        $total_pop_pay = $total_pop_pay?$total_pop_pay:0;

        $total_pay = $total_o_totalmoney + $total_pop_pay;
        $total_pay = sprintf("%.2f",$total_pay);

        return $total_pay;
    }

    /**
     * 订单流拍处理
     * 刘勇豪
     * @param int $gp_id
     * @return array
     */
    public function if_no_stock($gp_id=0){
        $where['gp.gp_id'] = $gp_id;
        $gp_info = Db("goods_product")->alias("gp")
            ->field("gp.gp_stock,gp.g_id,g.g_state,g.g_name,g.g_img,gp.is_fudai")
            ->join('pai_goods g', 'g.g_id = gp.g_id', 'left')
            ->where($where)
            ->find();
        if(empty($gp_info)){
            return ['status'=>0,'msg'=>'商品信息不见了！'];
        }

        $gp_stock = $gp_info['gp_stock'];
        $g_state = $gp_info['g_state'];
        $g_id = $gp_info['g_id'];
        $g_name = $gp_info['g_name'];
        $g_img = $gp_info['g_img'];
        $is_fudai = $gp_info['is_fudai'];// 1是福袋 2不是福袋
        if($gp_stock < 1){

            if($g_state != 9){
                $where = '';
                $where['g_id'] = $g_id;
                $res = Db("goods")->where($where)->update(['g_state'=>9]);
                if(!$res){
                    return ['status'=>0,'msg'=>'数据库繁忙！商品状态更新失败！'];
                }

                $where = '';
                $where['gp_id'] = $gp_id;
                $res = Db("goods_product")->where($where)->update(['gp_state'=>3]);
                if(!$res){
                    return ['status'=>0,'msg'=>'数据库繁忙！商品状态更新失败2！'];
                }
            }

            // 成团的订单退款
            $where = '';
            $where['o.gp_id'] = $gp_id;
            $where['o.o_paystate'] = 2;
            $where['o.o_state'] = 1;
            $list = Db("order_pai")->alias("o")
                ->join('pai_member m', 'm.m_id = o.m_id', 'left')
                ->where($where)
                ->select();
            if(!empty($list)){
                foreach($list as $v){
                    $o_id = $v['o_id'];
                    $m_id = $v['m_id'];
                    $o_sn = $v['o_sn'];
                    $o_totalmoney = $v['o_totalmoney'];
                    $o_periods = $v['o_periods'];
                    $m_name = $v['m_name'];
                    $m_levelid = $v['m_levelid'];

                    $refund['refund_time'] = time();
                    $refund['m_id'] = $m_id;
                    $refund['refund_money'] = $o_totalmoney;
                    $refund['refund_success_time'] = time();
                    $refund['refund_state'] = 8;
                    $refund['refund_fromid'] = $o_id;
                    $refund['refund_reason'] = '订单流拍退款！';
                    $refund_id = Db("refund")->insertGetId($refund);

                    // 2.生成money_log日志
                    if($refund_id){
                        $money_log['ml_type'] = 'add';
                        $money_log['ml_reason'] = "订单流拍自动退款";
                        $money_log['ml_table'] = 4;
                        $money_log['ml_money'] = $o_totalmoney;
                        $money_log['money_type'] = 1;
                        $money_log['nickname'] = $m_name;
                        $money_log['position'] = $m_levelid;
                        $money_log['add_time'] = time();
                        $money_log['from_id'] = $refund_id;
                        $money_log['m_id'] = $m_id;
                        $log_id = Db("money_log")->insertGetId($money_log);
                    }

                    //未中拍信息
                    $systemMsg = new SystemMsgService();
                    $msg_data['sm_addtime'] = time();//系统消息添加时间
                    $msg_data['sm_display'] = 2;//2中拍消息
                    $msg_data['sm_title'] = "参拍结果信息";//消息标题
                    $msg_data['sm_brief'] = "好遗憾！您的参拍订单号为：".$o_sn."的订单没有成团！";//消息简介
                    $msg_data['sm_content'] = "很遗憾！您在商品：'".$g_name."'的折拍活动中的第'".$o_periods."'期参拍中因商品售罄，该期订单不能成团未能参与抽奖，您的参拍费用已退还给您！";//消息内容
                    $msg_data['sm_img'] = $g_img;//图片(商品等简介图片)
                    $msg_data['g_id'] = $g_id;//商品ID
                    $msg_data['shop_price'] = $o_totalmoney;
                    $msg_data['o_id'] = $o_id;//订单ID
                    $msg_data['to_mid'] = $m_id;//收消息人ID
                    $res = $systemMsg->add_msg($msg_data);

                    // 订单状态
                    $res3 = Db('order_pai')->where(['o_id'=>$o_id])->update(['o_state'=>11,'o_paystate'=>4]);
                    if(!$res3){
                        return ['status'=>0,'msg'=>'数据库繁忙！'];
                    }
                }
            }

            // 如果是福袋商品，要把下一个待上架的商品上线
            if($is_fudai == 1){
                $call_back = $this->sale_next_fudai();
                if(!$call_back['status']){
                    return $call_back;
                }
            }
            return ['status'=>1,'msg'=>'流拍订单已处理！'];
        }
        return ['status'=>1,'msg'=>'商品正常参拍中！'];
    }
    /**
     * 中拍者信息
     * 邓赛赛
     */
    public function order_suc($where=[],$order='o_id asc',$field="*",$page=1,$page_size=15){
        $offset = $page < 1 ? 1 : 0;
        $list['list'] = Db::table('pai_order_pai op')
            ->where($where)
            ->join('pai_member m','op.m_id=m.m_id','left')
            ->join('pai_review_order ro','ro.order_id=op.o_id','left')
            ->join('pai_review_goods rg','ro.ro_id=rg.ro_id','left')
            ->join('pai_goods_dt_record gdr','op.gdr_id=gdr.gdr_id','left')
            ->join('pai_goods_discounttype gdt','gdr.gdt_id=gdt.gdt_id','left')
            ->order($order)
            ->field($field)
            ->limit($offset,$page_size)
            ->select();
        if(!empty($list['list'])){
            $order_pai = new OrderPaiService();
            foreach($list['list'] as $k => $v){
                //开奖时间
                $luck_time= $order_pai->get_luck_time($v['gp_id'],$v['gdr_id'],$v['o_periods']);
                if($luck_time['status'] == 1){
                    $list['list'][$k]['luck_time'] = $luck_time['data'];
                }else{
                    $list['list'][$k]['luck_time'] = '';
                }
                //评论内容图片
                $list['list'][$k]['img_list'] = array();
                if($v['rg_id']){
                    $rg_id = $v['rg_id'];
                    $img_list = [];
                    if($rg_id){
                        $img_list = Db::table("pai_review_goods_imgs")->where(['rg_id'=>$rg_id])->select();
                    }
                    $list['list'][$k]['img_list'] = $img_list;
                }
            }
        }
        //总中拍人数
        $list['total_num'] =  Db::table('pai_order_pai op')->where($where)->count();
        return $list;
    }
    /**
     * 退款详情
     * 刘勇豪
     * @param int $o_id
     * @param int $m_id
     * @return array
     */
    public function refund_info($o_id=0,$m_id=0){

        if(!$o_id || !$m_id){
            return ['status'=>0,'msg'=>'参数错误！','data'=>''];
        }

        $order_info = Db("order_pai")->where(['o_id'=>$o_id])->field("o_id,o_sn,m_id,store_id,o_money,o_paystate,o_state,o_periods,o_totalmoney,gp_id,gdr_id,o_deliverfee,o_is_no_tip")->find();
        if(empty($order_info)){
            return ['status'=>0,'msg'=>'订单信息不存在！','data'=>''];
        }

        if($order_info['m_id'] != $m_id){
            return ['status'=>0,'msg'=>'拒绝访问，不是你的订单！','data'=>''];
        }

        // 退款、转花生的比例参数
        $config = new ConfigService();
        $con_peanut_info = $config->configInfo(['c_code'=>'PEANUT']);
        $peanut_rate = 0.05;//默认值
        if(!empty($con_peanut_info) && !empty($con_peanut_info['c_value'])){
            $peanut_rate = $con_peanut_info['c_value'];
        }

        // 退款发起时间
        $callback = $this->get_luck_time($order_info['gp_id'],$order_info['gdr_id'],$order_info['o_periods']);
        $refund_start_time = $callback['data'];
        $order_info['refund_start_time'] = $refund_start_time;

        // 退款完成时间
        $callback = $this->get_dealtime($order_info['gp_id'],$order_info['gdr_id'],$order_info['o_periods']);
        $refund_end_time = $callback['data'];
        $order_info['refund_end_time'] = $refund_end_time;

        // 退款金额
        if($order_info['o_is_no_tip'] > 1){
            $peanut_rate = 0;
        }

        if($order_info['o_state'] == 11){
            $refund_money = $order_info['o_totalmoney'];
        }elseif($order_info['o_state'] == 10){
            $refund_money = round($order_info['o_totalmoney'] * (1 - $peanut_rate),2);
        }else{
            $refund_money = round(($order_info['o_totalmoney'] - $order_info['o_money'] - $order_info['o_deliverfee']) * (1 - $peanut_rate),2);
        }
        $order_info['refund_money'] = $refund_money;

        return ['status'=>1,'msg'=>'','data'=>$order_info];
    }

    /**
     * 获取抽奖时间（公布时间，未中奖退款发起时间）
     * 刘勇豪
     * @param int $gp_id
     * @param int $gdr_id
     * @param int $o_periods
     * @return array
     */
    public function get_luck_time($gp_id=0,$gdr_id=0,$o_periods=0){
        if(!$gp_id || !$gdr_id || !$o_periods){
            return ['status'=>0,'msg'=>'参数错误','data'=>0];
        }

        $where['gp_id'] = $gp_id;
        $where['gdr_id'] = $gdr_id;
        $where['o_periods'] = $o_periods;
        $where['o_paystate'] = ['gt',1];
        $res = Db("order_pai")->where($where)->order("o_paytime desc")->find();
        $luck_time = 0;
        if(!empty($res)){
            if($res['o_publishtime']){
                $luck_time = $res['o_publishtime'];
            }else{
                $luck_time = $res['o_paytime'];
            }
        }

        return ['status'=>1,'msg'=>'','data'=>$luck_time];
    }
    /**
     * 获取退款完成时间、中奖者签收时间
     * 刘勇豪
     * @param int $gp_id
     * @param int $gdr_id
     * @param int $o_periods
     * @return array
     */
    public function get_dealtime($gp_id=0,$gdr_id=0,$o_periods=0){
        $dealtime = 0;
        $where['gp_id'] = $gp_id;
        $where['gdr_id'] = $gdr_id;
        $where['o_periods'] = $o_periods;
        $where['o_paystate'] = ['between','4,5'];
        $res = Db("order_pai")->where($where)->find();
        if(!empty($res) && $res['o_dealtime']){
            $dealtime = $res['o_dealtime'];
        }
        return ['status'=>1,'msg'=>'','data'=>$dealtime];
    }
    /**
     * 获取用户所有邀请的参拍或未参拍人数
     * 邓赛赛
     */
    public function get_participate_num($m_id){
        $where = [
            'm.m_id'=>$m_id,
            'm.m_state'=>0,
            'son_m.m_id'=>['<>','null'],
            'son_m.m_state'=>0,
            'son_m.is_auction'=>1,
        ];
        $auction = Db::table('pai_member m')
            ->where($where)
            ->join('pai_member son_m','m.m_id = son_m.tj_mid','left')
            ->count();
        $where = [
            'tj_mid'=>$m_id,
            'm_state'=>0,
        ];
        //总人数
        $total_num = Db::table('pai_member')->where($where)->count();
        //参拍人数
        $people_num[0] = $auction;
        //未参拍人数
        $people_num[1] = $total_num - $auction;
        return $people_num;
    }

    /**
     * 获取毫秒时间戳
     * 刘勇豪
     * @return float
     */
    function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 积分拍支付成功就回调（返花生）
     * 刘勇豪
     * @param int $gp_id
     * @return array
     */
    public function pai_pay_callback($gp_id=0){

        //查找所有退款中的订单退款
        $where = '';
        $where['o_paystate'] = 3;//退款中
        if($gp_id){
            $where['gp_id'] = $gp_id;//指定商品
        }

        $list = Db("order_pai")->where($where)->select();
        $member = new MemberService();

        // 退款、转花生的比例参数
        $config = new ConfigService();
        $con_peanut_info = $config->configInfo(['c_code'=>'PEANUT']);
        $peanut_rate = 0;//默认值
        if(!empty($con_peanut_info) && !empty($con_peanut_info['c_value'])){
            $peanut_rate = $con_peanut_info['c_value'];
        }

        $num = 0;
        if(!empty($list)){
            foreach($list as $v){
                $o_id = $v['o_id'];
                $m_id = $v['m_id'];
                $o_sn = $v['o_sn'];
                $o_money = $v['o_money'];
                $gp_num = $v['gp_num'];
                $o_totalmoney = $v['o_totalmoney'];
                $o_money = $v['o_money'];
                $o_deliverfee = $v['o_deliverfee'];
                $o_state = $v['o_state'];
                $o_is_no_tip = $v['o_is_no_tip'];
                if($o_is_no_tip == 2){
                    $peanut_rate = 0;
                }

                // 1.退款金额
                $refund_momey = 0;
                if($o_state == 10){
                    //未中奖
                    $refund_momey = $o_totalmoney;
                }elseif($o_state < 10 && $o_state > 1){
                    // 中奖
                    $refund_momey = $o_totalmoney - $o_money - $o_deliverfee;
                }

                // 退款者信息
                $mem_info = $member->get_info(['m_id' => $m_id]);

                if($refund_momey > 0 && !empty($mem_info)){

                    // 事务
                    Db::startTrans();
                    try{
                        $m_name = $mem_info['m_name'];
                        $m_levelid = $mem_info['m_levelid'];

                        // 1.退款日志
                        $refund_momey = round($refund_momey*(1-$peanut_rate),2);//订单退款
                        $refund_peanut = round($refund_momey*$peanut_rate,2);// 订单的手续费（转换为花生）

                        $refund['refund_time'] = time();
                        $refund['m_id'] = $m_id;
                        $refund['refund_money'] = $refund_momey;
                        $refund['refund_success_time'] = time();
                        $refund['refund_state'] = 8;
                        $refund['refund_fromid'] = $o_id;
                        $refund['refund_reason'] = '未中拍退款！';
                        $refund_id = Db("refund")->insertGetId($refund);

                        // 2.生成money_log日志
                        if($refund_id){
                            $money_log['ml_type'] = 'add';
                            $money_log['ml_reason'] = "未拍中自动退款";
                            $money_log['ml_table'] = 4;
                            $money_log['ml_money'] = $refund_momey;
                            $money_log['money_type'] = 1;
                            $money_log['nickname'] = $m_name;
                            $money_log['position'] = $m_levelid;
                            $money_log['add_time'] = time();
                            $money_log['from_id'] = $refund_id;
                            $money_log['m_id'] = $m_id;
                            $log_id = Db("money_log")->insertGetId($money_log);
                        }

                        // 3.返款到用户表
                        Db("member")->where(['m_id'=>$m_id])->setInc('m_money',$refund_momey);

                        // 4.返还花生
                        if($refund_peanut > 0){
                            $peanut_log['pl_num'] = $refund_peanut;
                            $peanut_log['pl_time'] = time();
                            $peanut_log['from_id'] = $o_id;
                            $peanut_log['pl_type'] = 'add';
                            $peanut_log['pl_state'] = 8;
                            $peanut_log['pl_mid'] = $m_id;
                            $peanut_log['pl_reason'] = "未订单返回";
                            $plog_id = Db("peanut_log")->insertGetId($peanut_log);

                            // 5.返花生到用户表
                            Db("member")->where(['m_id'=>$m_id])->setInc('peanut',$refund_peanut);
                        }

                        // 6.更新用户订单和中拍码状态
                        Db("order_pai")->where(['o_id'=>$o_id])->update(['o_paystate'=>4]);

                        // 执行提交操作
                        Db::commit();
                        $num++;
                        // return ['status' => 1, 'msg' => '订单签收成功！'];
                    }catch(\Exception $e){
                        // 回滚事务
                        Db::rollback();
                        // 错误日志
                        $error_data['el_type_id'] = 1;
                        $error_data['el_description'] = $e->getMessage();
                        $error_data['m_id'] = $m_id;
                        $error_data['el_time'] = time();
                        Db::table('pai_error_log')->data($error_data)->insert();

                        // 获取提示信息
                        continue;
                    }
                }
            }
        }

        $back = '';
        $back['num'] = $num;
        $back['gp_id'] = $gp_id;
        return ['status'=>8,'msg'=>"success !", 'data'=>$back];
    }

    /**
     * 判断和销售下一个福袋商品上架
     * 刘勇豪
     * @return array
     */
    public function sale_next_fudai(){

        $day_start = date("Y-m-d",time())." 00:00:00";
        $day_end = date("Y-m-d",time())." 23:59:59";
        $time_start = strtotime($day_start);
        $time_end = strtotime($day_end);

        $where['g.g_state'] = 6;// 竞拍中
        $where['gp.gp_state'] = 1;// 1上架 2预上架  3下架
        $where['gp.is_fudai'] = 1;//福袋商品
        $where['g.g_starttime'] = ['elt',$time_end];
        $where['g.g_endtime'] = ['egt',$time_start];

        $find = Db("goods")->alias("g")
            ->join('pai_goods_product gp','gp.g_id = g.g_id','left')
            ->where($where)
            ->find();

        if(empty($find)){
            // 选择下一个
            $where['g.g_state'] = 6;// 竞拍中
            $where['gp.gp_state'] = 2;// 1上架 2预上架  3下架
            $where['gp.sort'] = ['gt',0];
            $where['gp.is_fudai'] = 1;//福袋商品
            $where['g.g_starttime'] = ['elt',$time_end];
            $where['g.g_endtime'] = ['egt',$time_start];

            $order = 'gp.sort asc';// 排序

            $next_find = Db("goods")->alias("g")
                ->join('pai_goods_product gp','gp.g_id = g.g_id','left')
                ->where($where)
                ->order($order)
                ->find();
            if(!empty($next_find)){
                $res = Db("goods_product")->where(['gp_id'=>$next_find['gp_id']])->update(['gp_state'=>1]);

                if(!$res){
                    return ['status'=>0,'msg'=>"update error !", 'data'=>''];
                }
            }
            return ['status'=>8,'msg'=>"success !", 'data'=>''];
        }
        return ['status'=>1,'msg'=>"No need to update !", 'data'=>''];
    }

    /**
     * 拍品进度
     * 刘勇豪
     * @param int $g_id
     * @return array
     */
    public function pai_progress($g_id=0){
        if(!$g_id){
            return ['status'=>0,'msg'=>"缺少参数 !", 'data'=>''];
        }

        $gp_info = Db("goods_product")->where(['g_id'=>$g_id])->find();
        if(empty($gp_info)){
            return ['status'=>0,'msg'=>"商品不存在 !", 'data'=>''];
        }
        $gp_id = $gp_info['gp_id'];

        $gdr_lists = Db("goods_dt_record")->where(['g_id'=>$g_id])->select();
        if(!empty($gdr_lists)){
            foreach($gdr_lists as $kk=>$vv){
                $gdr_id = $vv['gdr_id'];
                $gdr_membernum = $vv['gdr_membernum'];
                $call_create = $this->createPeriods($gdr_id);
                if(!$call_create['status']){
                    return $call_create['msg'];
                }
                $maxPeriods = $call_create['data'];//当前第几期在团
                $where_award['gdr_id'] = $gdr_id;
                $where_award['o_periods'] = $maxPeriods;
                $pai_num = $this->countPaiNum($where_award);// 已团数量
                $left_num = $gdr_membernum - $pai_num;
                $gdr_lists[$kk]['left_num'] = $left_num;
                $gdr_lists[$kk]['pai_periods'] = $maxPeriods;
                $gdr_lists[$kk]['gp_num'] = $pai_num;
                $gdr_lists[$kk]['left_num'] = $left_num;
                //参团率
                $proportion = $pai_num/$gdr_membernum*100;
                if($proportion < 99){
                    $gdr_lists[$kk]['proportion'] = ceil($proportion);
                }else{
                    $gdr_lists[$kk]['proportion'] = floor($proportion);
                }
            }
        }

        // 统计此商品总共参拍
        $where = '';
        $where['gp_id'] = $gp_id;
        $total_pai_num = $this->countPaiNum($where);// 已团数量

        $data = '';
        $data['gdr_lists'] = $gdr_lists;
        $data['total_pai_num'] = $total_pai_num;

        return ['status'=>8,'msg'=>"success !", 'data'=>$data];

    }

}