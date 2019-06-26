<? $this->load_view('min_header'); ?>


<form method="post" enctype="multipart/form-data">
<table class="editTable">
<tbody>
<tr>
	<th width="90">真实姓名：</th><td><input type="text" name="realName" value="<?=(isset($rs['realName']))?$rs['realName']:'' ?>" /></td>
	<th>手机号：</th><td><input type="text" name="phone" value="<?=(isset($rs['phone']))?$rs['phone']:'' ?>" /></td>
</tr>
<tr><th>权限组：</th><td>
	<select name="purviewgroup_id" style="width:98%">
	<option value="">请选择</option>
	<?
	if(!empty($purviewgroup_rs)){
	    foreach ($purviewgroup_rs as $k => $v) {
	      $selected = (isset($rs['purviewgroup_id']) && $rs['purviewgroup_id']==$v['purviewgroup_id'])?'selected="selected"':'';
	      echo '<option '.$selected.' value="'.$v['purviewgroup_id'].'">'.$v['name'].'</option>';
	    }
	}
    ?></select></td>
    <th>状态：</th><td>
		<select name="status">
		<?foreach ($this->status_arr as $k => $v){
			$selected = (!empty($rs) && $rs['status'] == $k)?'selected="selected"':'';
			echo '<option '.$selected.' value="'.$k.'">'.$v.'</option>';
		}?>
		</select>			
	</td>
</tr>

<?if(!empty($rs)){?>
<tr>
	<th>注册时间：</th><td><?=$rs['add_time_str']?></td>
	<th>最后修改日期：</th><td><?=$rs['last_time_str']?></td>
</tr>
<?}?>

<tr><th></th><td colspan="3"><button type="submit" class="buttonBG">保存</button></td></tr>   
</tbody>	
</table>
</form>

<? $this->load_view('min_footer'); ?>