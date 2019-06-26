<? $this->load_view('min_header'); ?>

<form method="post" id="test">
<?
if(!empty($rs)){
	foreach ($rs as $k=>$v){
		$select = in_array($v['purview_id'], $rsArr) ? 'checked' : '';
		echo '<div class="purView">
		<h2>';
		if(isset($v['children'])){
			echo '<input type="button" class="buttonBG right" name="btnCheckedAllForDiv" value="全选" onclick="javascript:CheckAllForDiv(this,'.$v['purview_id'].');" />';
		}
		echo '<label title="权限节点路径：'.$v['url'].'"><input id="'.$v['purview_id'].'" name="p[]" type="checkbox" value="'.$v['purview_id'].'" '.$select.' />'.$v['name'].'（'.$v['url'].'）</label>
		</h2>';
		if(isset($v['children'])){
			echo '<ul id="d'.$v['purview_id'].'">';
			foreach ($v['children'] as $k1=>$v1){
				if($v1['status']==2){
					$select = in_array($v1['purview_id'], $rsArr) ? 'checked' : '';		
					echo '<h3><label title="权限节点路径：'.$v1['url'].'"><input name="p[]" type="checkbox" value="'.$v1['purview_id'].'" '.$select.' />'.$v1['name'].'（'.$v1['url'].'）</label></h3>';
					if(isset($v1['children'])){
						echo '<ol>';
						foreach ($v1['children'] as $k2=>$v2){
							if($v2['status']==2){
								$select = in_array($v2['purview_id'], $rsArr) ? 'checked' : '';
								echo '<label title="权限节点路径：'.$v2['url'].'"><input name="p[]" type="checkbox" value="'.$v2['purview_id'].'" '.$select.' />'.$v2['name'].'（'.$v2['url'].'）</label>';
							}
						}
						echo '<div class="c"></div></ol>';
					}
				}
			}
			echo '</ul>';
		}
		echo '</div>';
	}
}
?>
<table class="listTable">
<tbody>
<tr>
	<td width="80"><input type="button" name="btnCheckedRev" value="全选" onclick="javascript:CheckedRev();" /></td>
	<td colspan="2"><button type="submit" name="submit" class="buttonBG">提交</button></td>
</tr>
</tbody>
</table>
</form>

<? $this->load_view('min_footer'); ?>
<script type="text/javascript"> 
function CheckedRev(){ 
	var arr = $(':checkbox'); 
	for(var i=0;i<arr.length;i++){ 
		arr[i].checked = ! arr[i].checked; 
	} 
} 
</script> 
<script>
function CheckAllForDiv(btnstr,id){
	var divbox = document.getElementById("d"+id);
	var controls = divbox.getElementsByTagName('input');
	if(btnstr.value=='全选'){
		btnstr.value='取消全选';
		document.getElementById(id).checked = true;
		for(var i=0; i<controls.length; i++){
			controls[i].checked = true;
			//controls[i].checked = ! controls[i].checked; 
			//alert(controls[i].value);		
		}
	}
	else{
		btnstr.value='全选';
		document.getElementById(id).checked = false;
		for(var i=0; i<controls.length; i++){
			controls[i].checked = false;
		}
	}
}
</script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
</body> 
</html>