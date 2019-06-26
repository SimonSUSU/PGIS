<? $this->load_view('min_header'); ?>

<form method="post">
<table class="editTable">
<tbody>
	<tr><th width="100">上级节点：</th><td><select name="pid" class="easyui-combobox" style="height:30px; width:61%"><option value="0">顶级节点</option><?=$select;?></select></td></tr>
	<tr><th>名称：</th><td><input type="text" name="name" value="<?=!empty($rs['name'])?$rs['name']:''?>" /></td></tr>
	<tr><th>路径：</th><td><input type="text" name="url" value="<?=!empty($rs['url'])?$rs['url']:$path?>" /></td></tr>
	<tr><th>排序：</th><td><input type="number" name="sorting" value="<?=!empty($rs['sorting'])?$rs['sorting']:0?>"/></td></tr>

	<?if( !empty($pid_rs) && $pid_rs['is_system']==1 ){ /*父级是系统级的*/?>
	<tr><th></th><td>当前父级是系统级，请注意，下级也要是系统级，否则下级无法继承</td></tr>
	<?}?>

	<tr><th>系统级：</th><td><label><input type="checkbox" <? if( isset($rs['is_system']) && $rs['is_system']==1){echo "checked";}?> name="is_system" value="1">是（勾选后所有用户都拥有此权限，并在企业权限分配中不显示）</label></td></tr>
	<tr><th>继承上级：</th><td><label><input type="checkbox" <? if(isset($rs['status']) && $rs['status']==1){echo "checked";}?> name="status" value="1">是（勾选后，此权限节点在权限选择中不显示；是否被分派，同上级）</label></td></tr>
	<tr><th></th><td><button type="submit" class="buttonBG">保存</button></td></tr>
</tbody>
</table>
</form>

<? $this->load_view('min_footer'); ?>