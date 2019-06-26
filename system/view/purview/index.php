<? $this->load_view('header'); ?>
<div class="soubox" id="tools">
<h3 style="border:0">
	<?if($this->HCTL_url(array('purview','add')) && $this->user_id <=2 ){?>
	<a href="javascript:;" onclick="openWin('<?=url(array('purview','add'))?>', '600px','320px','添加节点');" class="right">添加</a>
	<?}?>
	权限节点管理
</h3>
</div>

<table id="grid" class="easyui-treegrid" style="width:100%;height:100%;"
			data-options="
                url: '<?=url(array('purview', 'index','json'))?>',
                method: 'get',
                fitColumns:true,
                rownumbers: true,
                idField: 'purview_id',
                treeField: 'name',
                toolbar: '#tools',
                pagination: true,
        		pageList: [5000],
            ">
    <thead>
     <tr>
		<th field="purview_id" align="center" width="50">id</th>	
		<th field="name" align="left" width="30%">节点名称</th>	
		<th field="url" align="left" width="30%">节点路径</th>
		<th field="sorting" align="center" width="100">排序</th>
		<th field="is_system_str" align="center" width="100">系统级</th>
		<th field="status_str" align="center" width="100">继承上级</th>
		<? if($this->user_id <=2){ ?>
		<th field="add_str" align="center" width="100"></th>
		<th field="edit_str" align="center" width="100"></th>
		<th field="del_str" align="center" width="100"></th>
		<?}?>
    </tr>
    </thead>
</table>


<? $this->load_view('footer'); ?>