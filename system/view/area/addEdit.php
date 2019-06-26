<? $this->load_view('min_header'); ?>

<form method="post" enctype="multipart/form-data">
<table class="editTable">
<tbody>
<tr><th width="150">地区名：</th><td><input type="text" name="name" value="<?=(isset($rs['name']))?$rs['name']:'' ?>" /></td></tr>
<tr><th>经纬度：</th><td><input type="text" name="lnglat" value="<?=(isset($rs['lnglat']))?$rs['lnglat']:'' ?>" placeholder="格式为：110.298231,19.939694" /></td></tr>
<tr><th>模型文件：</th><td>
		<?if(!empty($rs['gltf_file'])){?><p><?=$rs['gltf_file'];?></p><?}?>
		<input type="file" name="gltf_file" /></p>	
</td></tr>
<tr><th>模型缩放倍数：</th><td><input type="text" name="gltf_scale" value="<?=(isset($rs['gltf_scale']))?$rs['gltf_scale']:'' ?>" /></td></tr>
<tr><th>模型高度：</th><td><input type="text" name="gltf_height" value="<?=(isset($rs['gltf_height']))?$rs['gltf_height']:'' ?>" /></td></tr>
<tr><th>模型 rotateX：</th><td><input type="text" name="gltf_rotateX" value="<?=(isset($rs['gltf_rotateX']))?$rs['gltf_rotateX']:'' ?>" /></td></tr>
<tr><th>模型 rotateY：</th><td><input type="text" name="gltf_rotateY" value="<?=(isset($rs['gltf_rotateY']))?$rs['gltf_rotateY']:'' ?>" /></td></tr>
<tr><th>模型 rotateZ：</th><td><input type="text" name="gltf_rotateZ" value="<?=(isset($rs['gltf_rotateZ']))?$rs['gltf_rotateZ']:'' ?>" /></td></tr>
<tr><th>模型位置：</th><td><input type="text" name="gltf_lnglat" value="<?=(isset($rs['gltf_lnglat']))?$rs['gltf_lnglat']:'' ?>" placeholder="格式为：110.298231,19.939694" /></td></tr>

<tr><th>模型场景序号：</th><td><input type="number" name="gltf_scene" value="<?=(isset($rs['gltf_scene']))?$rs['gltf_scene']:'' ?>" /></td></tr>
<tr><th>排序：</th><td><input type="number" name="sorting" value="<?=(isset($rs['sorting']))?$rs['sorting']:'' ?>" /></td></tr>
<tr><th></th><td><button type="submit" class="buttonBG">保存</button></td></tr>
</tbody>
</table>
</form>

<? $this->load_view('min_footer'); ?>