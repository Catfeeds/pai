<?php
namespace app\admin\controller;
use app\admin\controller\AdminHome;
use app\data\service\goods\GoodsService;
use app\data\service\goodsCategory\GoodsCategoryService;
use app\data\service\goodsSpec\GoodsSpecService;
use app\data\service\store\StoreService;
use app\data\service\system_msg\SystemMsgService;
use think\Db;
use think\Request;
use think\Url;
use think\Response;

class Goods extends AdminHome
{

    /**
     * 商品列表
	 * 创建人 邓赛赛
	 * 时间 2017-09-10 21:29:11
     */
        public function goodslist()
	{
	   $goods =  new GoodsService();
//	   $data = $goods->encrypt('abcde108');
//	   dump($data);die;
	   $data = input('get.');
        $data['g_state'] = empty($data['g_state']) ? 2 : $data['g_state'];
        $data['is_heat'] = empty($data['is_heat']) ? '' : $data['is_heat'];
        $where = [
	       'g_state'=>$data['g_state']
       ];
        if($data['is_heat']){
            $where['g.is_heat']= ['>',0];
            $where['g_state'] = 6;
            $data['g_state'] = 6;
        }
        $field = 'g.*,p.gp_market_price,p.gp_settlement_price,p.is_huodong,p.is_fudai';
        $list = $goods->member_goods_list($where,$field,'g_addtime desc');
//        dump($list);
        $page = $list->render();
        $total = $list->total();

//        dump($array_list);die;
        $num = empty(input('get.page')) ? 1 : input('get.page');    //当前页
        $goods_spec = new GoodsSpecService();               //商品分类
        $spec = $goods_spec->goodsSpecList(['gs_state'=>0]);
        $spec = array_column($spec,'gs_name','gs_id');
        $lists = array();
        //循环替换地区
        foreach($list as $k => $v){
            $address_id = [
                'pid'=>$v['pid'],
                'cid'=>$v['cid'],
                'aid'=>$v['aid'],
            ];
            $v['shop_address'] = $goods->id_tfm_address($address_id);
            $lists[$k] = $v;
        }
        $array_list = array();
        foreach($lists as $k => $v){
            $array_list[$k] = $v;
            if($v['g_id']){
                $array_list[$k]['limit_list'] = Db::table('pai_goods_dt_record gdr')
                    ->where('gdr.g_id',$v['g_id'])
                    ->join('pai_goods_discounttype dt','gdr.gdt_id = dt.gdt_id','left')
                    ->field('gdr.gdr_id,gdr.gdr_limited,dt.gdt_name')
                    ->select();
            }
        }

        $this->assign([
            'list'=>$array_list,
            'page'=>$page,
            'total'=>$total,
            'g_state'=>$data['g_state'],
            'num'=>$num,
            'spec'=>$spec,
            'is_heat'=>$data['is_heat'],
        ]);
	   return $this->fetch();

	}

    /**
     * 商品详情
     * 邓赛赛
     */
	public function goods_info(){
        $g_id = input('param.g_id');
        $goods = new GoodsService();
        $where = [
            'g.g_id'=>$g_id
        ];
        $info = $goods->get_goods_info($where,$field="g.*,gp.*,m.m_id,m.m_name");
        $address_id = [
            'pid'=>$info['pid'],
            'cid'=>$info['cid'],
            'aid'=>$info['aid'],
        ];
        $info['shop_address'] = $goods->id_tfm_address($address_id);

        $goods_spec = new GoodsSpecService();               //商品分类
        $spec = $goods_spec->goodsSpecList(['gs_state'=>0]);
        $spec = array_column($spec,'gs_name','gs_id');
        $this->assign('goods_info',$info);
        $this->assign('spec',$spec);
        return view();
    }

    /**
     * 审核商品
     * 邓赛赛
     */ 
    public function set_state(){
        $input = input('param.');
        $where['g_id']      = $input['g_id'];
        $data['g_state']    = $input['g_state'];
        $data['g_des']      = empty($input['g_des']) ? '' : $input['g_des'];
        $gdr_info           = empty($input['gdr_info']) ? [] : $input['gdr_info'];

        $data['g_audittime']= time();
        $data['is_tj']      = input('is_tj',1);
        $page               = input('num',1);
        $goods = new GoodsService();
        $info = $goods->get_goods_info(['g.g_id'=>$where['g_id']],'g.g_id,g.g_img,g.g_name,g.g_mid,g.g_starttime,g.g_endtime,gp.gp_settlement_price');
        if(!$info["g_starttime"]){
            if((time()-$info['g_endtime']) <= 86400*3){
               $this->error("开始时间和结束时间必须相隔三天",'/admin/goods/goodslist?page='.$page,'',2);
            }
            $data['g_starttime'] = time();
        }

        //修改
        if(array_filter($gdr_info)){
            foreach($gdr_info as $v){
                $gdr_id = empty($v[0]) ? '' : $v[0];
                $gdr_limited = empty($v[1]) ? '' : $v[1];

                if($gdr_id && $gdr_limited){
                    Db::table('pai_goods_dt_record')->where('gdr_id',$gdr_id)->update(['gdr_limited'=>$gdr_limited]);
                }
            }
        }
        $res = $goods->get_save($where,$data);
        $system = new SystemMsgService();
        if($res){
            //添加系统消息
            $data2 = [
                'sm_addtime'=>time(),
                'sm_title'=>'您有一件商品审核通知',
                'sm_brief'=>$info['g_name'],
                'sm_img'=>$info['g_img'],
                'g_id'=>$info['g_id'],
                'to_mid'=>$info['g_mid'],
                'sm_display'=>3
            ];
            $system->AddSystemMsg($data2);

            $this->success("修改成功",'/admin/goods/goodslist?page='.$page,'',1);
        }else{
            $data2 = [
                'sm_addtime'=>time(),
                'sm_title'=>'您有一件商品审核通知',
                'sm_brief'=>$info['g_name'],
                'sm_img'=>$info['g_img'],
                'g_id'=>$info['g_id'],
                'sm_content'=>input('get.g_des'),
                'to_mid'=>$info['g_mid'],
                'sm_display'=>3,
                'is_success'=>1
            ];
            $system->AddSystemMsg($data2);

            $this->error("修改失败",'/admin/goods/goodslist?page='.$page,'',1);
        }
    }
    /**
     * 设置热拍
     * 邓赛赛
     */
    public function set_heat(){
        $data['is_heat'] = input('get.is_heat') ? input('get.is_heat') : 0;
        $page = input('get.num');
        $g_id = input('get.g_id');
        $goods = new GoodsService();
        $where = [
            'g_id'=> $g_id,
        ];
        $res = $goods->get_save($where,$data);
        if($res){
            $this->success("修改成功",'/admin/goods/goodslist?page='.$page,'',1);
        }else{
            $this->error("修改失败",'/admin/goods/goodslist?page='.$page,'',1);
        }
    }


    /**
     * 商品分类
	 * 创建人 韦丽明
	 * 时间 2017-09-10 21:29:11
     */
	public function gclass() 
	{
		parent::userauthHtml(137);
		if ($this->request->isAjax())
		{
    		$gclass = new \app\data\service\goods\GclassService();
    		$volist = $gclass->gclassListShow();
			//dump($volist);die;
            return Response::create($volist, 'json');
		}
		return $this->fetch();
	}

    /**
     * 添加商品分类页面
	 * 创建人 韦丽明
	 * 时间 2017-09-10 21:50:05
     */	
	public function gclass_add() 
	{
		parent::userauthHtml(139);
		//商品分类列表
    	$volist = parent::gclassAll();
		$this->assign('volist',$volist);
		return $this->fetch();
	}

    /**
     * 添加商品分类处理
	 * 创建人 韦丽明
	 * 时间 2017-09-10 22:06:08
     */	
	public function gclass_add_do() 
	{
		parent::userauthHtml(139);
		$request = Request::instance();
		if (request()->isPost()) 
		{			
			$gclass = new \app\data\service\goods\GclassService();
			$result = $gclass->gclassRoomAdd();
			//自动创建并验证数据
			if ($result) 
			{
				parent::operating($request->path(),0,'新增成功');
				$this->success('添加成功',url('goods/gclass'),3);
			}
			else 
			{
				parent::operating($request->path(),1,'新增失败');
				$this->error('新增失败');
			}
		}
		else 
		{
			parent::operating($request->path(),1,'非法请求');
			$this->error('非法请求');
		}
	}
	
    /**
     * 更新商品分类页面
	 * 创建人 韦丽明
	 * 时间 2017-09-10 22:11:10
     */	
	public function gclass_edit() 
	{
		parent::userauthHtml(138);
		$gclass = new \app\data\service\goods\GclassService();
		$arr = $gclass->gclassRoomEdit();
		if ($arr) 
		{			
			$this->assign('result',$arr['result']);
			$this->assign('volist',$arr['volist']);
			$this->assign('liegth',$arr['liegth']);	
			return $this->fetch();
		}
		else 
		{
			parent::operating(request()->path(),1,'数据不存在');
			$this->error('没有找到相关数据');
		}
	}
	
    /**
     * 修改处理商品分类
	 * 创建人 韦丽明
	 * 时间 2017-09-11 13:42:14
     */
	public function gclass_edit_do() 
	{
		parent::userauthHtml(138);
		if (request()->isPost()) 
		{				
			$gclass = new \app\data\service\goods\GclassService();
			$updata = $gclass->gclassRoomEditDoo();					
			if ($updata) 
			{
				parent::operating(request()->path(),0,'更新成功');
				$this->success('修改成功',url('goods/gclass'),3);
			}
			else 
			{
				parent::operating(request()->path(),1,'更新失败');
				$this->error('更新失败');
			}
		}
		else 
		{
			parent::operating(request()->path(),1,'非法请求');
			$this->error('非法请求');
		}
	}

    /**
     * 删除商品分类
	 * 创建人 韦丽明
	 * 时间 2017-09-11 14:16:05
     */	
	public function gclass_del() 
	{
		//验证用户权限
		parent::userauth(140);
		//判断是否是ajax请求
		if (request()->isAjax()) 
		{
			$gclass = new \app\data\service\goods\GclassService();
			$del = $gclass->gclassRoomDel();	
			if ($del)
			{
				parent::operating(request()->path(),0,'删除成功');
				return array('s'=>'ok');
			}
			else
			{
				parent::operating(request()->path(),1,'删除失败');
				return array('s'=>'删除失败');
			}
		}
		else 
		{
			parent::operating(request()->path(),1,'非法请求');
			return array('s'=>'非法请求');
		}
	}
	
    /**
     * 批量删除商品分类
	 * 创建人 韦丽明
	 * 时间 2017-09-11 14:36:55
     */		
	public function gclass_indel() 
	{
		//验证用户权限
		parent::userauth(140);
		if (request()->isAjax()) {
			if (!$delid=explode(',',input('post.delid',''))) 
			{
				return array('s'=>'请选中后再删除');
			}
			$gclass = new \app\data\service\goods\GclassService();
			$delMost = $gclass->gclassRoomDelMost();
			if ($delMost) 
			{
				parent::operating(request()->path(),0,'删除成功');
				return array('s'=>'ok');
			}
			else 
			{
				parent::operating(request()->path(),1,'删除失败');
				return array('s'=>'删除失败');
			}
		}
		else 
		{
			parent::operating(request()->path(),1,'非法请求');
			return array('s'=>'非法请求');
		}
	}
	
    /**
     * 添加商品页面
	 * 创建人 韦丽明
	 * 时间 2017-09-11 14:42:18
     */
	public function add() 
	{
		parent::userauthHtml(143);
		//商品分类列表
    	$volist = parent::gclassAll();
		$this->assign('volist',$volist);
		return $this->fetch();
	}
	
    /**
     * 添加商品处理
	 * 创建人 韦丽明
	 * 时间 2017-09-11 18:05:55
     */	
	public function add_do() 
	{
		parent::userauthHtml(143);
		$request = Request::instance();
		if (request()->isPost()) 
		{
			$goods = new \app\data\service\goods\GoodsService();
			$result = $goods->goodsRoomAdd();
			//自动创建并验证数据
			if ($result) 
			{
				parent::operating($request->path(),0,'新增成功');
				$this->success('添加成功',url('goods/index'),3);
			}
			else 
			{
				parent::operating($request->path(),1,'新增失败');
				$this->error('新增失败');
			}
		}
		else 
		{
			parent::operating($request->path(),1,'非法请求');
			$this->error('非法请求');
		}
	}
	
	
    /**
     * 修改商品页面
	 * 创建人 韦丽明
	 * 时间 2017-09-11 18:21:58
     */	
	public function edit() 
	{
		parent::userauthHtml(145);
		$goods = new \app\data\service\goods\GoodsService();
		$arr = $goods->goodsRoomEdit();
		if ($arr) 
		{			
			$this->assign('organizers',$arr['result']['organizers']);
			$this->assign('result',$arr['result']);
			$this->assign('volist',$arr['volist']);
			$this->assign('liegth',$arr['liegth']);	
			return $this->fetch();
		}
		else 
		{
			parent::operating(request()->path(),1,'数据不存在');
			$this->error('没有找到相关数据');
		}
	}

    /**
     * 修改处理商品
	 * 创建人 韦丽明
	 * 时间 2017-09-11 18:21:58
     */	
	public function edit_do() 
	{
		parent::userauthHtml(145);		
		if (request()->isPost()) 
		{			
			$goods = new \app\data\service\goods\GoodsService();
			$updata = $goods->goodsRoomEditDoo();					
			if ($updata) 
			{
				//删除原图片
				$goods->goodsDelImg();
				parent::operating(request()->path(),0,'更新成功');
				$this->success('修改成功',url('goods/index'),3);
			}
			else 
			{
				parent::operating(request()->path(),1,'更新失败');
				$this->error('更新失败');
			}
		}
		else 
		{
			parent::operating($request->path(),1,'非法请求');
			$this->error('非法请求');
		}
	}
	
    /**
     * 删除商品
	 * 创建人 韦丽明
	 * 时间 2017-09-11 18:21:58
     */		
	public function del() 
	{		
		//验证用户权限
		parent::userauth(144);
		//判断是否是ajax请求
		if (request()->isAjax()) 
		{
			$goods = new \app\data\service\goods\GoodsService();
			$del = $goods->goodsRoomDel();	
			if ($del)
			{	
				//删除原图片
				$goods->goodsDelImg();				
				parent::operating(request()->path(),0,'删除成功');
				return array('s'=>'ok');
			}
			else
			{
				parent::operating(request()->path(),1,'删除失败');
				return array('s'=>'删除失败');
			}
		}
		else 
		{
			parent::operating(request()->path(),1,'非法请求');
			return array('s'=>'非法请求');
		}
	}
	
    /**
     * 批量删除商品
	 * 创建人 韦丽明
	 * 时间 2017-09-11 21:38:55
     */	
	public function indel() 
	{
		//验证用户权限
		parent::userauth(144);
		if (request()->isAjax()) 
		{
			if (!$delid=explode(',',input('post.delid',''))) 
			{
				return array('s'=>'请选中后再删除');
			}
			$goods = new \app\data\service\goods\GoodsService();
			$delMost = $goods->goodsRoomDelMost();
			if ($delMost) 
			{
				parent::operating(request()->path(),0,'删除成功');
				return array('s'=>'ok');
			}
			else 
			{
				parent::operating(request()->path(),1,'删除失败');
				return array('s'=>'删除失败');
			}
		}else {
			parent::operating(request()->path(),1,'非法请求');
			return array('s'=>'非法请求');
		}
	}
	
	//修改处理
	public function deleteimage() 
	{
	    parent::userauthHtml(144);
	    if (request()->isPost()) 
		{
	        $data = array();
			$where = array();
	        $key = input('post.key');
			if($key)
			{
				$id = explode('|',$key)[0];
				$i = explode('|',$key)[1];
				$where ['goods_id'] = $id;
				$data['goods_image'.$i] = "";
				$data['minpic'.$i] = "";
			}

	        $goods = new \app\data\service\goods\GoodsService();
			$save = $goods->goodsSave($where, $data);
	        if ($save) 
			{
	            parent::operating(request()->path(),0,'图片删除成功');
	            $this->success('修改成功',url('goods/index'),3);
	        }
			else 
			{
	            parent::operating(request()->path(),1,'图片删除失败');
	            $this->error('图片删除失败');
	        }
	    }
		else 
		{
	        parent::operating(request()->path(),1,'非法请求');
	        $this->error('非法请求');
	    }
	}

	
	/**
	*excel导出
	*/
	public function goods_excel($list, $name) {
		
		$fields = array('序号','货号','商品名称','价格','条码','主图地址','缩列图地址','描述','下载地址','录入时间');
		$i = 0 ;			
			foreach ($list as $k=>$vv) {
				$row[$i]['i'] = $i;			
				$row[$i]['id'] = $vv['id'];
				$row[$i]['goods_sn'] = $vv['goods_sn'];
				$row[$i]['goods_name'] = $vv['goods_name'];
				$row[$i]['price'] = $vv['price'];
				$row[$i]['barcode'] = $vv['barcode'];
				$row[$i]['goods_image'] = $vv['goods_image'];
				$row[$i]['minpic1'] = $vv['minpic1'];
				$row[$i]['description'] = $vv['description'];
				$row[$i]['download'] = $vv['download'];
				$row[$i]['create_time'] = date("Y-m-d H:i:s",$vv['create_time']);				
				$i++;				
			}
		$excel = parent::export($row, $fields, $name);
		//dump($excel);die;	
	}
}
