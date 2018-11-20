<?php
/**
 * 管理员Service
 *-------------------------------------------------------------------------------------------
 * 版权所有 广州市素材火信息科技有限公司
 * 创建日期 2017-07-12
 * 版本 v.1.0.0
 * 网站：www.sucaihuo.com
 *--------------------------------------------------------------------------------------------
 */

namespace app\data\service\withdraw;
use app\data\model\withdraw\WithdrawModel;
use app\data\service\BaseService as BaseService;
use app\data\service\system_msg\SystemMsgService;
use think\Db;
class WithdrawService extends BaseService
{

    protected $cache = 'withdraw'; //提现表

    public function __construct()
    {
        parent::__construct();
        $this->withdraw = new WithdrawModel();
        $this->cache = 'withdraw';
    }

    /**
     * @param $data
     * @return bool|int|string
     * 提现记录
     * 邓赛赛
     */
    public function get_add_id($data){
        $id = $this->withdraw->insertId($data);
        return $id;
    }
    /**
     * @param $data
     * @return bool|int|string
     * 获取某字段
     * 邓赛赛
     */
    public function get_value($data,$field='w_id'){
        $w_id= $this->withdraw->get_value($data,$field);
        return $w_id;
    }
    /**
     * @param $data
     * @return bool|int|string
     * 获取某字段
     * 邓赛赛
     */
    public function get_info($where,$field){
        $info = $this->withdraw->getInfo($where,$field);
        return $info;
    }
    /**
     * @param $data
     * @return bool|int|string
     * 获取某字段
     * 邓赛赛
     */
    public function get_count($data){
        $num= $this->withdraw->get_count_num($data);
        return $num;
    }

    /**
     * 获取用户提现列表
     * 邓赛赛
     */
    public function get_withdraw_page($where,$field='*'){
        $list = $this->withdraw->get_page($where,$field);
        return $list;
    }
    /**
     * 获取用户提现列表
     * 邓赛赛
     */
    public function get_withdraw_limit($where,$order='w_id desc',$field='*',$page=1,$size=10){
        $offset = ($page - 1) * $size;
        $list = Db::table('pai_withdraw w')
            ->join('pai_member_attestation ma','w.ac_id = ma.id','left')
            ->where($where)
            ->order($order)
            ->field($field)
            ->limit($offset,$size)
            ->select();
        return $list;
    }

    /**
     * 修改一条数据
     * 邓赛赛
     */
    public function get_save($where,$data){
        $res = $this->withdraw->getSave($where,$data);
        return $res;
    }

    /**
     * 提现完成（管理员操作时触发）
     */
    public function withdraw_money($w_id,$admin_id){
        Db::startTrans();
        try{
            $where=['w_id'=>$w_id];
            $date = [
                'w_state'=>3,
                'w_success_time'=>time(),
                'admin_id'=>$admin_id
            ];
            $this->get_save($where,$date);
            $info = $this->withdraw->getInfo($where,'*');
            $where2= [
                'from_id'=>$info['w_id']
            ];
            $update=[
                'state'=>8,
                'update_time'=>time(),
            ];
            Db::table("pai_money_log")->where($where2)->update($update);
            $name = $info['w_type'] == 1 ? 'm_frozen_money' : 'm_frozen_income';
            Db::table('pai_member')->where('m_id', $info['w_mid'])->setDec($name, $info['w_money']);
            $system = new SystemMsgService();
            $data = [
                'sm_addtime'=>time(),
                'sm_title'=>'提现成功',
                'sm_brief'=>'您有一笔提现受理成功',
                'sm_content'=>'您与'.date("Y-m-d H:i",$info['w_time']).'有一笔'.$info['w_money'].'元的提现受理成功,请注意查收',
                'to_mid'=>$info['w_mid'],
            ];
            $system->add_msg($data);

            //冻结表,解封审批数据(就是减少金额)
            $w_mid  = $this->withdraw->get_value(['w_id'=>$w_id],'w_mid');
            $w_money= $this->withdraw->get_value(['w_id'=>$w_id],'w_money');
            $f_money = Db::table("pai_frozen")->where(['m_id'=>$w_mid])->order('f_time desc')->value('f_money');
            $new_money = $f_money-$w_money;
            $data2 = [
                'f_type'=>'reduce',
                'f_money'=>$new_money,
                'f_typeid'=>2,
                'f_time'=>time(),
                'f_from_id'=>$w_id,
                'm_id'=>$w_mid,
            ];
            Db::table("pai_frozen")->insert($data2);

            Db::commit();
            $date = date('Y-m-d H:i');
            return ['status'=>1,'msg'=>'提现完成','date'=>$date];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['status'=>0,'msg'=>'提现失败'];
        }
    }

    /**
     * 金额详情
     * 邓赛赛
     */
    public function get_money_info($where,$field='*',$page=1,$page_size=20){
        $page = $page < 1 ? 1 : $page;
        $offset = ($page - 1) * $page_size;
        $list = Db::table('pai_money_log')->where($where)->field($field)->limit($offset,$page_size)->order('add_time desc')->select();
        return $list;
    }

    /**
     * 金额详情
     * 邓赛赛
     */
    public function get_list($where,$order='add_time desc',$field='*'){
        $list = Db::table('pai_money_log')->where($where)->order($order)->field($field)->select();
        return $list;
    }


}