<?php
/**
 * 店铺分类Model
 *-------------------------------------------------------------------------------------------

 *--------------------------------------------------------------------------------------------
 */

namespace app\data\model\store;
use app\data\model\BaseModel as BaseModel;
use app\data\service\member\MemberService;
use app\data\service\system_msg\SystemMsgService;
use think\Db;
class StoreModel extends BaseModel
{
    protected $db = 'store' ;//注入表

    /**
     * 获取指定表的单条信息
     * @param $db
     * @param $where
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getBusinessAuthInfo($db,$where){
        $info = Db::table($db)->where($where)->find();
        return $info;
    }

    /**
     * 修改商家申请表
     * @param $where
     * @param $data
     * @return bool
     */
    public function dataSave($db,$where, $data)
    {
        if (!$data || !$where)
        {
            return false;
        }
        //添加系统消息
        $system = new SystemMsgService();

        $save = Db::table($db)->where($where)->update($data);
        if($save && ($data['ba_state']==8)){
            $find = $this->getBusinessAuthInfo($db,$where);
            $res = Db::table('pai_store')->where('m_id',$find['m_id'])->field('m_id')->find();
            $store_type = $data['ba_type'];
            $data = [
                'corporation_name' => $find['corporation_name'],
                'corporation_tel' => $find['store_tel'],
                'store_categoryid' => $find['store_categoryid'],
                'm_id' => $find['m_id'],
                'add_time' => $find['ba_success_time'],
                'store_license' => $find['ba_license'],
                'store_license_img' => $find['ba_license_img'],
                'store_person_tel' => $find['ba_legal_person_tel'],
                'store_state' => 0,
                'store_type' =>$store_type,
                'pid' => $find['pid'],
                'cid' => $find['cid'],
                'did' => $find['aid'],
                'address' => $find['address'],
                'stroe_name' => $find['ba_stroe_name'],
            ];
            if($res){
                Db::table('pai_store')->where('m_id',$find['m_id'])->update($data);
            }else{
                Db::table('pai_store')->insert($data);
            }
            Db::table('pai_member')->where('m_id',$find['m_id'])->update(['m_type'=>1]);

            $data2 = [
                'sm_addtime'=>time(),
                'sm_display'=>1,
                'sm_title'=>'商家审核已通过',
                'sm_brief'=>'恭喜您提交的入驻申请通过',
//                'sm_content'=>"恭喜您已通过商家审核，现在即可发布商品哦。<a href='/member/goods/index'> 去发布 </a>",
                'to_mid'=>$find['m_id'],
            ];
            $system->AddSystemMsg($data2);
        }else if($save && ($data['ba_state']==9)){

            $find = $this->getBusinessAuthInfo($db,$where);
            $data2 = [
                'sm_addtime'=>time(),
                'sm_display'=>1,
                'sm_title'=>'商家审核未通过',
                'sm_brief'=>'前往查看理由',
                'is_success'=>1,
                'to_mid'=>$find['m_id'],
            ];
            $system->AddSystemMsg($data2);

            $res = Db::table('pai_store')->where('m_id',$find['m_id'])->field('m_id')->find();
            if($res){
                Db::table('pai_store')->where('m_id',$find['m_id'])->update(['store_state'=>1]); //状态为1
                Db::table('pai_member')->where('m_id',$find['m_id'])->update(['m_type'=>0]);    //商家设为会员
            }
        }
        return $save;
    }

    /**
     * 搜索店铺
     * 邓赛赛
     */
    public function searchStore($where,$order,$field,$offset,$page_size){
        $list['list'] = Db::table($this->tbale)
            ->where($where)
            ->order($order)
            ->field($field)
            ->limit($offset,$page_size)
            ->select();
        $where2 = [
            'g.g_state'=>6,
            'g.g_endtime'=>['>',time()]
        ];
        foreach($list['list'] as $k => $v){
            $where2['g.g_storeid']=$v['store_id'];
            $goods = Db::table('pai_goods')
                ->alias('g')
                ->where($where2)
                ->join('pai_goods_product gp','g.g_id = gp.g_id','left')
                ->field('g.g_name,gp.gp_market_price,g.g_img,g.g_s_img,g.g_id')
                ->limit(3)
                ->order('g.g_starttime desc')
                ->select();
            $list['list'][$k]['goods'] = $goods;
        }
        $total_num = Db::table($this->tbale)
            ->where($where)
            ->count();
        $total_page = ceil($total_num/$page_size);
        $list['new_num'] = count($list['list']);
        $list['page_size'] = $page_size;
        $list['total_page'] = $total_page;
        $list['total_num'] = $total_num;
        return $list;
    }

    /**
     * 支付保证金
     * 邓赛赛
     */
    public function addDeposit($where,$data){
        $m_id = $where['m_id'];
        //暂时不用此商品id(所有保证金一下商品都是变为审核中)
        $g_id = $where['g_id'];
        //获取推荐者信息和应获得收益
//        $info = Db::table('pai_member')
//            ->alias('m')
//            ->where(['m.m_id'=> $m_id])
//            ->join('pai_member_level ml','m.m_levelid=ml.ml_id','left')
//            ->join('pai_member tj_m','m.tj_mid=tj_m.m_id','left')
//            ->join('pai_member_level tj_ml','tj_m.m_levelid=tj_ml.ml_id','left')
//            ->join('pai_store s','tj_m.m_id=s.m_id','left')
//            ->field('m.m_id,ml.ml_id,tj_m.m_id tj_m_id,tj_ml.ml_id tj_ml_id,tj_ml.ml_seller_join,s.stroe_name,m.m_mobile')
//            ->find();
        Db::startTrans();
        try{
            //余额减少
            Db::table('pai_member')->where('m_id', $m_id)->setDec('m_money', $data);
            //保证金增加
            Db::table('pai_store')->where('m_id', $m_id)->setInc('deposit', $data);
            //商品状态改为审核中
//            Db::query("update pai_goods g  join pai_goods_product gp on g.g_id=gp.g_id set g.g_state= 2 where gp.gp_settlement_price < $market AND g.g_state= 1");
            Db::query("update pai_goods  set g_state= 2 where g_id = $g_id");
            //充值保证金消息通知
            $system_insert = [
                'sm_addtime'=>time(),
                'sm_title'  =>'缴纳保证金',
                'sm_brief'  =>'您所缴纳的保证金成功',
                'sm_content'  =>'您所缴纳的保证金成功,我们会快速审核您的商品,请注意查看消息通知  --- <a href="/member/goods/my_goods">快去查看吧</a>',
                'to_mid'  =>$m_id,
            ];
            Db::table("pai_system_msg")->insert($system_insert);
            $money_log = [
                'ml_type'   =>'reduce',
                'ml_reason' =>'缴纳保证金',
                'ml_money'=>$data,
                'money_type'=>1,
                'add_time'=>time(),
                'm_id'=>$m_id,
                'state'=>8,
            ];
            Db::table("pai_money_log")->insert($money_log);

//            //推荐者等级必须大于充值者等级才可获得收益
//            if($info['tj_ml_id'] > $info['ml_id']){
//                $res = Db::table('pai_income')->where(['m_id'=>$info['tj_m_id'],'i_from_mid'=>$m_id,'i_typeid'=>3])->count();
//                //第一次交纳保证金给上家收益
//                if(!$res){
//                    Db::table("pai_member")->where(['m_id'=>$info['tj_m_id']])->setInc('m_income',$info['ml_seller_join']);
//                    $mem = new MemberService();
//                    $mobile = $mem->decrypt($info['m_mobile']);
//                    $mobile = substr_replace($mobile,'****',3,4);
//                    $income_insert = [
//                        'i_time'        =>time(),
//                        'i_typeid'      =>3,
//                        'm_id'          =>$info['tj_m_id'],
//                        'i_state'       =>8,
//                        'i_money'       =>$info['ml_seller_join'],
//                        'i_reason'      =>"来自下级账号{".$mobile."}的缴纳保障金收益",
//                        'i_from_mid'   =>$m_id,
//                        'i_from_id'     =>0
//                    ];
//                    $id = Db::table("pai_income")->insertGetId($income_insert);
//                    //添加通知受益者消息
//                    $system = [
//                        'sm_addtime'=>time(),
//                        'sm_title'  =>'收益通知',
//                        'sm_brief'  =>'推荐商家收益通知',
//                        'sm_content'  =>'您推荐的商家为您带来的收益,请注意查收,可在我的收益中查看',
//                        'to_mid'  =>$info['tj_m_id'],
//                    ];
//                    Db::table("pai_system_msg")->insert($system);
//                    $money_log = [
//                        'ml_type'   =>'add',
//                        'ml_reason' =>'推荐商家缴纳保证金收益',
//                        'ml_table'=>3,
//                        'ml_money'=>$info['ml_seller_join'],
//                        'money_type'=>2,
//                        'nickname'=>$info['stroe_name'],
//                        'position'=>"签约商家Lv".$info['ml_id'],
//                        'member_type'=>2,
//                        'income_type'=>1,
//                        'add_time'=>time(),
//                        'from_id'=>$id,
//                        'm_id'=>$info['tj_m_id'],
//                        'state'=>8,
//                    ];
//                    Db::table("pai_money_log")->insert($money_log);
//                }
//            }
            Db::commit();
            return ['status'=>1,'msg'=>'充值成功'];
        }catch (\Exception $e){
            Db::rollback();
            return ['status'=>0,'msg'=>'充值失败,请稍后重试'];
        }
    }



}