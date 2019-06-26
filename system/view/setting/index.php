<? $this->load_view('header'); ?>

<form method="post" enctype="multipart/form-data">
<table class="editTable">
<tbody>
<tr><th width="100">系统名称：</th><td><input type="text" name="webName" value="<?=$rs['webName']?>"  placeholder="" /></td></tr>
<tr><th>系统网址：</th><td><input type="text" name="webSize" value="<?=$rs['webSize']?>"  placeholder="" /></td></tr>
<tr><th>系统关键字：</th><td><input type="text" name="keywords" value="<?=$rs['keywords']?>"  placeholder="" /></td></tr>
<tr><th>系统描述：</th><td><input type="text" name="description" value="<?=$rs['description']?>"  placeholder="" /></td></tr>
<tr><th>系统备案号：</th><td><input type="text" name="webIcp" value="<?=$rs['webIcp']?>"  placeholder="" /></td></tr>

<tr><th>系统简述：</th><td><input type="text" name="webInfo" value="<?=$rs['webInfo']?>"  placeholder="" /></td></tr>
<tr><th>联系邮箱：</th><td><input type="text" name="webEmail" value="<?=$rs['webEmail']?>"  placeholder="" /></td></tr>
<tr><th>联系电话：</th><td><input type="text" name="webTel" value="<?=$rs['webTel']?>"  placeholder="" /></td></tr>
<tr><th>服务地址：</th><td><input type="text" name="webAddr" value="<?=$rs['webAddr']?>"  placeholder="" /></td></tr>


<tr><th>系统简介：</th><td><textarea name="webAbout" style="width:99%; height:400px;"><?=$rs['webAbout']?></textarea></td></tr>
<tr><th></th><td><button type="submit" class="buttonBG">保存</button></td></tr>
</tbody>
</table>
</form>


<? $this->load_view('footer'); ?>
<?$this->load_js(array('kindeditor/kindeditor'  )); ?>
<script type="text/javascript">
var editor;
KindEditor.ready(function(K) {
	K.create('textarea[name="webAbout"]',{
		urlType:'domain',
		uploadpath: '/home/ajaxupload/about/',
	});
});
</script>