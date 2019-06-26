<? $this->load_view('min_header'); ?>

<form method="post"> 
<table class="editTable">
<tbody>
<tr><th width="100">权限组名称：</th><td><input type="text" name="name" value="<?=(isset($rs['name']))?$rs['name']:'' ?>" /></td></tr>
<tr><th>备注：</th><td><textarea type="text" name="remark"><?=(!empty($rs['remark']))? $rs['remark']:'' ?></textarea></td></tr>
<tr><th>状态：</th><td>
    <select name="status">
      <option value="1" <?if( !empty($rs['status']) && $rs['status']==1){?>selected="selected"<?}?>>启用登录</option>
      <option value="2" <?if(!empty($rs['status']) && $rs['status']==2){?>selected="selected"<?}?> >禁用登录</option>
    </select>
</td></tr>
<tr><th>排序：</th><td><input type="number" name="sorting" value="<?=(isset($rs['sorting']))?$rs['sorting']:'' ?>" /></td></tr>
<tr><th></th><td><button type="submit" class="buttonBG">保存</button></td></tr>
</tbody>
</table>
</form>

<? $this->load_view('min_footer'); ?>