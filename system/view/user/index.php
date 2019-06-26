<? $this->load_view('header'); ?>

<div class="soubox" id="tools">
<ul>
  <?if($this->HCTL_url(array('user','add'))){?>
  <a href="javascript:;" onclick="openWin('<?=url(array('user','add'))?>', '800px','90%','添加');" class="right">添加</a>
  <?}?>
  <span>
    <select id="timetype" style="width:125px">
      <option value="add_time">注册时间：</option>
      <option value="last_login_time">最后登录时间：</option>
    </select>
    <input type="text" class="Wdate" id="start_date" value="" onfocus="WdatePicker({doubleCalendar:true,onpicked:function(){end_date.focus();},minDate:'%y-%M-#{%d-3650}',maxDate:'%y-%M-%d'})" />至<input type="text" class="Wdate" id="end_date" value="" onfocus="WdatePicker({doubleCalendar:true,minDate:'#F{$dp.$D(\'start_date\',{d:0})}',maxDate:'%y-%M-%d'})" /></span>
  <label><select id="sex">
      <option value="">性别</option>
      <?foreach ($this->sex_arr as $k => $v){
        echo '<option value="'.$k.'">'.$v.'</option>';
      }?></select></label>
	<label><select id="status">
			<option value="">状态</option>
			<?foreach ($this->status_arr as $k => $v){
				if($k==3){continue;}
				echo '<option value="'.$k.'">'.$v.'</option>';
			}?></select></label>
  <label><select id="purviewgroup_id">
    <option value="">权限组</option>
    <?
    if(!empty($purviewgroup_rs)){
        foreach ($purviewgroup_rs as $k => $v) {
          $selected = (isset($rs['purviewgroup_id']) && $rs['purviewgroup_id']==$v['purviewgroup_id'])?'selected="selected"':'';
          echo '<option '.$selected.' value="'.$v['purviewgroup_id'].'">'.$v['name'].'</option>';
        }
    }
    ?></select></label>
	<label><input type="text" id="keyword" value="" placeholder="关键词：姓名/手机" /></label>
	<label><button type="button" class="buttonBG" onclick="doSearch()">查询</button></label>
</ul>
</div>

<table id="grid" class="easyui-datagrid" style="width:100%;height:100%;"
    idField="id"
    data-options="
        url:'<?=url(array('user', 'index','json'))?>',
        method:'post',
        fitColumns:false,
        rownumbers:true,
        fit:true,
        pagination: true,
        pageSize: 50,
        pageList: [20,30,50,80,100,200,300,400,500],
        striped:true,
        toolbar: '#tools',
        emptyMsg: '无符合条件的记录',
        showFooter:false,
        ">
    <thead frozen="true">
    <tr>
      <th field="realName" align="center" width="90">真实姓名</th>
      <th field="phone" align="center" width="90">手机号</th>
      <th field="status_str" align="center" width="50" data-options="sortable:true">状态</th>
    </tr>
    </thead>
    <thead>
    <tr>    
      <th field="points_total" align="center" width="60" data-options="sortable:true">积分</th>
      <th field="purviewgroup_id" align="left" width="100" data-options="sortable:true">所属权限组</th>

      <th field="add_time_str" align="center" width="130" data-options="sortable:true">注册时间</th>
      <th field="last_time_str" align="center" width="130" data-options="sortable:true">最后修改日期</th>
      <th field="last_login_time_str" align="center" width="130" data-options="sortable:true">最后登录时间</th>

    </tr>
    </thead>
</table>



<? $this->load_view('footer'); ?>
<script>
function doSearch(){
    $('#grid').datagrid('load',{
      timetype: $('#timetype').val(),
      start_date: $('#start_date').val(),
      end_date: $('#end_date').val(),

      sex: $('#sex').val(),
      status: $('#status').val(),
      purviewgroup_id: $('#purviewgroup_id').val(),
      


      keyword: $('#keyword').val(),
    });
}


function set_field(user_id,field){
	$.post('<?=url(array('user', 'set_field'))?>',
		{
			'user_id':user_id,
      'field':field,
		}, function(data){
			if(data.code == 'Success'){
        layer.msg('设为'+data.item+'成功');
				$('#'+field+user_id).html(data.item);
			}else{
				layer.msg('设为'+data.item+'失败');
			}
		}
	)
}
</script>