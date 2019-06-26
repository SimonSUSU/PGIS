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
<body<?=(Router::$s_controller == 'desktop' && Router::$s_method == 'index') ? ' class="desktopBG"' : ''?>>
<div class="header">
	<ol class="clearfix">
		<a href="javascript:;" xonclick="openWin('<?=url(array('desktop','my'))?>', '600px','430px','我的档案');"><?=$this->user_arr['realName']?><span><?=$this->user_arr['purviewgroup_name'];?></span></a>
	</ol>
	<a href="<?=url(array('desktop','index'))?>" class="logo"><?=$this->SYSTEM_CONFIG['webName']?></a>
	<dl>
		<?
		$menu_num = 0;
		$side_menu = (Router::$s_controller == 'desktop') ? 'desktop' :'';
		if($this->HCTL_url(array('desktop','index'))){
			$menu_num++;
			$this->CTL_url(array('desktop','index'), '办公桌面', ($side_menu == 'desktop') ? 'class="act"' : '');
		}
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'map'
			) ? 'map' : $side_menu;
		if($this->HCTL_url(array('map','index'))){
			$menu_num++;
			$this->CTL_url(array('map','index'), '管控区域', ($side_menu == 'map') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('balance','index'))){
			$menu_num++;
			$this->CTL_url(array('map','index'), '管控区域', ($side_menu == 'map') ? 'class="act"' : '');
		}
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
		*/
		/////////////////////////////////////////////
		$side_menu = (
			Router::$s_controller == 'setting' ||
			Router::$s_controller == 'area' ||
			Router::$s_controller == 'purviewgroup' ||
			Router::$s_controller == 'purview' ||
			Router::$s_controller == 'user' ||
			//Router::$s_controller == 'hot' ||
			Router::$s_controller == 'store'
			) ? 'system' : $side_menu;
		if($this->HCTL_url(array('store','index'))){
			$menu_num++;
			$this->CTL_url(array('store','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('area','index'))){
			$menu_num++;
			$this->CTL_url(array('area','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('user','index'))){
			$menu_num++;
			$this->CTL_url(array('user','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('hot','index'))){
			$menu_num++;
			$this->CTL_url(array('hot','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('setting','index'))){
			$menu_num++;
			$this->CTL_url(array('setting','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}

		elseif($this->HCTL_url(array('purviewgroup','index'))){
			$menu_num++;
			$this->CTL_url(array('purviewgroup','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}elseif($this->HCTL_url(array('purview','index'))){
			$menu_num++;
			$this->CTL_url(array('purview','index'), '基础系统', ($side_menu == 'system') ? 'class="act"' : '');
		}
		?>
	</dl>
</div>
<div class="webMain" <?=(Router::$s_controller == 'desktop' || Router::$s_controller == 'change') ?'style="margin-left:0"':''?> >



<?if(!empty($side_menu)){?>
<div class="webSide">
  <div class="Menu">
  	<ul>
		<?
		switch ($side_menu) {
			case 'desktop':
				echo '<style>html{overflow-y:hidden;} .webSide{display:none;} .webMain{margin-left:0}</style>';
				//$this->CTL_url(array('index','index'), '<i class="fa fa-tachometer fa-fw"></i>后台桌面', (Router::$s_controller == 'index') ? 'class="act"' : '');
			break;


			case 'map':
				$this->CTL_url(array('map','index'), '<i class="fa fa-life-ring fa-fw"></i>海南特勤局', (Router::$s_controller == 'map' && Router::$s_method == 'index') ? 'class="act"' : '');
				$this->CTL_url(array('map','hotel'), '<i class="fa fa-life-ring fa-fw"></i>海口喜来登酒店', (Router::$s_controller == 'map' && Router::$s_method == 'hotel') ? 'class="act"' : '');
			break;



			case 'balance':
				$this->CTL_url(array('order','index'), '<i class="fa fa-life-ring fa-fw"></i>兑换明细', (Router::$s_controller == 'order') ? 'class="act"' : '');
				$this->CTL_url(array('balance','index'), '<i class="fa fa-life-ring fa-fw"></i>兑换结算', (Router::$s_controller == 'balance') ? 'class="act"' : '');
			break;

			case 'villager':
				$this->CTL_url(array('villager','index'), '<i class="fa fa-paper-plane fa-fw"></i>帮扶采集', (Router::$s_controller == 'villager' && Router::$s_method == 'index') ? 'class="act"' : '');
				$this->CTL_url(array('villager','import'), '<i class="fa fa-trophy fa-fw"></i>批量导入', (Router::$s_controller == 'villager' && Router::$s_method == 'import') ? 'class="act"' : '');
				//$this->CTL_url(array('villager','xls'), '<i class="fa fa-paper-plane fa-fw"></i>导入分析', (Router::$s_controller == 'villager' && Router::$s_method == 'xls') ? 'class="act"' : '');
			break;

			case 'assess':				
				//$this->CTL_url(array('assess','user'), '<i class="fa fa-building fa-fw"></i>问卷作答', (Router::$s_controller == 'assess' && Router::$s_method == 'user') ? 'class="act"' : '');
				if($this->HCTL_url(array('assess','user'))){
					foreach ($this->assess_category_arr as $k => $v) {
						$act = (!empty($category_id) && $k==$category_id && Router::$s_controller == 'assess' && Router::$s_method == 'user')?' class="act"' : '';
						echo '<a '.$act.' href="'.url(array('assess','user',$k)).'">'.$v.'评分</a>';
					}
				}
				if($this->HCTL_url(array('assess','config'))){
					foreach ($this->assess_category_arr as $k => $v) {
						$act = (!empty($category_id) && $k==$category_id && Router::$s_controller == 'assess' && Router::$s_method == 'config')?' class="act"' : '';
						echo '<a '.$act.' href="'.url(array('assess','config',$k)).'">'.$v.'评分配置</a>';
					}
				}

				if(!empty($this->user_arr['is_towncadre']) && $this->user_arr['is_towncadre'] ==2){ //乡镇工作人员
					if($this->HCTL_url(array('assess','towncadre_config'))){
						foreach ($this->assess_category_arr as $k => $v) {
							$act = (!empty($category_id) && $k==$category_id && Router::$s_controller == 'assess' && Router::$s_method == 'towncadre_config')?' class="act"' : '';
							echo '<a '.$act.' href="'.url(array('assess','towncadre_config',$k)).'">'.$v.'评分乡镇配置</a>';
						}
					}
				}
			break;

			case 'points':
				$this->CTL_url(array('points','index'), '<i class="fa fa-file fa-fw"></i>积分明细', (Router::$s_controller == 'points' && Router::$s_method == 'index') ? 'class="act"' : '');
				$this->CTL_url(array('points','import'), '<i class="fa fa-trophy fa-fw"></i>积分导入', (Router::$s_controller == 'points' && Router::$s_method == 'import') ? 'class="act"' : '');
			break;



			case 'workflow':
				$this->CTL_url(array('workflow','goods'), '商品采购审批', (Router::$s_controller == 'workflow'  && Router::$s_method == 'goods') ? 'class="act"' : '');
				$this->CTL_url(array('workflow','assess'), '问卷评分审批', (Router::$s_controller == 'workflow'  && Router::$s_method == 'assess') ? 'class="act"' : '');
				$this->CTL_url(array('workflow','town_assess'), '乡镇出题审批', (Router::$s_controller == 'workflow'  && Router::$s_method == 'town_assess') ? 'class="act"' : '');
				//$this->CTL_url(array('workflow','balance'), '兑换结算审批', (Router::$s_controller == 'workflow'  && Router::$s_method == 'balance') ? 'class="act"' : '');
			break;


			case 'report':
				$this->CTL_url(array('report','progress'), '进度报表', (Router::$s_controller == 'report' && Router::$s_method == 'progress') ? 'class="act"' : '');
				$this->CTL_url(array('report','points'), '积分报表', (Router::$s_controller == 'report' && Router::$s_method == 'points') ? 'class="act"' : '');
				if($this->HCTL_url(array('report','assess'))){
					foreach ($this->assess_category_arr as $k => $v) {
						$act = (!empty($category_id) && $k==$category_id && Router::$s_controller == 'report' && Router::$s_method == 'assess')?' class="act"' : '';
						echo '<a '.$act.' href="'.url(array('report','assess',$k)).'">'.$v.'评分公示表</a>';
					}
				}
				//$this->CTL_url(array('report','index'), '兑换登记统计表', (Router::$s_controller == 'report') ? 'class="act"' : '');
				//$this->CTL_url(array('report','index'), '货物采购明细表', (Router::$s_controller == 'xxxx') ? 'class="act"' : '');
				//$this->CTL_url(array('report','index'), '月度积分兑换统计表', (Router::$s_controller == 'xxxx') ? 'class="act"' : '');
			break;

			case 'help':
				$this->CTL_url(array('ask','index'), '<i class="fa fa-life-ring fa-fw"></i>在线问答', (Router::$s_controller == 'ask') ? 'class="act"' : '');
				$this->CTL_url(array('help','index'), '<i class="fa fa-life-ring fa-fw"></i>帮助文档', (Router::$s_controller == 'help') ? 'class="act"' : '');
			break;

			case 'log':
				$this->CTL_url(array('log','index'), '系统登录日志', (Router::$s_method == 'login') ? 'class="act"' : '');
				$this->CTL_url(array('log','qcode'), '验证码发送日志', (Router::$s_method == 'qcode') ? 'class="act"' : '');
				$this->CTL_url(array('log','access'), '系统访问日志', (Router::$s_method == 'access') ? 'class="act"' : '');
				$this->CTL_url(array('log','wxmsg'), '微信消息日志', (Router::$s_method == 'wxmsg') ? 'class="act"' : '');
				$this->CTL_url(array('log','postlog'), '系统操作日志', (Router::$s_method == 'postlog') ? 'class="act"' : '');
							
			break;

			case 'system':
				$this->CTL_url(array('store','index'), '<i class="fa fa-shopping-cart fa-fw"></i>超市管理', (Router::$s_controller == 'store') ? 'class="act"' : '');
				$this->CTL_url(array('user','index'), '<i class="fa fa-user fa-fw"></i>用户管理', (Router::$s_controller == 'user') ? 'class="act"' : '');
				//$this->CTL_url(array('hot','index'), '<i class="fa fa-adn fa-fw"></i>推荐管理', (Router::$s_controller == 'hot') ? 'class="act"' : '');
				$this->CTL_url(array('purviewgroup','index'), '<i class="fa fa-group fa-fw"></i>权限组管理', (Router::$s_controller == 'purviewgroup') ? 'class="act"' : '');
				$this->CTL_url(array('purview','index'), '<i class="fa fa-cogs fa-fw"></i>权限节点管理', (Router::$s_controller == 'purview') ? 'class="act"' : '');
				$this->CTL_url(array('area','index'), '<i class="fa fa-sitemap fa-fw"></i>区域管理', (Router::$s_controller == 'area') ? 'class="act"' : '');
				$this->CTL_url(array('setting','index'), '<i class="fa fa-gear fa-fw"></i>系统设置', (Router::$s_controller == 'setting') ? 'class="act"' : '');
				if($this->HCTL_url(array('setting','flushMemcache')) && $this->user_id ==1 ){
					echo '<a href="'.url(array('setting','flushMemcache')).'" onclick="return confirm(\'确定要清理缓存？\');"><i class="fa fa-refresh fa-fw"></i>清理缓存</a>';
				}

			break;

			default:
			# code...
			break;
		}
		?>
		</ul>
  </div>
</div>
<?}?>
