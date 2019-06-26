<? $this->load_view('header'); ?>

<div class="soubox" id="tools">
<ul>
	<?if($this->HCTL_url(array('purviewgroup','add'))){?>
	<a href="javascript:;" onclick="openWin('<?=url(array('purviewgroup','add'))?>', '600px','400px','添加权限组');" class="right">添加</a>
	<?}?>
	<label>关键词：<input type="text" id="keyword" value="" placeholder="请输入权限组名称关键字"  /></label>
	<label><button type="button" class="buttonBG" onclick="doSearch()">查询</button></label>
</ul>
</div>


<table id="grid" class="easyui-datagrid" style="width:100%;height:100%;"
    idField="id" 
    data-options="
        url:'<?=url(array('purviewgroup', 'index','json'))?>',
        method:'post',
        fitColumns:true,
        rownumbers:true,
        fit:true,
        pagination: true,
        pageSize: 50,
        pageList: [20,30,50,80,100,200,300,400,500],
        striped:true,
        toolbar: '#tools',
        emptyMsg: '无符合条件的记录',
        showFooter:false,
        nowrap:false,
        ">
    <thead>
        <tr>
			<th field="name" align="left" width="200">权限组名称</th>
			<th field="status_str" align="center" width="80">状态</th>
			<th field="sorting" align="center" width="60">排序</th>
			<th field="remark" align="left" width="35%">备注</th>
            <th field="add_time_str" align="center" width="150">添加时间</th>
			<th field="last_time_str" align="center" width="150">最后修改时间</th>
			<?if($this->HCTL_url(array('purviewgroup','purview'))){?>
				<th field="purview_str" align="center" width="100"></th>
			<?}?>
			<?if($this->HCTL_url(array('purviewgroup','edit'))){?>
			<th field="edit_str" align="center" width="50"></th>
			<?}?>
			<?if($this->HCTL_url(array('purviewgroup','del'))){?>
			<th field="del_str" align="center" width="50"></th>
			<?}?>
        </tr>
    </thead>
</table>


<? $this->load_view('footer'); ?>
<script>
function doSearch(){
    $('#grid').datagrid('load',{
        keyword: $('#keyword').val(),
    });
}
function set(id){
	$.post('<?=url(array('purviewgroup', 'setStatus'))?>',
		{
			'id':id,
		}, function(data){
			console.log(data);
			if(data.code == 'Success'){
				$('#status'+id).html(data.item);
			}else{
				alert(data.msg);
			}
		}
	)
}
</script>

