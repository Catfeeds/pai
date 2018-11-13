<?php
namespace app\member\controller;
use app\data\service\article\ArticleService;
use app\data\service\articleType\ArticleTypeService;
use app\data\service\BaseService;
use app\data\service\member\MemberAttestationService;
use app\data\service\member\MemberService;
use think\Db;
use think\Request;

class Modifydata extends MemberHome
{
    /**
     * 设置页面
     */
    public function set_data(){
        $where=[
            'm_id'=>$this->m_id,
        ];

        $member = new MemberService();
        $field="m_name,m_avatar,m_mobile,m_sex,real_name,m_identification";
        $info = $member->get_info($where,$field);

        if(empty($info['m_mobile'])){
            $info['m_mobile'] = '未填写';
        }else{
            $info['m_mobile']  = $member->decrypt($info['m_mobile']);
            $info['m_mobile'] = substr($info['m_mobile'],0,3)."*****".substr($info['m_mobile'],-4,4);
        }
        $this->assign('header_title','设置');
        $this->assign('header_path',"/member/myhome/index");
        $this->assign(['info'=>$info]);
        return $this->fetch();
    }

    /**
     * 密码设置页面（登陆后的设置）
     */
    public function pwd_set(){
        $this->assign('header_title','设置密码');
        $this->assign('header_path','/member/modifydata/set_data');
        return $this->fetch();
    }

    /**
     * 登录密码设置（登陆后的设置）
     */
    public function login_set(){

        if(request()->isPost()){
            $member = new MemberService();
            $where = [
                'm_id'=>$this->m_id,
            ];
            $data = input('post.');
            $res = $member->set_login_pwd($where,$data);
            return $res;
        }
        $this->assign('header_title','重置登录密码');
        return $this->fetch();
    }

    /**
     * 设置初始支付密码
     * 邓赛赛
     */
    public function first_payment_pwd(){
        $member = new MemberService();
        $o_id = input('param.o_id') ? input('param.o_id') : '';
        $pg_id = input('param.pg_id') ? input('param.pg_id') : '';
        $where=[
            'm_id'=>$this->m_id
        ];
        $m_payment_pwd = $member->get_info($where,'m_payment_pwd');
        if(!empty($m_payment_pwd['m_payment_pwd'])){
            return $this->error('您已设置支付密码','/member/myhome/index','',2);
        }
        if(request()->isPost()){
            $m_payment_pwd = trim(input('post.m_payment_pwd'));
            $re_payment_pwd = trim(input('post.re_payment_pwd'));

            if(!preg_match('/^[0-9]{6}$/',$m_payment_pwd)){
                return ['stauts'=>0,'msg'=>'支付密码为六位数字组成'];
            }
            if($m_payment_pwd != $re_payment_pwd){
                return ['stauts'=>2,'msg'=>'两次密码不一致'];
            }
            $data = [
                'm_payment_pwd'=>md5($m_payment_pwd)
            ];
            $res = $member->get_save($where,$data);
            if($res){
                return ['status'=>1,'msg'=>'设置支付密码成功','data'=>['o_id'=>$o_id,'pg_id'=>$pg_id]];
            }else{
                return ['status'=>0,'msg'=>'设置支付密码失败'];
            }
        }
        $this->assign('o_id',$o_id);
        $this->assign('pg_id',$pg_id);
        $this->assign('header_title','初始化支付密码');
        return $this->fetch();
    }

    /**
     * 支付密码设置（登陆后的设置）
     * 邓赛赛
     */
    public function payment_set(){
        $member = new MemberService();
        $where = ['m_id'=>$this->m_id];
//        绑定支付是开启此功能
        $info = $member->get_info($where,"m_payment_pwd");
        if($info['m_payment_pwd'] == null){
//           $this->error('未设置支付密码','');
            $this->redirect('modifydata/first_payment_pwd');
        }

        if(request()->isPost()){
            $data = input('post.');
            $res = $member->set_payment_pwd($where,$data);
            return $res;
        }
        $this->assign('header_title','重置支付密码');
        return $this->fetch();
    }
    /**
     * 修改个人资料页面（登陆后的设置）
     * 邓赛赛
     */
    public function self_data(){
        $m_id = $this->m_id;
        $where=[
            'm_id'=>$m_id
        ];
        $member = new MemberService();
        if(request()->isPost()){
            $update=input('post.');
//            $update['m_avatar'] = request()->file('m_avatar');
            $res = $member->set_personal_data($where,$update);
            return $res;
        }

        $field="m_name,m_avatar,m_mobile,m_sex,tj_mid,real_name,m_identification,real_name";
        $info = $member->get_info($where,$field);
        $is_attestation = Db('member_attestation')->where($where)->count();//判断是否已实名认证
        $info['tj_mobile']='';
        if(!empty($info['tj_mid'])){
            $where2 = [
                'm_id'=>$info['tj_mid']
            ];
            $tj_mobile = $member->get_value($where2,'m_mobile');
            $tj_mobile = $member->decrypt($tj_mobile);
            $info['tj_mobile'] = substr_replace($tj_mobile,'****',3,4);
        }
        if(!empty($is_attestation)){
            $info['is_attestation'] = 2;
        }else{
            $info['is_attestation'] = 1;
        }
        $info['m_identification']= empty($info['m_identification']) ? null : substr($info['m_identification'],0,6)."**********".substr($info['m_identification'],-1,1);
        if(empty($info['m_mobile'])){
            $info['m_mobile'] = '未填写';
        }else{
            $member = new MemberService();
            $info['m_mobile']  = $member->decrypt($info['m_mobile']);
            $info['m_mobile2']  = $info['m_mobile'];
            $info['m_mobile'] = substr($info['m_mobile'],0,3)."*****".substr($info['m_mobile'],-4,4);
        }
        $this->assign('header_title','个人资料');
        $this->assign('header_path','/member/modifydata/set_data');
        $this->assign('info',$info);
        return $this->fetch();
    }
    /**
     * 使用与帮助
     * 邓赛赛
     */
    public function use_help(){
        $at = new ArticleTypeService();
        $where= [
            'at_name'   =>'使用与帮助',
            'at_state'  =>0
        ];
        //获取使用与帮助id
        $at_id = $at -> articleTypeInfo($where, 'at_id');

        $list = array();
        if($at_id){
            $where2 = [
                'at_parentid'=>$at_id['at_id'],
                'at_state'  =>0
            ];
            //查出帮助分类的子分类
            $list = $at -> articleTypeList($where2, 'at_id,at_name,at_parentid,at_img');
            $article = new ArticleService();
            foreach($list as $k => $v){
                //在article表中找出此子分类的搜索有子集问题
                $where3 = [
                    'a_state'=>0,
                    'a_type'=>$v['at_id']
                ];
                //简介和ID
                $list[$k]['son'] = $article->articleLimitList($where3,'a_id asc','a_id,a_brief a_name',1,4);
            }
        }
        $this->assign('list',$list);
        $this->assign('header_title','使用与帮助');
        $this->assign('header_path','/member/myhome/index');
        return  view();
    }
    /**
     * （搜索跳转页）使用与帮助
     * 邓赛赛
     */
    public function search_use_help(){
        $a_name = input('param.a_name');
        $list = array();
        //为空是返回空值
        if(trim($a_name)){
            $at = new ArticleTypeService();
            $where= [
                'at_name'   =>'使用与帮助',
                'at_state'  =>0
            ];
            //获取使用与帮助id
            $at_id = $at -> articleTypeInfo($where, 'at_id');

            if($at_id) {
                $where2 = [
                    'at_parentid' => $at_id['at_id'],
                    'at_state' => 0
                ];
                //查出帮助分类的子分类
                $at_id_list = $at->articleTypeColumn($where2, 'at_id');
                $where3 = [
                    'a_type' =>['in',$at_id_list],
                    'a_state' => 0,
                    'a_name'    =>['like','%'.$a_name.'%']
                ];
                $article = new ArticleService();
                $list = $article->articleList($where3,'a_id,a_name');

            }
        }
        $this->assign('list',$list);
        $this->assign('header_title','使用与帮助');
        $this->assign('header_path','/member/modifydata/use_help');
        return  view();
    }


    /**
     * 常见问题
     * 邓赛赛
     */
    public function often_problem(){
        $at_id = input('param.at_id');
        $where = [
            'at_id'=>$at_id,
            'at_state'  =>0
        ];
        $at = new ArticleTypeService();
        $info = $at -> articleTypeInfo($where, 'at_id,at_name');
        if(!$info){
            $this->error('查找错误','/member/modifydata/use_help','',1);
        }
        $article = new ArticleService();
        $where2 = [
            'a_type'    =>$at_id,
            'a_state'   =>0
        ];
        $list = $article->articleList($where2,'a_id,a_name');
//        dump($list);die;
        $this->assign('list',$list);
        $this->assign('header_title',$info['at_name']);
        return  view();
    }
    /**
     * 帮助详情
     * 邓赛赛
     */
    public function help_info(){
        $a_id = input('param.a_id');
        $where = [
            'a_id'      =>$a_id,
            'a_state'   =>0
        ];
        $article = new ArticleService();
        $info = $article->articleInfo($where,'a_description');
        if(!$info){
            $this->error('查找错误','/member/modifydata/use_help','',1);
        }
        $info['a_description'] = htmlspecialchars_decode($info['a_description']);
        $this->assign('info',$info);
        $this->assign('header_title','问题详情');
        return  view();
    }

    /**
     * ajax获取搜索问题标题(联想操作)
     * 邓赛赛
     */
    public function help_title(){
        if(request()->isAjax()){
            $list = array();
            $at = new ArticleTypeService();
            $where= [
                'at_name'   =>'使用与帮助',
                'at_state'  =>0
            ];
            //获取使用与帮助id
            $at_id = $at -> articleTypeInfo($where, 'at_id');
            $where2 = [
                'at_parentid'   => $at_id['at_id'],
                'at_state'      => 0
            ];
            $at_id_list  = $at->articleTypeColumn($where2,'at_id');
            if(!$at_id_list){
                return $list;
            }
            $a_name = input('param.a_name');
            $where3 = [
                'a_state'   =>0,
                'a_type'=>['in',$at_id_list]
            ];
            if($a_name){
                $where3['a_name'] = ['like','%'.$a_name.'%'];
            }
            $article = new ArticleService();
            $list = $article->articleColumn($where3,'a_name');
            return $list;
        }
    }

    /**
     * 我要反馈
     * 邓赛赛
     */
    public function feedback(){
        $this->assign('header_title','我要反馈');
        return view();
    }
    /**
     * 关于拍吖吖
     * 邓赛赛
     */
    public function about(){
        $a_type = new ArticleTypeService();
        $where = [
            'at_name'=>'关于拍吖吖'
        ];
        $at_id = $a_type->articleTypeValue($where,'at_id');
        $article = new ArticleService();
        $where = [
            'a_type'=>$at_id,
            'a_state'=>0
        ];
        $list = $article->articleList($where, $field='a_id,a_name,a_brief,a_addtime,a_description,a_author,a_img');
        $this->assign('list',$list);
        $this->assign('header_title','关于吖吖商城');
        return view();
    }
    /**
     * 查看关于拍吖吖内容
     * 邓赛赛
     */
    public function lookup_content(){
        $a_id = input('param.a_id');
        $article = new ArticleService();
        $where=[
            'a_id'=>$a_id,
        ];
        $info = $article-> articleInfo($where,'a_brief,a_description');
        if(!$info){
            $this->error('未查到您要查看的信息','/member/modifydata/about','',1);
        }
        $info['a_description'] = htmlspecialchars_decode($info['a_description']);
        $this->assign('header_title',$info['a_brief']);
        $this->assign('content',$info['a_description']);
        return view('');
    }

    /**
     * 更改用户推荐手机号码
     * 邓赛赛
     */
    public function updatePhone(){
        $m_id = $this->m_id;
        $base = new BaseService();

        if(\request()->isPost()){
            $m_mobile2 = input('post.m_mobile2');
            if(!$m_id)
            {
                return ['status'=>0,'修改人为空'];
            }

            if(!trim($m_mobile2,''))
            {
                return ['status'=>0,'msg'=>'推荐人为空'];
            }
            $is_set = Db::table('pai_member')->where('m_id',$m_id)->value('tj_mid');
            if($is_set){
                return ['status'=>0,'msg'=>'您已有推荐人'];
            }
            $m_mobile2 = $base->encrypt($m_mobile2);
            $ml_tui_id1_info = Db::table('pai_member')->where('m_id',$m_id)->field('m_id,tj_mid,ml_tui_id')->find();
            $ml_tui_id2_info = Db::table('pai_member')->where('m_mobile',$m_mobile2)->field('m_id,tj_mid,ml_tui_id')->find();

            if(empty($ml_tui_id1_info)){
                return ['status'=>0,'msg'=>'被推荐人非会员'];
            }
            if(empty($ml_tui_id2_info)){
                return ['status'=>0,'msg'=>'推荐人非会员'];
            }
            if($ml_tui_id2_info['m_id'] == $ml_tui_id1_info['m_id']){
                return ['status'=>0,'msg'=>'自己不可推荐自己'];
            }

            if($ml_tui_id2_info['tj_mid'] == $ml_tui_id1_info['m_id']){
                return ['status'=>0,'msg'=>'推荐人的推荐者是此被推荐者，现不可执行您的操作'];
            }

            if(empty($ml_tui_id2_info['ml_tui_id'])){
                if($ml_tui_id2_info['ml_tui_id'] < $ml_tui_id1_info['ml_tui_id']){
                    return ['status'=>0,'msg'=>'推荐人不可比被推荐人等级低'];
                }
            }else{
                if(!empty($ml_tui_id1_info['ml_tui_id'])){
                    if($ml_tui_id2_info['ml_tui_id'] > $ml_tui_id1_info['ml_tui_id']){
                        return ['status'=>0,'msg'=>'推荐人不可比被推荐人等级低'];
                    }
                }
            }
            $res = Db::table('pai_member')->where('m_id',$m_id)->update(['tj_mid'=>$ml_tui_id2_info['m_id'],'edit_time'=>time()]);
            if($res){
                //查看是否有推荐记录
                $is_log = Db::table('pai_invitation_log')->where('m_id',$m_id)->count();
                if(!$is_log) {
                    //无记录时添加记录
                    $insert = [
                        'add_time' => time(),
                        'tj_mid' => $ml_tui_id2_info['m_id'],
                        'm_id' => $m_id,
                        'set_type' => 1
                    ];
                    Db::table('pai_invitation_log')->insert($insert);
                }
                return ['status'=>1,'msg'=>'操作成功'];
            }else{
                return ['status'=>0,'msg'=>'操作失败'];
            }
        }
        $is_tj = Db::table('pai_member')->where('m_id',$m_id)->value('tj_mid');
        $is_tj = $base->decrypt($is_tj);
        $this->assign('is_tj',$is_tj);
        $this->assign('header_title','修改推荐者');
        return view();
    }

    /**
     * 实名认证
     * 邓赛赛
     */
    public function id_check(){
        $m_id = $this->m_id;
        $member_a = new MemberAttestationService();
        $where = [
            'm_id'=>$m_id
        ];
        $info = $member_a->get_info($where,'real_name,identification,birthday,sex');
        if($info){
            $info['sex'] = $info['sex']==1 ? '女' : '男';
            $this_year = date('Y',time());
            $birthday_year = date('Y',$info['birthday']);
            $info['birthday'] = date("Y-m-d",$info['birthday']);
            $info['year'] = $this_year-$birthday_year;
            $name_len = mb_strlen($info['real_name']);
            switch($name_len){
                case 1:
                $info['real_name'] = $info['real_name'].'*';
                break;
                case 2:
                    $info['real_name'] = mb_substr($info['real_name'],0,1).'*';
                    break;
                default:
                    $info['real_name'] = mb_substr($info['real_name'],0,1).'*'.mb_substr($info['real_name'],-1,1);
            }
            $info['identification'] = mb_substr($info['identification'],0,3).'****'.mb_substr($info['identification'],-4,4);
        }
        $this->assign('info',$info);
        $this->assign('header_title','实名认证');
        return view();
    }

}
