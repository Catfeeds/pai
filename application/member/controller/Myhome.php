<?php
namespace app\member\controller;
use app\data\service\api\ApiincomeService;
use app\data\service\api\ApimemberService;
use app\data\service\BaseService;
use app\data\service\config\ConfigService;
use app\data\service\goods\GoodsCollectionService;
use app\data\service\goods\GoodsService;
use app\data\service\goods\VisitGoodsHistoryService;
use app\data\service\member\MemberLevelService;
use app\data\service\member\MemberService;
use app\data\service\orderPai\OrderPaiService;
use app\data\service\peanutLog\PeanutLogService;
use app\data\service\promoters\PromotersApplyService;
use app\data\service\sms\AliService;
use app\data\service\sms\SmsService;
use app\data\service\store\StoreCollectionService;
use app\data\service\store\StoreService;
use app\data\service\system_msg\SystemMsgService;
use RedisCache\RedisCache;
use think\Cache;
use think\Db;

class Myhome extends MemberHome
{
    //我的
    public function index(){
        $m_id = $this->m_id;
        $mem = new MemberService();
        $where = [
            'm_id'=>$m_id
        ];
        //收藏商品数量
        $goods_collection = new GoodsCollectionService();
        $g_c_num = $goods_collection->collection_num($where);
        //关注店铺数量
        $store_collection= new StoreCollectionService();
        $s_c_num = $store_collection->get_count($where);
        //足迹数量
        $visit_goods_history = new VisitGoodsHistoryService();
        $v_g_h_num = $visit_goods_history->v_get_count($m_id);
        //商家id
        $store =  new StoreService();
        $store_id = $store->get_value($where,'store_id');
        $store_name = $store->get_value($where,'stroe_name');
        $store_logo = $store->get_value($where,'store_logo');
        $goods = new GoodsService();
        $where2 = [
            'g_mid'=>$m_id,
            'g_state'=>['in',[2,4,6,8,9]]
        ];
        $goods_list = [
            'list'=>array(),
            'len'=>0
        ];
        if($store_id){
            $goods_list = $goods->get_limit_list($where2,'g_addtime desc','g_id,g_img,g_s_img',1,4);
            $goods_list = [
                'list'=>$goods_list,
                'len'=>count($goods_list)
            ];
        }
        //用户信息
        $data = $mem->get_info($where,"m_name,m_avatar,m_s_avatar,m_type,m_payment_pwd,m_levelid,is_promoters");
        $member_level = new MemberLevelService();
        //推广等级图片显示
        $data['tgy_img'] = '';

        if($data['is_promoters'] == 4 || $data['is_promoters'] == 5){
            $data['ml_img'] = '/uploads/logo/tfy.png';
            if($data['is_promoters'] == 5){
                $promoters_apply = new PromotersApplyService();
                $is_first_img = $promoters_apply ->get_value(['m_id'=>$m_id],'is_first_img');
                if($is_first_img == 1){
                    $data['tgy_img'] = '/uploads/logo/tfy_first_img.png';
                    $promoters_apply->get_save(['m_id'=>$m_id],['is_first_img'=>2]);
                }
            }
        }else{
            //用户等级头像
            $where2 = [
                'ml_id'=>$data['m_levelid']
            ];
            $data['ml_img'] = $member_level->get_value($where2,'ml_img');
        }
        //是否设置支付密码
        $data['m_payment_pwd'] = $data['m_payment_pwd'] ?  true :false;
        //待付款/
        $where3 = [
            'o_state' => ['in','1,2,3,4'],
            'm_id'    =>$m_id
        ];

        $orderpai = new OrderPaiService();
        $order = $orderpai->get_group_count($where3,'count(*)num,o_state','o_state');
        $order = array_column($order,'num','o_state');
        $order[5] = Db::table('pai_order_pai')->where(['o_state'=>['in','2,3,4,5'],'m_id'=>$m_id])->count();
        $config = new ConfigService();
        $where4 = [
          'c_code'=>'PO_UNPAID',
        ];
        $c_value = $config->configGetValue($where4,'c_value');
        $time = $c_value * 60 * 60;
        $where3 = [
            'o_paystate' =>1,
            'o_addtime' =>['>=',time()-$time],
        ];
        $o_paystate = $orderpai->orderPaiInfo($where3,'count(*)num');

        //获取是否有未读消息
        $is_read = Cache::get('is_read');
        $is_read = $is_read ? 1 : 0;
        if($is_read==0){
            $where = [
                'to_mid'=>$m_id,
                'is_read'=>1,
                'sm_public'=>1
            ];
            $msg = new SystemMsgService();
            $is_read = $msg->get_count($where);
            if($is_read){
                Cache::set('is_read',1);
            }
        }
//        $promoters_apply = new MemberService();
//        $is_promoters = $promoters_apply->get_value(['m_id'=>$m_id],'is_promoters');
        $data['is_read']       = $is_read;
        $data['goods_collection']       = $g_c_num;
        $data['store_collection']       = $s_c_num;
        $data['visit_goods_history']    = $v_g_h_num;
        $data['store_id']               = $store_id;
        $data['store_name']             = $store_name;
        $data['store_logo']             = $store_logo;
        $data['goods']                  = $goods_list;
        $data['is_promoters']           = $data['is_promoters'];//是否为推广员  1否  2申请中 3审核失败 4考核中 5考核成功(推广员) 6考核失败
        $this->assign(['data'=>$data]);
        //下标1参团冲 2待发货 3待收货 4待评价 5团中
        $this->assign('order',$order);
        //待付款数量
        $this->assign('o_paystate',$o_paystate);
       return  $this->fetch();

    }
    /*
     * 实名认证
     * 张文斌
     * 2018-9-11
     */
    public function goto_Authentication(){
        $m_id = $this->m_id;
        $mem = new MemberService();
        $where = [
            'm_id'=>$m_id
        ];
        $is_attachment = $mem->get_attestation($where,$field="real_name,identification");
        if(!empty($is_attachment)){
            return ['status'=>0,'msg'=>'您已经实名认证过'];
        }
        $apimember = new ApimemberService();
        $baseservice = new BaseService();
        $insert_attachment = array();
        $AliService = new AliService();//身份认证service
        if(request()->isAjax()){
            $data = input('param.');
            $real_name = $data['real_name'];
            if(empty($real_name)){
                return ['status'=>0,'msg'=>'真实姓名不能为空'];
            }
            $identification = $data['identification'];
            if(empty($identification)){
                return ['status'=>0,'msg'=>'身份证号码不能为空'];
            }
            $idcard_positive = request()->file('idcard_positive');//二进制
            $idcard_reverse = request()->file('idcard_reverse');
            $new_idcard_positive = empty($data['idcard_positive']) ? [] : $data['idcard_positive'];
            $new_idcard_reverse = empty($data['idcard_reverse']) ? [] : $data['idcard_reverse'];
            if($idcard_positive){//二进制形式
                $file = $baseservice->upload('idcard_positive','idcard_positive','',500,300);
                if($file){
                    $insert_attachment['idcard_positive'] = $file;
                }else{
                    return ['status'=>0,'msg'=>'身份证正面照上传失败'];
                }
            }else{//数组形式
                if(array_filter($new_idcard_positive)){
                    $idcard_positive = array_values(array_filter($new_idcard_positive));
                    $insert_attachment['idcard_positive'] = $apimember->ba64_img($new_idcard_positive,'idcard_positive')[0];
                }
            }
            if($idcard_reverse){
                $file_two = $baseservice->upload('idcard_reverse','idcard_reverse','',500,300);
                if($file_two){
                    $insert_attachment['idcard_reverse'] = $file_two;
                }else{
                    return ['status'=>0,'msg'=>'身份证反面照上传失败'];
                }
            }else{
                if(array_filter($new_idcard_reverse)){
                    $idcard_reverse = array_values(array_filter($new_idcard_reverse));
                    $insert_attachment['idcard_reverse'] = $apimember->ba64_img($new_idcard_reverse,'idcard_reverse')[0];
                }
            }
            $return_list = $AliService->id_check($data['identification'],$data['real_name']);//调用身份认证
            if($return_list['status'] != '01'){
                return ['status'=>0,'msg'=>$return_list['msg']];
            }
            $sex = $return_list['sex'] == '女' ? 1 : 2;//性别
            $insert_attachment['real_name'] = $real_name;
            $insert_attachment['identification'] = $identification;
            $insert_attachment['add_time'] = time();
            $insert_attachment['m_id'] = $m_id;
            $insert_attachment['status'] = 1;
            $insert_attachment['area'] = $return_list['area'];
            $insert_attachment['province'] = $return_list['province'];
            $insert_attachment['city'] = $return_list['city'];
            $insert_attachment['prefecture'] = $return_list['prefecture'];
            $insert_attachment['birthday'] = strtotime($return_list['birthday']);
            $insert_attachment['addrCode'] = $return_list['addrCode'];
            $insert_attachment['sex'] = $sex;
            $res = $mem->insertId($insert_attachment);//插入实名认证表
            $update_member = Db('member')->where($where)->update(['real_name'=>$real_name,'m_identification'=>$identification]);
            if($res && $update_member){
                return ['status'=>1,'msg'=>'实名认证成功'];
            }
        }
    }
    /*
     * 为实名认证绑定提现银行卡即实名认证
     */
    public function bankcard_sm(){
        $m_id = $this->m_id;
        $where = [
            'm_id'=>$m_id
        ];
        $tuiincome = new ApiincomeService();
        $member = $tuiincome->isattestation($where,$field='real_name,identification');
        $info = $tuiincome->getInfo($where,$filed="*");
        if(!empty($info['m_bankowner']) && !empty($info['m_bank_phone']) && !empty($info['m_bankaccount']) && !empty($info['m_identification'])){
            return ['status'=>0,'msg'=>'只能绑定一个银行卡'];
        }
        if(empty($data['m_bankowner'])){
            return ['status'=>0,'msg'=>'开户人不能为空'];
        }
        if(empty($data['m_bankaccount'])){
            return ['status'=>0,'msg'=>'个人银行卡号不能为空'];
        }
        if(empty($data['m_bank_phone'])){
            return ['status'=>0,'msg'=>'预留手机号不能为空'];
        }
        if(empty($data['m_identification'])){
            return ['status'=>0,'msg'=>'身份证号不能为空'];
        }
        if(empty($data['verification'])){
            return ['status'=>0,'msg'=>'验证码不能为空'];
        }
        if(empty($member)){//判断没有在实名认证表
            Db::startTrans();
            try{
                $sms = new SmsService();//此处检测短信验证是否正确
                $res = $sms->checkSmsCode($data['verification'],$data['m_bank_phone']);
                if($res['status']!=1){
                    return ['status'=>0,'msg'=>$res['msg']];
                }
                $AliService = new AliService();
                $return_list = $AliService->id_check($data['m_identification'],$data['m_bankowner']);
                if($return_list['status'] != '01'){
                    return ['status'=>0,'msg'=>$return_list['msg']];
                }
                $sex = $return_list['sex'] == '女' ? 1 : 2;
                $update['m_id'] = $m_id;
                $update['real_name'] = $return_list['name'];
                $update['identification'] = $return_list['idCard'];
                $update['add_time'] = time();
                $update['area'] = $return_list['area'];
                $update['province'] = $return_list['province'];
                $update['city'] = $return_list['city'];
                $update['prefecture'] = $return_list['prefecture'];
                $update['birthday'] = strtotime($return_list['birthday']);
                $update['addrCode'] = $return_list['addrCode'];
                $update['sex'] = $sex;
                $attestation_id = $tuiincome->add_attestation($update);//插入实名认证表返回插入id
                $AliService = new AliService();
                $return_list_ty = $AliService->bank_check($data['m_bankaccount'],$data['m_identification'],$data['m_bank_phone'],$data['m_bankowner']);
                if($return_list_ty['status'] != 01){
                    return ['status'=>0,'msg'=>$return_list_ty['msg']];
                }
                $array = array(
                    'm_bankowner'=>$return_list_ty['name'],
                    'm_bank_phone'=>$return_list_ty['mobile'],
                    'm_bankaccount'=>$return_list_ty['accountNo'],
                    'm_identification'=>$return_list_ty['idCard'],
                    'm_province'=>$return_list_ty['province'],
                    'm_city'=>$return_list_ty['city'],
                    'm_bankname'=>$return_list_ty['bank'],
                );
                $list = $tuiincome->getSave($where,$array);//修改pai_member表
                $arr_atta = array(
                    'bankowner'=>$return_list_ty['name'],
                    'bankname'=>$return_list_ty['bank'],
                    'bankaccount'=>$return_list_ty['accountNo'],
                    'bank_phone'=>$return_list_ty['mobile'],
                );
                $save_atta = $tuiincome->get_atta($where,$arr_atta);
                Db::commit();
                return ['status'=>1,'msg'=>'绑定银行卡成功'];
            }catch (\Exception $e){
                Db::rollback();
                $msg = $e ->getMessage();//错误信息
                $arr = array(
                    'el_type_id'=>3,
                    'el_description'=>$msg,
                    'm_id'=>$m_id,
                    'el_time'=>time(),
                );
                $res = Db('error_log')->insert($arr);//插入错误日志表
                return ['status'=>0,'msg'=>'绑定银行卡失败'];
            }
        }
    }
    /**
     * 我的足迹列表
     * 邓赛赛
     */
    public function visit_list(){
        $m_id = $this->m_id;
        $page = input('param.page/d');
        $page = $page ? $page : 1;
        $page_size = input('param.page_size') ? input('param.page_size') : 4;
        $visit_goods_history = new VisitGoodsHistoryService();
        $where=[
            'g.g_state'=>['in',6,8,9],
            'v.m_id'=>$m_id
        ];
        $field='g.g_name,g.g_id,g.g_state,g.g_img,g.g_s_img,gp.gp_market_price,gp.gp_id,v.m_id,v.vgh_id,v.visit_time';
        $list['list'] = array();
        $list = $visit_goods_history->get_limit_list($where, $order='v.vgh_id desc', $field, $page,$page_size);
        if(request()->isAjax()){
            return $list;
        }
        $this->assign('data',$list);
        $this->assign('header_title','我的足迹列表');
        return $this->fetch();
    }

    /**
     * 删除我的足迹
     * 邓赛赛
     */
    public function del_visit(){
        if(request()->isAjax()){
            $redis = RedisCache::getInstance(1);
            $m_id = $this->m_id;
            $data = input('post.');
            if(empty($data['vgh_id'])){
                return ['status'=>0,'msg'=>'删除足迹失败'];
            }
            $vgh_id = $data['vgh_id'];
            $vgh_id = implode(',',$vgh_id);

            $where = [
                'm_id'=>$m_id,
                'vgh_id'=>['in',$vgh_id]
            ];
            $visit = new VisitGoodsHistoryService();
            $g_id = $visit->get_info($where,'g_id');
            $res = $visit -> del($where);
            if($res){
                $redis->del('visit_goods_history_'.$m_id.'_'.$g_id['g_id']);
                return ['status'=>1,'msg'=>'删除成功'];
            }else{
                return ['status'=>0,'msg'=>'删除失败'];
            }
        }
    }

    /**
     * @return \think\response\View
     * 我的花生
     * 邓赛赛
     */
    public function peanut(){
        $m_id = $this->m_id;
        if(request()->isAjax()){
            $param      = input('param.');
            $page       = empty($param['page'])      ? 1 : $param['page'];
            $page_size  = empty($param['page_size']) ? 6 : $param['page_size'];
            $pl_type    = empty($param['pl_type'])   ? '': $param['pl_type'];

            $peanut_log = new PeanutLogService();
            $where2 = [
                'pl_mid'=>$m_id,
                'pl_state'=>8,
            ];
            switch($pl_type){
                case 'add':
                    $where2['pl_type'] = $pl_type;
                    break;
                case 'reduce':
                    $where2['pl_type'] = $pl_type;
                    break;
            }
            $list['list'] = $peanut_log->get_limit($where2,'pl_time desc','*',$page,$page_size);
            $lists['list'] = array();
            //整合成新的数组
            $add_money = 0;
            $reduce_money = 0;
//            dump($list);
            $lists['list'] = array();
            foreach($list['list'] as $k => $v){
                $v['date_pl_time'] = date('Y-m-d',$v['pl_time']);
                $v['month'] = date('Y-m',$v['pl_time']);
                $lists['list'][] = $v;
                $date = $v['month'];
                $month_begin_date = $date . "-01";

                $start_time = strtotime($month_begin_date); //月初时间
                $next_month_begin_date = date('Y-m-d H:i:s', strtotime("$month_begin_date +1 month")-1);    //月底时间
                $ent_time = strtotime($next_month_begin_date);
                $where = [
                    'pl_mid'=>$m_id,
                    'pl_time'=>['between time',[$start_time,$ent_time]],
                    'pl_state'=>8
                ];
//
                if(!isset($time[$v['month']])){
                    //第一次设置此变量(值无意义)
                    $time[$v['month']] = '第一次才会设置此变量';
                    $where['pl_type'] = 'add';
                    $add_money = $peanut_log->get_value($where,'sum(pl_num)');
                    $lists['list'][$k]['add_price'] = $add_money ? $add_money : 0;
                    $where['pl_type'] = 'reduce';
                    $reduce_money = $peanut_log->get_value($where,'sum(pl_num)');
                    $lists['list'][$k]['reduce_money'] = $reduce_money ? $reduce_money : 0;
                    $time[$v['month']] = '';
                }else{
                    $lists['list'][$k]['add_price'] = $add_money ? $add_money : 0;
                    $lists['list'][$k]['reduce_money'] = $reduce_money ? $reduce_money : 0;
                }
            }
            //日期下标重新变为数字下标
            $list['list'] = $lists['list'];
            $total_num = $peanut_log->get_Count($where2);
            $total_page = ceil($total_num/$page_size);
            $list['page'] = (int)$page;
            $list['page_size'] = (int)$page_size;
            $list['new_num'] = count($list['list']);
            $list['total_num'] = $total_num;
            $list['total_page'] = $total_page;
            return $list;
        }
        $where = [
            'm_id'=>$m_id
        ];
        $member = new MemberService();
        //现有花生
        $peanut = $member->get_info($where,'peanut');
        $info['peanut'] = empty($peanut['peanut']) ? 0 : $peanut['peanut'];
        $peanut_log = new PeanutLogService();
        $where = [
            'pl_mid'=>$m_id,
            'pl_state'=>8,
            'pl_type'=>'add',
        ];
        //总花生
        $info['total_peanut'] = $peanut_log->get_value($where,'sum(pl_num)');
        $info['total_peanut'] = sprintf("%.2f",$info['total_peanut']);
        //抵用
        $info['spend_peanut'] = sprintf("%.2f",$info['total_peanut'] - $info['peanut']);
        $this->assign('header_title','我的花生');
        $this->assign('header_path','/member/myhome/index');
        $this->assign('info',$info);
        return view();
    }

    /**
     * 充值花生
     * 邓赛赛
     */
    public function recharge_peanut(){
        $m_id = $this->m_id;
        $sql="select m_money,peanut from pai.pai_member where m_id=$m_id";
        $list=Db::table("pai_member")->query($sql);
        $m_money=$list[0]['m_money']?$list[0]['m_money']:'0.00';
        $peanut=$list[0]['peanut']?$list[0]['peanut']:'0.00';
       $this->assign("m_money",$m_money);
        $this->assign("peanut",$peanut);
        $this->assign("m_id",$m_id);
        $this->assign('header_title','充值花生');
        return view();
    }
    /**
     * 充值花生成功
     * 邓赛赛
     */
    public function recharge_peanut_scs(){
        $this->assign('header_title','充值花生');
        return view();
    }

    /**
     * 花生帮助说明
     * 邓赛赛
     */
    public function peanut_explain(){
        $this->assign('header_title','花生规则');
        return view();
    }

    /**
     * 返现规则
     * 邓赛赛
     */
    public function cashback_rule(){
        $this->assign('header_title','返现规则');
        return view();
    }

    /**
     * 通过手机号码获取id
     * 邓赛赛
     */
    public function lookup_id(){
        $mobile = input('param.mobile');
        $base = new BaseService();
        $m_mebile = $base->encrypt($mobile);
        $where = [
            'm_mobile'=>$m_mebile,
        ];
        $field = 'm_id';
        $member = new MemberService();
        $id = $member->get_value($where,$field);
        $id = $id ? $id : '未查找到此手机ID';
        return $id;
    }

    /**
     * 转换手机号码
     * 邓赛赛
     */
    public function lookup_phone(){
        $mobile = input('param.mobile');
        $base = new BaseService();
        $m_mebile = $base->decrypt($mobile);
        return $m_mebile;
    }

    public function alllookup_phone(){
        $data1 = file_get_contents('22.txt');
        $data1 = explode(',',$data1);
        foreach($data1 as $v) {
            $base = new BaseService();
            $m_mebile = $base->decrypt($v);
            echo $m_mebile.',';
        }
    }

    /**
     * 加密手机号码
     * 邓赛赛
     */
    public function encrypt_phone(){
        $mobile = input('param.mobile');
        $base = new BaseService();
        $m_mebile = $base->encrypt($mobile);
        return $m_mebile;
    }
}
