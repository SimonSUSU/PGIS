<? $this->load_view('min_header'); ?>

<form method="post" enctype="multipart/form-data">
<table class="editTable">
<tbody>
<tr><th width="150">标签名：</th><td><input type="text" name="name" value="<?=(isset($rs['name']))?$rs['name']:'' ?>" /></td></tr>
<tr><th>标签图标：</th><td>
		<?if(!empty($rs['pic'])){?><p><img src="<?=$rs['pic'];?>" height="50" /></p><?}?>
		<input type="file" name="pic" /></p>	
</td></tr>
<tr><th>排序：</th><td><input type="number" name="sorting" value="<?=(isset($rs['sorting']))?$rs['sorting']:'' ?>" /></td></tr>
<tr><th></th><td><button type="submit" class="buttonBG">保存</button></td></tr>
</tbody>
</table>
</form>

<? $this->load_view('min_footer'); ?>