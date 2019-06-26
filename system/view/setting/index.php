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
<!--
<tr><th>桌面端版本：</th><td><input type="text" name="soft_version" value="<?=$rs['soft_version']?>"  placeholder="" /></td></tr>
-->
<tr><th>系统简介：</th><td><textarea name="webAbout" style="width:99%; height:400px;"><?=$rs['webAbout']?></textarea></td></tr>

<tr><th>审核流程配置：</th><td style="margin:0;padding:0;">
	<table class="editTable" style="margin:0;padding:0;">
	<tbody>
		<tr><th width="200">说明配置：</th><td class="tip">
			<p>一、定义：<?
			foreach ($this->workflow_user_type_arr as $k => $v){
				echo $k.$v.'，';
			}
			?>
			<p>二、使用：1,4,2表示：<?=$this->workflow_user_type_arr[1]?>-><?=$this->workflow_user_type_arr[4]?>-><?=$this->workflow_user_type_arr[2]?></p>
		</td></tr>
		<tr><th>商品采购审批步骤配置：</th><td><input type="text" name="workflow_config_goods" value="<?=$rs['workflow_config_goods']?>" /></td></tr>
		<tr><th>问卷得积分审批步骤配置：</th><td><input type="text" name="workflow_config_assess" value="<?=$rs['workflow_config_assess']?>" /></td></tr>
		<tr><th>乡镇自定义问卷审批步骤配置：</th><td><input type="text" name="workflow_config_town_assess" value="<?=$rs['workflow_config_town_assess']?>" /></td></tr>
		<tr><th>超市结算申请审批步骤配置：</th><td><input type="text" name="workflow_config_balance" value="<?=$rs['workflow_config_balance']?>" /></td></tr>
	</tbody>
	</table>
	</td></tr>
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