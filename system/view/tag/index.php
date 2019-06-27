<? $this->load_view('header'); ?>

<div class="soubox" id="tools">
<ul>
    <?if($this->HCTL_url(array('tag','add'))){?>
	<a href="javascript:;" onclick="openWin('<?=url(array('tag','add'))?>', '90%','90%','添加');" class="right">添加</a>
    <?}?>
    <span>发布时间：<input type="text" id="start_date" size="15" readonly class="Wdate" onfocus="WdatePicker({doubleCalendar:true,onpicked:function(){end_date.focus();},minDate:'#{%y-10}-%M-%d',maxDate:'%y-%M-%d'})"  value="" />至<input type="text" id="end_date" size="15" readonly class="Wdate" onfocus="WdatePicker({doubleCalendar:true,minDate:'#F{$dp.$D(\'start_date\',{d:0})}',maxDate:'%y-%M-%d'})" value=""/></span>
	<label>关键词：<input type="text" id="keyword" value="" placeholder="请输入标题关键字"  /></label>
	<label><button type="button" class="buttonBG" onclick="doSearch()">查询</button></label>
</ul>
</div>

<table id="grid" class="easyui-datagrid" style="width:100%;height:100%;"
    idField="id" 
    data-options="
        url:'<?=url(array('tag', 'index','json'))?>',
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
			<th field="pic" align="center" width="50">图标</th>
            <th field="name" align="left" width="60%">标签名</th>
            <th field="sorting" align="center" width="50">排序</th>
            <th field="add_time_str" align="center" width="150">创建时间</th>
			<th field="last_time_str" align="center" width="150">最后修改时间</th>
            <?if($this->HCTL_url(array('tag','edit'))){?>
                <th field="edit_str" align="center" width="50">修改</th>
            <?}?>
            <?if($this->HCTL_url(array('tag','del'))){?>
                <th field="del_str" align="center" width="50">操作</th>
            <?}?>
        </tr>
    </thead>
</table>

<? $this->load_view('footer'); ?>
<script>
function doSearch(){
    $('#grid').datagrid('load',{
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        keyword: $('#keyword').val(),
    });
}
</script>