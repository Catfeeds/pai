<?php
namespace app\pointpai\controller;

use think\Config;
use think\Controller;
use think\Cookie;
use think\Db;
use app\data\service\pointOrderPai\PointOrderPaiService;
use app\data\service\pointOrderAwardcode\PointOrderAwardcodeService;
use app\data\service\address\AddressService;
use app\data\service\pointGoods\PointGoodsProductService;
use app\data\service\member\MemberService;
use app\data\service\popularity\PopularityGoodsService;
use app\data\service\pointGoods\PointGoodsService;

class Pointorder extends IndexHome
{

    /*
    * 订单详情
    * 创建人 刘勇豪
    * @return mixed
    */
    public function index()
    {
        $o_id = input('param.o_id/d', 0);
        $j_type = input('param.j_type', 0);
        if (!$o_id) {
            return "参数错误！";
        }

        $pointOrderPai = new PointorderPaiService();
        $callback = $pointOrderPai->order_info_page($o_id);
        if (!$callback['status']) {
            return $callback['msg'];
        }
        $info = $callback['data'];
        $gp_id = $info['gp_id'];
        $o_periods = $info['o_periods'];
        $g_id = $info['g_id'];

        // 参团进度
        $rate = $pointOrderPai->get_orderpai_rate($gp_id, $o_periods);

        // 查询团中信息
        $pointOrderAwardcode = new PointOrderAwardcodeService();

        // 本期团中者信息
        $awardinfo = $pointOrderAwardcode->get_awards_mem($gp_id, $o_periods);

        // 我的幸运码
        $where['po.o_id'] = $info['o_id'];
        $myawards = $pointOrderAwardcode->getPointOrderAwards($where);

        // 订单退花生数量
        $refund_peanut = $pointOrderPai->get_refund_peanut($o_id);

        // 用户信息
        $member = new MemberService();
        $m_id = $this->m_id;
        $mem_info = $member->get_info(['m_id' => $m_id]);
        $syumem_info = $member->get_syumem_info(['m_id' => $m_id]);

        //分享二维码
        // 获取二维码( 问赛赛 TODO...
        $p_goods = new PopularityGoodsService();
        $mid = 2;
        $path_3 = '/uploads/code/pointpai/shop/'.$mid.'code_'.$g_id.".jpg";
        $info['code']  = $p_goods->hebingImg('/uploads/logo/father.png',$info['g_img'],$path_3,$info['g_name'],$info['o_point'],$mid,$g_id);
        $info['url']   = PAI_URL."/pointpai/Pointgoods/index/g_id/".$g_id.'?share=1';

        // 微信分享
        //微信分享参数
        $info['share_title'] = '拍吖吖：只要'.$info['o_point'].'积分，你敢信？快跟我一起来抢购吧！';
        $info['share_desc'] =$info['g_name'];
        $info['share_link'] = PAI_URL.'/pointpai/Pointgoods/index/g_id/'.$info['g_id'].'?share=1';
        $info['share_imgUrl'] = PAI_URL.$info['gp_img'];

        $this->assign('info', $info);
        $this->assign('rate', $rate);
        $this->assign('awardinfo', $awardinfo);
        $this->assign('myawards', $myawards);
        $this->assign('mem_info', $mem_info);
        $this->assign('syumem_info', $syumem_info);
        $this->assign('header_title', "订单详情");
        $this->assign('refund_peanut', $refund_peanut);
        return $this->fetch();
    }

    /*
    * 确认参团订单
    * 创建人 刘勇豪
    * @return mixed
    */
    public function confirm_order(){
        $pcode = input('param.pcode', '');
        $address_id = input('param.address_id', 0);
        if(Cookie::get("address_id")){
            $address_id = Cookie::get("address_id");
        }

        $pcode = $pcode?urldecode($pcode):'';

        $pointOrderPai = new PointorderPaiService();
        $json_data = $pointOrderPai->decrypt(str_replace("liuyonghao","/",$pcode));

        $data = json_decode($json_data, true);

        if (!isset($data['num']) || !isset($data['gp_id']) ) {
            return "非法请求";
        }
        $gp_id = $data['gp_id'];
        $num = $data['num'];
        $gs_id = $data['gs_id'] ? $data['gs_id'] : 1;

        $m_id = $this->m_id;

        //收货地址
        $address = new AddressService();
        $default_address = [];
        if ($address_id) {
            $default_address = $address->addressInfo(['address_id' => $address_id, 'm_id' => $m_id]);
        }
        if (!$address_id || empty($default_address)) {
            $callback = $address->getMyDefaultAddress($m_id);
            $default_address = $callback['data'];
        }

        // 商品详情
        $g_id = 0;
        if (!$gp_id) {
            return '缺少参数'; // 缺少参数
        } else {
            // 商品详情
            $goods_product = new PointGoodsProductService();
            $where['pgp.gp_id'] = $gp_id; // 商品id
            $info = $goods_product->getGoodsStoreInfo($where);
            if (empty($info)) {
                return "商品不存在！";
            }
            $g_id = $info['g_id'];
        }

        // 当前最多能团的件数
        $back = $pointOrderPai->get_max_pai_num_bygpid($m_id, $gp_id);
        $max_pai_num = 0;
        if ($back['status']) {
            $max_pai_num = $back['data']['left_max_pai_num'];
        }

        // 计算总金额
        $price = 0;
        if (!empty($info['gp_sale_price'])) {
            $price = $info['gp_sale_price'];
        }
        $all_money = $price * $num + $info['g_express'];

        // 用户信息
        $member = new MemberService();
        $mem_info = $member->get_info(['m_id' => $m_id]);
        $syumem_info = $member->get_syumem_info(['m_id'=>$m_id]);

        $this->assign('default_address', $default_address);
        $this->assign('info', $info);
        $this->assign('syumem_info', $syumem_info);
        $this->assign('num', $num);
        $this->assign('gp_id', $gp_id);
        $this->assign('gs_id', $gs_id);
        $this->assign('all_money', $all_money);
        $this->assign('mem_info', $mem_info);
        $this->assign('pcode', $pcode);
        $this->assign('max_pai_num', $max_pai_num);
        $this->assign('header_title', '确认参团订单');
        //$this->assign('header_path', '/pointpai/Pointgoods/index/g_id/'.$g_id.'/o_id/123');
        $this->assign('header_path', '');
        return $this->fetch();
    }

    /*
    * 所有参团的
    * 创建人 刘勇豪
    * @return mixed
    */
    public function pai_mem_list(){
        $gp_id = input('param.gp_id/d', 0);

        $this->assign('header_title', "所有参团的");
        $this->assign('gp_id', $gp_id);
        return $this->fetch();
    }

    public function get_pai_mem_list(){
        $m_id = $this->m_id;
        $page = input('param.page/d',1);
        $size = input('param.size/d',5);
        $gp_id = input('param.gp_id/d',0);
        $oa_status = input('param.oa_status/d',1);

        $pointOrderAwardcode = new PointOrderAwardcodeService();

        $where = [];
        if($gp_id){
            $where['poa.gp_id'] = $gp_id;
        }

        if($oa_status == 1){
            $where['poa.oa_state'] = 1;//参团中
        }elseif($oa_status == 2){
            $where['poa.oa_state'] = ['gt',1];//参团中
        }
        $order='poa.oa_id desc';
        $field='m.m_avatar,m.m_name,poa.o_id,poa.gp_id,poa.o_periods,poa.oa_state, po.o_addtime,po.o_paytime';
        $limit = (($page-1) * $size).','.$size;

        $list = $pointOrderAwardcode->get_point_awardinfo_list($where,$order,$field,$limit);
        if(!empty($list)){
            foreach($list as $k=>$v){
                $m_avatar = "/static/image/index/pic_home_taplace@2x.png";//默认头像
                if(!empty($v['m_avatar'])){
                    $m_avatar = $v['m_avatar'];// 头像
                }
                $list[$k]['m_avatar'] = $m_avatar;
            }
        }

        if(!empty($list)){
            return ['status'=>1,'msg'=>"ok",'data'=>$list];
        }else{
            return ['status'=>0,'msg'=>"empty",'data'=>$list];
        }
    }

    /*
    * 当前期的参团列表
    * 创建人 刘勇豪
    * @return mixed
    */
    public function paier_list(){

        $gp_id = input('param.gp_id/d',0);
        $o_periods = input('param.o_periods/d',0);

        $this->assign('gp_id', $gp_id);
        $this->assign('o_periods', $o_periods);
        $this->assign('header_title', '参团者列表');
        return $this->fetch();
    }

    /*
    * 支付结果
    * 创建人 刘勇豪
    * @return mixed
    */
    public function pay_result(){

        $o_id = input('param.o_id/d', 0);
        $j_type = input('param.j_type', 0);

        if (!$o_id) {
            header("location:/pointpai/Pointorder/orderlist/i/0");die;
        }
        // 订单详情
        $pointOrderPai = new PointorderPaiService();
        $info = $pointOrderPai->getMoreInfo(['po.o_id' => $o_id]);

        // 订单剩余支付时间
        $o_addtime = $info['o_addtime'];
        $over_time = 24 * 60 * 60 - (time() - $o_addtime);

        $hour = 0;
        $minute = 0;
        if($over_time>0){
            $hour = floor($over_time / 3600);
            $minute = floor(($over_time - $hour * 3600) / 60);
        }

        $time_str = $hour . "小时" . $minute . "分钟";
        $g_id = $info['g_id'];

        $this->assign('info', $info);
        $this->assign('o_id', $o_id);
        $this->assign('header_title', "支付结果");
        $this->assign('time_str', $time_str);

        return $this->fetch();
    }

    /*
    * 吖吖码列表
    * 创建人 刘勇豪
    * @return mixed
    */
    public function code_list(){

        $this->assign('header_title', "吖吖码列表");
        return $this->fetch();
    }

    /*
    * 参团订单支付
    * 创建人 刘勇豪
    * @return mixed
    */
    public function creat_order()
    {
        $address_id = input('param.address_id', '');
        $num = input('param.num', '');
        $gp_id = input('param.gp_id', '');
        $gs_id = input('param.gs_id', 1);
        $o_id = input('param.o_id', '');
        $m_id = $this->m_id;
        if (!$num || !$gp_id ) {
            return ['status' => 0, 'msg' => '非法请求！'];
        }
        $pointOrderPai = new PointorderPaiService();
        $pointGoods = new PointGoodsService();

        // 判断够不够参拍资格
        $call_back = $pointGoods -> check_level($m_id);
        if(!$call_back['status']){
            return $call_back;
        }

        // 判断是不是自己的商品
        $callback = $pointOrderPai->is_my_goods($gp_id,$m_id);
        if(!$callback['status']){
            return ['status' => 0, 'msg' => $callback['msg']];
        }

        // 判断是否超出最大购买量
        $back = $pointOrderPai->get_max_pai_num_bygpid($m_id, $gp_id);
        $max_pai_num = 0;
        if ($back['status']) {
            $max_pai_num = $back['data']['left_max_pai_num'];
        } else {
            return ['status' => 0, 'msg' => $back['msg']];
        }
        if(!$max_pai_num){
            return ['status' => 0, 'msg' => '下单失败，您在此商品的本期限购次数已用完！'];
        }
        if ($num > $max_pai_num) {
            return ['status' => 0, 'msg' => '下单失败，您最多只能购买' . $max_pai_num . '份！'];
        }

        //收货地址
        if ($gs_id < 2) {
            $address = new AddressService();
            $my_address = $address->addressInfo(['address_id' => $address_id]);
            if (empty($my_address)) {
                return ['status' => 0, 'msg' => '请填写收货地址！'];
            }
            $data['o_pid'] = $my_address['pid']; //收货人省ID
            $data['o_cid'] = $my_address['cid']; //收货人市ID
            $data['o_did'] = $my_address['did']; //收货人区ID
            $data['o_address'] = $my_address['province'] . $my_address['city'] . $my_address['district'] . $my_address['address']; //收货地址详细
            $data['o_receiver'] = $my_address['name']; //收货人姓名
            $data['o_mobile'] = $my_address['tel']; //收货人手机
            $data['o_addressid'] = $my_address['address_id']; //收货地址ID
        }

        // 商品详情
        $point_goods_product = new PointGoodsProductService();
        $where['pgp.gp_id'] = $gp_id; // 商品id
        $info = $point_goods_product->getGoodsStoreInfo($where);
        if (empty($info)) {
            return ['status' => 0, 'msg' => '商品不存在！'];
        }

        $g_state = $info['g_state'];// 商品状态 6位出售中
        $g_starttime = $info['g_starttime'];// 出售开始时间
        $g_endtime = $info['g_endtime'];// 出售最终截止时间
        if($g_state != 6){
            return ['status' => 0, 'msg' => '抱歉亲！这件商品不在出售中，还不能购买哦！'];

        }

        if($g_starttime > time()){
            return ['status' => 0, 'msg' => '抱歉亲！这件商品还没有开始参团，开团时间：'.data('Y-m-d H:i:s',$g_starttime)];
        }

        if($g_endtime < time()){
            return ['status' => 0, 'msg' => '抱歉亲！这件商品参团活动已结束了~。结束时间：'.data('Y-m-d H:i:s',$g_endtime)];
        }

        // 生成订单信息
        $data['o_sn'] = $pointOrderPai->createOrderSN(); //商品订单编码

        $data['m_id'] = $m_id; //参团人会员ID
        $data['store_id'] = $info['g_storeid']; //商品商家ID
        $data['o_point'] = $info['gp_sale_price']; //商品价格
        $data['gp_id'] = $info['gp_id']; //商品ID
        $data['gs_id'] = $info['g_typeid']; //商品特殊属性id 等于$gs_id
        $data['gp_num'] = $num; //商品数量
        $data['o_addtime'] = time(); //订单日期
        $data['o_deliverfee'] = $info['g_express']; //快递费
        $data['o_gp_settlement_price'] = $info['gp_settlement_price'];//商品下单时的结算价格
        $data['o_totalpoint'] = $info['gp_sale_price'] * $num + $info['g_express']; //订单金额
        $data['o_totalpoint'] = round($data['o_totalpoint'],2);

        // 订单期数
        $callback = $pointOrderPai->createPeriods($gp_id);
        if (!$callback['status']) {
            return ['status' => 0, 'msg' => $callback['msg']];
        }
        $data['o_periods'] = $callback['data'];

        // 订单信息验证完善
        // TODO ...

        if (!$o_id) {
            // 添加订单
            $o_id = $pointOrderPai->pointOrderPaiAdd_getId($data);

            // 有无设置密码
            $member = new MemberService();
            $mem_info = $member->get_info(['m_id' => $m_id]);
            $m_payment_pwd = $mem_info['m_payment_pwd']; // 会员支付密码
            if(empty($m_payment_pwd)){
                $url = url('/member/modifydata/first_payment_pwd/o_id/'.$o_id);
                return ['status' => 2, 'msg' => '未设置支付密码！', 'data' => $url];
            }

            if ($o_id) {
                return ['status' => 1, 'msg' => '订单生成成功！', 'data' => $o_id];
            } else {
                return ['status' => 0, 'msg' => '订单生成失败！'];
            }
        } else {
            // 修改订单
            $where_save['o_id'] = $o_id;
            $save = $pointOrderPai->pointOrderPaiSave($where_save, $data);
            if ($save) {
                return ['status' => 1, 'msg' => '订单修改成功！', 'data' => $o_id];
            } else {
                return ['status' => 0, 'msg' => '订单修改失败！', 'data' => $o_id];
            }
        }
    }

    /**
     * 参团订单支付
     * 创建人 刘勇豪
     * @return array
     */
    public function order_pay()
    {
        $o_id = input('param.o_id', '');
        $pwd = input('param.pwd', '');
        $m_id = $this->m_id;
        if (!$o_id) {
            return ['status' => 0, 'msg' => '非法请求！'];
        }

        $pointOrderPai = new PointorderPaiService();
        $calback = $pointOrderPai->order_pay($o_id, $m_id, $pwd);
        return $calback;
    }


    /**
    * 订单列表页
    * 创建人 刘勇豪
    * @return mixed
     * /pointpai/Pointorder/orderlist
    */
    public function orderlist()
    {
        $i = input('param.i/d', 0);
        $header_title = "积分订单";
        $m_id = $this->m_id;
        // 用户信息
        $member = new MemberService();
        $meminfo = $member->get_info(['m_id'=>$m_id],'m_name,m_money');
        $syumem_info = $member->get_syumem_info(['m_id'=>$m_id]);

        $this->assign('header_title', $header_title);
        $this->assign('meminfo', $meminfo);
        $this->assign('syumem_info', $syumem_info);
        $this->assign('i', $i);
        return $this->fetch();
    }

    /**
     * 动态获取我参团的列表
     * 刘勇豪
     * @return array
     * 链接：/pointpai/Pointorder/getorderlist
     */
    public function getorderlist(){
        $m_id = $this->m_id;
        $page = input('param.page/d',1);
        $size = input('param.size/d',5);
        $status = input('param.status/d',0);//订单状态
        $keyword = input('param.keyword/s','');//订单搜索关键字

        // 待支付订单保留时间
        $res = Db("config")->where(['c_code'=>'PO_UNPAID'])->find();
        $max_pay_time = 24;
        if(!empty($res1)){
            $max_pay_time = $res['c_value'];
        }

        $where['po.m_id'] = $m_id;
        $where['po.o_isdelete'] = ['lt',3];
        if($keyword){
            $where['pg.g_name'] = ['like','%'.$keyword.'%'];
        }

        //订单状态（新需求）
        switch ($status)
        {
            case 0:
                // 全部
                break;
            case 1:
                // 待付款
                $where['po.o_paystate'] = 1;
                $where['po.o_isdelete'] = 1;
                $where['po.o_addtime'] = ['gt',(time() - $max_pay_time * 60 * 60)];
                break;
            case 2:
                // 进行中（参团中）
                $where['po.o_paystate'] = 2;
                $where['po.o_state'] = 1;
                break;
            case 3:
                // 待收货（待发货、待收货）
                $where['po.o_paystate'] = ['gt',1];
                $where['po.o_state'] = ['in','2,3'];
                break;
            case 4:
                // 待评价
                $where['po.o_paystate'] = ['gt',1];
                $where['po.o_state'] = 4;
                break;
            case 5:
                // 已失败（未成团、未团中退款中、未团中退款完成）
                $where['po.o_paystate'] = ['gt',2];
                $where['po.o_state'] = ['in','10,11'];
                break;
            default:
                // 全部（我参团的）
                break;
        }


        $pointOrderPai = new PointorderPaiService();
        $order='po.o_id desc';
        $field='po.o_id,po.m_id,po.store_id,po.o_point,po.o_paystate,po.o_state,po.gp_id,po.gp_num,po.o_addtime,po.o_paytime,po.o_totalpoint,po.o_periods,po.o_isdelete,po.o_gp_settlement_price,po.gs_id,pgp.gp_market_price,pgp.g_id,
pgp.gp_img,pg.g_name,pg.g_endtime,s.stroe_name,s.store_logo,pg.g_img,pg.g_s_img';
        $limit = (($page-1) * $size).','.$size;
        $list = $pointOrderPai->get_order_detail_limit_list($where,$order,$field,$limit);

        // 判断订单是否已经超出支付时间
        if(!empty($list)){
            $is_close = 0;
            foreach($list as $kk => $vv){
                $o_paystate = $vv['o_paystate'];
                $o_addtime = $vv['o_addtime'];

                $live_paytime = $o_addtime + 24 * 60 * 60 - time();
                if($live_paytime < 1){
                    $is_close = 1;
                }
                $list[$kk]['is_close'] = $is_close;
                $list[$kk]['live_paytime'] = $live_paytime;


                // 获取二维码
                $p_goods = new PopularityGoodsService();
                $mid = 2;
                $path_3 = '/uploads/code/pointpai/shop/'.$mid.'code_'.$vv['g_id'].".jpg";
                $list[$kk]['code']  = $p_goods->hebingImg('/uploads/logo/father.png',$vv['g_img'],$path_3,$vv['g_name'],$vv['o_point'],$mid,$vv['g_id']);
                $list[$kk]['url']   = PAI_URL."/pointpai/Pointgoods/index/g_id/".$vv['g_id'].'?share=1';

                // 微信分享
                //微信分享参数
                $list[$kk]['share_title'] = '拍吖吖：只要'.$vv['o_point'].'积分，你敢信？快跟我一起来抢购吧！';
                $list[$kk]['share_desc'] =$vv['g_name'];
                $list[$kk]['share_link'] = PAI_URL.'/pointpai/Pointgoods/index/g_id/'.$vv['g_id'].'?share=1';
                $list[$kk]['share_imgUrl'] = PAI_URL.$vv['gp_img'];
            }
        }

        // 统计总条数
        $total_num = $pointOrderPai->get_order_detail_count($where);
        if(empty($list)){
            return ['status' => 0, 'msg' => '没有数据', 'data' => $list,'total_num'=>$total_num];
        }
        return ['status' => 1, 'msg' => 'success', 'data' => $list,'total_num'=>$total_num];
    }

    /**
     * 参团订单吖吖码详情
     * 刘勇豪
     * @return mixed
     */
    public function paier_info()
    {
        $o_id = input('param.o_id/d',0);
        $pointOrderAwardcode = new PointOrderAwardcodeService();
        $where['o_id'] = $o_id;
        $list = $pointOrderAwardcode -> pointOrderAwardcodeList($where);

        $m_name = '';
        if(!empty($list)){
            $m_id = $list[0]['m_id'];
            $res = Db("member")->where(['m_id'=>$m_id])->find();
            if(!empty($res)){
                $m_name = $res['m_name'];
            }
        }

        $this->assign('list', $list);
        $this->assign('m_name', $m_name);
        $this->assign('header_title', $m_name);
        return $this->fetch();
    }

    /**
     * 参团这列表
     * @return mixed
     */
    public function get_paier_list()
    {
        $m_id = $this->m_id;

        $page = input('param.page/d',0);
        $size = input('param.size/d',5);
        $gp_id = input('param.gp_id/d',0);
        $o_periods = input('param.o_periods/d',0);
        $type = input('param.type/d',0);

        if($type == 1){
            $where['m.m_id'] = $m_id;
        }elseif($type == 2){
            $where['po.o_state'] = 2;
        }
        if($gp_id){
            $where['po.gp_id'] = $gp_id;
        }
        if($o_periods){
            $where['po.o_periods'] = $o_periods;
        }
        $where['po.o_paystate'] = ['gt',1];//已支付的
        $where['po.o_isdelete'] = ['lt',2];//未删除
        $order='po.o_id asc';
        $field='*';
        $limit = ($page * $size).','.$size;
        $pointOrderPai = new PointorderPaiService();
        $list = $pointOrderPai->orderLimitList($where,$order,$field,$limit);
        if(!empty($list)){
            $html = '';
            foreach($list as $v){
                $o_id = $v['o_id'];//订单id

                $m_avatar = "/static/image/index/pic_home_taplace@2x.png";//默认头像
                if(!empty($v['m_avatar'])){
                    $m_avatar = $v['m_avatar'];// 头像
                }
                $m_name = $v['m_name'];// 昵称

                // 抽奖码份数
                $gp_num = $pointOrderPai->countPaiNum(['o_id'=>$o_id]);
                //是否团中的图片
                $o_state = $v['o_state'];
                if($o_state == 2){
                    $zhongpai_img = "<img src='/static/image/orderpai/icon_zhongpai@2x.png' o_state = ".$o_id.'=='.$o_state.">";
                }else{
                    $zhongpai_img = '';
                }

                $html .= "<a href='/pointpai/Pointorder/paier_info/o_id/".$o_id."'>
                <div class='all_participants_main_list clear'>
                    <div class='all_participants_main_picview lf'>
                        <div class='all_participants_main_pic'>
                            <img src='".$m_avatar."'>
                        </div>
                        <div class='all_participants_zhongpai'>
                            ".$zhongpai_img."
                        </div>
                    </div>
                    <div class='all_participants_text lf clear'>
                        <p>".$m_name."<span class='rt'>".date('Y-m-d H:i:s',$v['o_addtime'])."</span></p>
                        <div>拥有".$gp_num."份吖吖码<img src='/static/image/orderpai/icom_jump@2x.png'  class='rt'></div>
                    </div>
                </div>
            </a>";
            }

            return ['status'=>1,'msg'=>"ok",'data'=>$html];
        }else{
            return ['status'=>0,'msg'=>"empty",'data'=>''];
        }
    }

    /**
     * 删除订单
     * 刘勇豪
     * @return mixed
     */
    public function delete_order(){
        $o_id = input('param.o_id/d',0);
        $m_id = $this->m_id;

        $pointOrderPai = new PointorderPaiService();
        $call_back = $pointOrderPai->delete_order($o_id,$m_id);

        return $call_back;
    }

    /**
     * 取消订单
     * 刘勇豪
     * @return mixed
     */
    public function cancel_order(){
        $o_id = input('param.o_id/d',0);
        $m_id = $this->m_id;

        $pointOrderPai = new PointorderPaiService();
        $call_back = $pointOrderPai->cancel_order($o_id,$m_id);

        return $call_back;
    }

    /**
     * 确认订单
     * 刘勇豪
     * @return mixed
     */
    public function confirm_ordergoods(){
        $o_id = input('param.o_id/d',0);
        $m_id = $this->m_id;

        $pointOrderPai = new PointorderPaiService();
        $call_back = $pointOrderPai->confirm_ordergoods($o_id,$m_id);

        return $call_back;
    }

    /**
     * 订单物流详情
     * @return mixed
     */
    public function order_logistics()
    {
        $o_id = input('param.o_id/d',0);
        $is_seller = input('param.is_seller/d',0);
        $m_id = $this->m_id;
        $pointOrderPai = new PointorderPaiService();


        $where['o_id'] = $o_id;
        $call_back = $pointOrderPai->order_logistics_info($where);
        if(!$call_back['status']){
            return $call_back['msg'];
        }
        $info = $call_back['data'];

        $this->assign('info',$info);
        $this->assign('is_seller',$is_seller);
        $this->assign('header_title', "物流信息");
        return $this->fetch();
    }

    /**
     * 店家发货（新建订单物流信息）
     * 刘勇豪
     * @return mixed
     */
    public function new_logistics(){
        $o_id = input('param.o_id/d', 0);
        $is_layer = input('param.is_layer/d', 0);

        $pointOrderPai = new PointorderPaiService();
        $callback = $pointOrderPai->new_logistics_page($o_id);
        if(!$callback['status']){
            return $callback['msg'];
        }
        $info = $callback['data'];

        $this->assign('header_title', "填写快递单");
        $this->assign('o_id', $o_id);
        $this->assign('is_layer', $is_layer);
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 填写快递单提交
     * 刘勇豪
     * @return array|mixed
     */
    public function new_logistics_post(){
        $data = input('post.');
        $pointOrderPai = new PointorderPaiService();

        //提交数据
        $call_back = $pointOrderPai->new_logistics_post($data);

        return $call_back;
    }

    /**
     * 非普通商品发货
     * 刘勇豪
     * @return array
     */
    public function send_out(){
        $o_id = input('param.o_id/d', 0);
        $m_id = $this->m_id;
        $pointOrderPai = new PointorderPaiService();
        $callback = $pointOrderPai->send_out($o_id,$m_id);
        return $callback;
    }

    /**
     * 统计参团人数
     * @return array
     */
    public function count_paier(){
        $gp_id = input('param.gp_id/d',0);
        $o_periods = input('param.o_periods/d',0);
        if(!$gp_id){
            return ['status'=>0,'msg'=>"参数错误"];
        }

        $pointOrderPai = new PointorderPaiService();
        $callback = $pointOrderPai->count_paier($gp_id,$o_periods);
        if(!$callback['status']){
            return ['status'=>0,'msg'=>$callback['msg']];
        }

        $count_paier = $callback['data'];

        return ['status'=>8,'msg'=>$callback['msg'],'data'=>$count_paier];
    }

    /**
     * 订单搜索
     * 刘勇豪
     * @return mixed
     */
    public function order_search(){
        $type = input('param.type', 0);
        $keyword = input('param.keyword', '');

        $this->assign('type', $type);
        $this->assign('keyword', $keyword);
        $this->assign('header_title', "订单搜索");
        return $this->fetch();
    }

    /**
     * 积分支付回调
     * 刘勇豪
     * @return mixed
     */
    public function point_pay_callback(){
        $gp_id = input('param.gp_id/d', 0);
        $pointOrderPai = new PointorderPaiService();
        $callback = $pointOrderPai->point_pay_callback($gp_id);
        return $callback;
    }

    /**
     * 退款详情
     * 刘勇豪
     *
     */
    public function refund_info(){
        $o_id = input('param.o_id/d', 0);
        $pointOrderPai = new PointorderPaiService();
        $refund_info = $pointOrderPai->refund_info($o_id);

        if(empty($refund_info) || empty($refund_info['refund_info'])){
            $this->error("没有退款信息", "/pointpai/Pointorder/index/o_id/".$o_id, 2);
        }

        $this->assign('refund_info', $refund_info);
        $this->assign('header_title', "退款详情");
        return $this->fetch();
    }





}

