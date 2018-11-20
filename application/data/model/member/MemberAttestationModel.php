<?php

namespace app\data\model\member;
use app\data\model\BaseModel;
use think\Db;
use think\Request;
    class MemberAttestationModel extends BaseModel
{
    protected $db = 'member_attestation' ;//认证表

    /**
     * 获取此身份证已被多少人认证
     * 邓赛赛
     */
    public function id_attestation_num($id){
        $num = Db::table('pai_member_attestation')
            ->where('identification',$id)
            ->group('m_id')
            ->count();
        return $num;
    }

}