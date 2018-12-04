<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Url;
use app\data\service\BaseService as BaseService;
use app\data\service\activity\ActivityService;
use RedisCache\RedisCache;
class Activity extends AdminHome
{
    /*
    * 文章列表
    * 创建人 刘勇豪
    * 时间 2018-06-26 13:40:00
    */
    public function index()
    {
        $admin_id = $this->admin_id;
        $status = input('param.status', 0);
        $a_name = input('param.a_name/s', '');
        $a_code = input('param.a_code/s', '');

        $activity = new ActivityService();
        $where = '';
        switch ($status)
        {
            case 1:
                $where['a.a_start_time'] = ['lt',time()];
                $where['a.a_end_time'] = ['gt',time()];
                $where['a.a_state'] = 1;
                break;
            case 2:
                $where['a.a_end_time'] = ['lt',time()];
                break;
            case 3:
                $where['a.a_start_time'] = ['gt',time()];
                break;
        }

        if($a_name){
            $where['a.a_name'] = ['like',"%".$a_name."%"];
        }

        if($a_name){
            $where['a.a_code'] = ['like',"%".$a_code."%"];
        }

        $order= 'a.a_id desc';
        $field = "*";
        $list = $activity->getActivityPaginate($where, $field, $order,5);
        $page = $list->render();
        $total = $list->total();

        $list = $list->toArray();
        $list = $list['data'];
        //dump($list);
        //die;

        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('status', $status);
        $this->assign('a_name', $a_name);
        $this->assign('a_code', $a_code);
        return $this->fetch();
    }

    /*
    * 活动编辑
    * 创建人 刘勇豪
    * 时间 2018-06-26 13:40:00
    */
    public function edit()
    {
        $a_id = input('param.a_id', 0);
        $activity = new ActivityService();

        //查询活动详情
        $info = [];
        if ($a_id) {
            $where_find['a_id'] = $a_id;
            $info = $activity->activityInfo($where_find);
            if (empty($info)) {
                $this->error("非法请求，活动不存在！", url('activity/index'), 3);
            }
            $info['a_description'] = htmlspecialchars_decode($info['a_description']);
        }
        //dump($info);die;

        $this->assign('info', $info);
        $this->assign('a_id', $a_id);
        return $this->fetch();
    }

    /**
     * 编辑提交数据处理
     * 刘勇豪
     */
    public function edit_post(){
        $data = input('post.');
        $admin_id = $this->admin_id;
        //dump($data);die;

        $activity = new ActivityService();

        $call_back = $activity->activity_edit($data,$admin_id);

        return $call_back;
    }

    /**
     * 删除活动轮播图
     * 刘勇豪
     */
    public function delete_banner(){
        $ab_id = input('param.ab_id/d', 0);
        $activity = new ActivityService();
        $call_back = $activity->delete_banner($ab_id);

        return $call_back;
    }

    /**
     * 活动关闭
     * 刘勇豪
     */
    public function activity_close(){
        $a_id = input('param.a_id/d', 0);

        $activity = new ActivityService();
        $call_back = $activity->activity_close($a_id);

        return $call_back;
    }

    /**
     * 活动开启
     * 刘勇豪
     */
    public function activity_open(){
        $a_id = input('param.a_id/d', 0);

        $activity = new ActivityService();
        $call_back = $activity->activity_open($a_id);

        return $call_back;
    }

    /**
     * 活动商品管理
     * 刘勇豪
     */
    public function goods_admin(){
        $a_id = input('param.a_id', 0);
        $ag_type = input('param.ag_type', 0);
        $g_name = input('param.g_name/s', '');
        $gp_sn = input('param.gp_sn/s', '');
        $stroe_name = input('param.stroe_name/s', '');
        $type = input('param.type/d', 0);

        $activity = new ActivityService();

        // 活动详情
        $info = [];
        $where_find['a_id'] = $a_id;
        $info = $activity->activityInfo($where_find);
        if (empty($info)) {
            $this->error("非法请求，活动不存在！", url('activity/index'), 3);
        }

        // 商品列表
        $where = '';
        $where['a.a_id'] = $a_id;
        $where['ag.ag_type'] = $ag_type;

        // 搜索
        if($g_name){
            // 商品名称
            $where['g.g_name']  = array('like', '%'.$g_name.'%');
        }
        if($gp_sn){
            // 商品编号
            $where['gp.gp_sn']  = array('like', '%'.$gp_sn.'%');
        }
        if($stroe_name){
            // 店铺名称
            $where['s.stroe_name']  = array('like', '%'.$stroe_name.'%');
        }
        switch ($type)
        {
            case 1:
                $where['g.g_typeid'] = 1;
                break;
            case 2:
                $where['g.g_typeid'] = 2;
                break;
            case 3:
                $where['g.g_typeid'] = 3;
                break;
            case 4:
                $where['gp.play_type'] = 1;
                break;
            case 5:
                $where['gp.play_type'] = 2;
                break;
            case 6:
                $where['gp.play_type'] = 3;
                break;
        }

        $order= 'ag.ag_sort asc';
        $field = "*";
        $list = $activity->getActivitygoodsPaginate($where, $field, $order,5);
        $page = $list->render();
        $total = $list->total();
        $list = $list->toArray();
        $list = $list['data'];

        if(!empty($list)){
            foreach($list as $k=>$v){
                $min_goods_price = $activity->min_goods_price($v['g_id']);
                $max_goods_price = $activity->max_goods_price($v['g_id']);
                $list[$k]['min_goods_price'] = $min_goods_price;
                $list[$k]['max_goods_price'] = $max_goods_price;
            }
        }
//        dump($list);
//        die;

        $this->assign('info', $info);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('a_id', $a_id);
        $this->assign('ag_type', $ag_type);

        // 搜索内容
        $this->assign('g_name', $g_name);
        $this->assign('gp_sn', $gp_sn);
        $this->assign('stroe_name', $stroe_name);
        $this->assign('type', $type);
        return $this->fetch();
    }

    /**
     * 活动商品列表
     * 刘勇豪
     */
    public function goods_list(){
        $a_id = input('param.a_id', 0);
        $ag_type = input('param.ag_type', 0);
        $g_name = input('param.g_name/s', '');
        $gp_sn = input('param.gp_sn/s', '');
        $stroe_name = input('param.stroe_name/s', '');
        $type = input('param.type/d', 0);

        $activity = new ActivityService();

        // 活动详情
        $info = [];
        $where_find['a_id'] = $a_id;
        $info = $activity->activityInfo($where_find);
        if (empty($info)) {
            $this->error("非法请求，活动不存在！", url('activity/index'), 3);
        }

        // 活动时间
        $a_start_time = $info['a_start_time'];
        $a_end_time = $info['a_end_time'];

        // 商品列表
        $where = '';
        $where['g.g_starttime'] = ['lt',$a_end_time];
        $where['g.g_endtime'] = ['gt',$a_start_time];
        $where['g.g_state'] = 6;//参拍中

        // 搜索
        if($g_name){
            // 商品名称
            $where['g.g_name']  = array('like', '%'.$g_name.'%');
        }
        if($gp_sn){
            // 商品编号
            $where['gp.gp_sn']  = array('like', '%'.$gp_sn.'%');
        }
        if($stroe_name){
            // 店铺名称
            $where['s.stroe_name']  = array('like', '%'.$stroe_name.'%');
        }
        switch ($type)
        {
            case 1:
                $where['g.g_typeid'] = 1;
                break;
            case 2:
                $where['g.g_typeid'] = 2;
                break;
            case 3:
                $where['g.g_typeid'] = 3;
                break;
            case 4:
                $where['gp.play_type'] = 1;
                break;
            case 5:
                $where['gp.play_type'] = 2;
                break;
            case 6:
                $where['gp.play_type'] = 3;
                break;
        }

        // 过滤已选过的商品
        $selected_goods = $activity->activity_goods_list(['a_id'=>$a_id], 'ag_gid');
        if(!empty($selected_goods)){
            $selected_goods = array_column($selected_goods, 'ag_gid');
            $ids_str = implode(",",$selected_goods);
            $where['g.g_id'] = ['not in',$ids_str];
        }

        $order= 'g.g_id desc';
        $field = "*";
        $list = $activity->getGoodsPaginate($where, $field, $order,5);
        $page = $list->render();
        $total = $list->total();
        $list = $list->toArray();
        $list = $list['data'];
        if(!empty($list)){
            foreach($list as $k=>$v){
                $min_goods_price = $activity->min_goods_price($v['g_id']);
                $max_goods_price = $activity->max_goods_price($v['g_id']);
                $list[$k]['min_goods_price'] = $min_goods_price;
                $list[$k]['max_goods_price'] = $max_goods_price;
            }
        }


        $this->assign('info', $info);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('a_id', $a_id);
        $this->assign('ag_type', $ag_type);

        // 搜索内容
        $this->assign('g_name', $g_name);
        $this->assign('gp_sn', $gp_sn);
        $this->assign('stroe_name', $stroe_name);
        $this->assign('type', $type);
        return $this->fetch();
    }

    /**
     * 商品添加到活动中
     * 刘勇豪
     * @return mixed
     */
    public function add_goods(){
        $admin_id = $this->admin_id;
        $a_id = input('param.a_id', 0);
        $ag_type = input('param.ag_type', 0);
        $g_ids = input('param.g_ids', '');

        $activity = new ActivityService();
        $call_back = $activity->add_goods($a_id,$ag_type,$g_ids,$admin_id);

        return $call_back;
    }

    /**
     * 活动中剔除商品
     * 刘勇豪
     * @return mixed
     */
    public function del_goods(){
        $a_id = input('param.a_id', 0);
        $g_id = input('param.g_id', 0);

        $activity = new ActivityService();
        $call_back = $activity->del_goods($a_id,$g_id);

        return $call_back;
    }

    /**
     * 修改活动产品的广告图片
     * 刘勇豪
     */
    public function update_ads_banner(){
        $ag_id = input('param.ag_id/d', 0);
        $ag_banner = input('param.ag_banner', '');

        $activity = new ActivityService();
        $call_back = $activity->update_ads_banner($ag_id,$ag_banner);

        return $call_back;
    }

    /**
     * 活动商品设置排序
     * 刘勇豪
     * @return array
     */
    public function set_sort(){
        $ag_id = input('param.ag_id/d', 0);
        $ag_sort = input('param.ag_sort', 0);

        $activity = new ActivityService();

        $call_back = $activity->update_ag_sort($ag_id,$ag_sort);

        return $call_back;
    }
}
