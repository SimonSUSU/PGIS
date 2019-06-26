<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?=!empty($webTitle)? $webTitle:$this->SYSTEM_CONFIG['webName']?></title>
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
<meta name="renderer" content="webkit">
<? $this->load_css(array('font-awesome','default/easyui','admin')); ?>
</head>
<body<?=(Router::$s_controller == 'map') ? ' onLoad="mapInit()"' : ''?>>

<div class="menuWrap">
	<ul>
		<?
		if($this->HCTL_url(array('desktop','index'))){
			echo '<ol>';
			//echo '<dl class="my">';
			//echo '<h1><a href="'.url(array('desktop','index')).'">'.$this->SYSTEM_CONFIG['webName'].'</a></h1>';
			//echo '<h6>'.$this->SYSTEM_CONFIG['webName'].'</h6>';
			//echo '<p>'.$this->user_arr['purviewgroup_name'].'</p>';
			//echo '</dl>';
			$this->CTL_url(array('desktop','index'), '<i class="fa fa-home fa-fw"></i>办公桌面', (Router::$s_controller == 'desktop')?'class="act"':'' );
			echo '</ol>';
		}
		/////////////////////////////////////////////

		if($this->HCTL_url(array('map','index'))){
			echo '<ol>';
			echo '<dl>';
			$this->area_Obj = $this->load_class_wz('areaClass');
			$rs = $this->area_Obj->lists(array(),'area_id,name');
			if($rs['code']=='Success'){
				foreach ($rs['item'] as $k => $v){
					$act = (!empty($area_id) && $v['area_id']==$area_id) ? 'class="act"':'';
					echo '<a '.$act.' href="'.url(array('map','index',$v['area_id'])).'">'.$v['name'].'</a>';
				}
			}
			echo '</dl>';

			$act = (Router::$s_controller == 'map')?'class="act"':'';
			echo '<a '.$act.' href="'.url(array('map','index')).'"><i class="fa fa-life-ring fa-fw"></i>管控区域</a>';
			echo '</ol>';
		}
		
		if($this->HCTL_url(array('area','index')) || $this->HCTL_url(array('user','index')) || $this->HCTL_url(array('purviewgroup','index')) ){
			echo '<ol>';
			echo '<dl>';	
			$this->CTL_url(array('area','index'), '区域管理', (Router::$s_controller == 'area') ? 'class="act"' : '');
			$this->CTL_url(array('user','index'), '用户管理', (Router::$s_controller == 'user') ? 'class="act"' : '');
			$this->CTL_url(array('purviewgroup','index'), '权限组管理', (Router::$s_controller == 'purviewgroup') ? 'class="act"' : '');
			$this->CTL_url(array('purview','index'), '权限节点管理', (Router::$s_controller == 'purview') ? 'class="act"' : '');
			$this->CTL_url(array('setting','index'), '系统设置', (Router::$s_controller == 'setting') ? 'class="act"' : '');
			if($this->HCTL_url(array('setting','flushMemcache')) && $this->user_id ==1 ){
				echo '<a href="'.url(array('setting','flushMemcache')).'" onclick="return confirm(\'确定要清理缓存？\');">清理缓存</a>';
			}
			echo '</dl>';

			$side_menu = (
				Router::$s_controller == 'setting' ||
				Router::$s_controller == 'area' ||
				Router::$s_controller == 'purviewgroup' ||
				Router::$s_controller == 'purview' ||
				Router::$s_controller == 'user'
				) ? 'system' : '';		
			if($this->HCTL_url(array('area','index'))){
				$this->CTL_url(array('area','index'), '<i class="fa fa-gear fa-fw"></i>基础系统', ($side_menu == 'system') ? 'class="act"' : '');
			}elseif($this->HCTL_url(array('user','index'))){
				$this->CTL_url(array('user','index'), '<i class="fa fa-gear fa-fw"></i>基础系统', ($side_menu == 'system') ? 'class="act"' : '');
			}elseif($this->HCTL_url(array('setting','index'))){
				$this->CTL_url(array('setting','index'), '<i class="fa fa-gear fa-fw"></i>基础系统', ($side_menu == 'system') ? 'class="act"' : '');
			}elseif($this->HCTL_url(array('purviewgroup','index'))){
				$this->CTL_url(array('purviewgroup','index'), '<i class="fa fa-gear fa-fw"></i>基础系统', ($side_menu == 'system') ? 'class="act"' : '');
			}elseif($this->HCTL_url(array('purview','index'))){
				$this->CTL_url(array('purview','index'), '<i class="fa fa-gear fa-fw"></i>基础系统', ($side_menu == 'system') ? 'class="act"' : '');
			}
			echo '</ol>';
		}


		echo '<ol class="footer">';
		echo '<a href="javascript:;" onclick="openWin(\''.url(array('desktop','my')).'\', \'600px\',\'430px\',\'我的档案\');"><i class="fa fa-user fa-fw"></i>我的</a>';
		echo '<a href="'.url(array('home','logout')).'" onclick="return confirm(\'确定退出系统？\');"><i class="fa fa-refresh fa-fw"></i>退出系统</a>';
		echo '</ol>';


	


		/*
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'goods'
			) ? 'goods' : $side_menu;
		if($this->HCTL_url(array('goods','index'))){
			$menu_num++;
			$this->CTL_url(array('goods','index'), '商品系统', ($side_menu == 'goods') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('goods','apply'))){
			$menu_num++;
			$this->CTL_url(array('goods','apply'), '商品系统', ($side_menu == 'goods') ? 'class="act"' : '');
		}
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'villager'
			) ? 'villager' : $side_menu;
		if($this->HCTL_url(array('villager','index'))){
			$menu_num++;
			$this->CTL_url(array('villager','index'), '帮扶系统', ($side_menu == 'villager') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('villager','import'))){
			$menu_num++;
			$this->CTL_url(array('villager','import'), '积分系统', ($side_menu == 'villager') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('villager','xls'))){
			$menu_num++;
			$this->CTL_url(array('villager','xls'), '帮扶系统', ($side_menu == 'villager') ? 'class="act"' : '');
		}
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'assess'
			) ? 'assess' : $side_menu;
		if($this->HCTL_url(array('assess','index'))){
			$menu_num++;
			$this->CTL_url(array('assess','index'), '评分系统', ($side_menu == 'assess') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('assess','user'))){
			$menu_num++;
			$this->CTL_url(array('assess','user'), '评分系统', ($side_menu == 'assess') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('assess','config'))){
			$menu_num++;
			$this->CTL_url(array('assess','config'), '评分系统', ($side_menu == 'assess') ? 'class="act"' : '');
		}elseif(!empty($this->user_arr['is_towncadre']) && $this->user_arr['is_towncadre'] ==2){ //乡镇工作人员
			if($this->HCTL_url(array('assess','towncadre_config'))){
				$menu_num++;
				$this->CTL_url(array('assess','towncadre_config'), '评分系统', ($side_menu == 'assess') ? 'class="act"' : '');
			}
		}

		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'points'
			) ? 'points' : $side_menu;
		if($this->HCTL_url(array('points','index'))){
			$menu_num++;
			$this->CTL_url(array('points','index'), '积分系统', ($side_menu == 'points') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('points','import'))){
			$menu_num++;
			$this->CTL_url(array('points','import'), '积分系统', ($side_menu == 'points') ? 'class="act"' : '');
		}
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'workflow'
			) ? 'workflow' : $side_menu;
		if($this->HCTL_url(array('workflow','goods'))){
			$menu_num++;
			$this->CTL_url(array('workflow','goods'), '审批系统', ($side_menu == 'workflow') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('workflow','assess'))){
			$menu_num++;
			$this->CTL_url(array('workflow','assess'), '审批系统', ($side_menu == 'workflow') ? 'class="act"' : '');
		}//elseif($this->HCTL_url(array('workflow','balance'))){
		//	$menu_num++;
		//	$this->CTL_url(array('workflow','balance'), '审批系统', ($side_menu == 'workflow') ? 'class="act"' : '');
		//}
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'report'
			) ? 'report' : $side_menu;
		if($this->HCTL_url(array('report','index'))){
			$menu_num++;
			$this->CTL_url(array('report','index'), '统计报表', ($side_menu == 'report') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('report','assess'))){
			$menu_num++;
			$this->CTL_url(array('report','assess'), '统计报表', ($side_menu == 'report') ? 'class="act"' : '');
		}
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'help' ||
			Router::$s_controller == 'ask'
			) ? 'help' : $side_menu;
		if($this->HCTL_url(array('ask','index'))){
			$menu_num++;
			$this->CTL_url(array('ask','index'), '帮助系统', ($side_menu == 'help') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('help','index'))){
			$menu_num++;
			$this->CTL_url(array('help','index'), '帮助系统', ($side_menu == 'help') ? 'class="act"' : '');
		}
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'log'
			) ? 'log' : $side_menu;
		if($this->HCTL_url(array('log','index'))){
			$menu_num++;
			$this->CTL_url(array('log','index'), '日志系统', ($side_menu == 'log') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('log','login'))){
			$menu_num++;
			$this->CTL_url(array('log','login'), '日志系统', ($side_menu == 'log') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('log','qcode'))){
			$menu_num++;
			$this->CTL_url(array('log','qcode'), '日志系统', ($side_menu == 'log') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('log','access'))){
			$menu_num++;
			$this->CTL_url(array('log','access'), '日志系统', ($side_menu == 'log') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('log','wxmsg'))){
			$menu_num++;
			$this->CTL_url(array('log','wxmsg'), '日志系统', ($side_menu == 'log') ? 'class="act"' : '');
		}		
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'setting' ||
			Router::$s_controller == 'area' ||
			Router::$s_controller == 'purviewgroup' ||
			Router::$s_controller == 'purview' ||
			Router::$s_controller == 'user'
			) ? 'system' : $side_menu;
		if($this->HCTL_url(array('area','index'))){
			$menu_num++;
			$this->CTL_url(array('area','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('user','index'))){
			$menu_num++;
			$this->CTL_url(array('user','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('setting','index'))){
			$menu_num++;
			$this->CTL_url(array('setting','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('purviewgroup','index'))){
			$menu_num++;
			$this->CTL_url(array('purviewgroup','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('purview','index'))){
			$menu_num++;
			$this->CTL_url(array('purview','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}
		*/
		?>
	</ul>
</div>

<div class="webMain">




