<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script type="text/javascript" src="/public/js/global.min.js"></script>
<script type="text/javascript">
$(function(){
});
</script>

</head>

<body>
<form id="form1" name="form1" enctype="multipart/form-data" method="post" action="/admin.php?a=article/save">
	<input type="hidden" name="MAX_FILE_SIZE" value="<?php if(isset($MAX_FILE_SIZE)){echo $MAX_FILE_SIZE;}?>" />
	<input type="hidden" name="id" value="<?php if(isset($detail['id'])){echo $detail['id'];}?>" />
	<table width="100%" border="1">
		<tr>
			<td><label for="cid">分类</label></td>
			<td>
				<select name="cid" id="cid">
				<?php if (is_array($cidList)) foreach ($cidList as $cid=>$name) { ?>
					<option value="<?php if(isset($cid)){echo $cid;}?>"<?php if(!empty($detail['cid']) && $detail['cid']==$cid) { ?> selected="selected"<?php }?>><?php if(isset($name)){echo $name;}?></option>
				<?php } ?>
				</select>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><label for="status">状态</label></td>
			<td>
				<select name="status" id="status">
				<?php if (is_array($statusList)) foreach ($statusList as $status=>$name) { ?>
					<option value="<?php if(isset($status)){echo $status;}?>"<?php if(!empty($detail['status']) && $detail['status']==$status) { ?> selected="selected"<?php }?>><?php if(isset($name)){echo $name;}?></option>
				<?php } ?>
				</select>
			</td>
			<td>&nbsp;</td>
		<tr>
			<td>标题图片</td>
			<td><input type="file" name="title_image" id="title_image" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><label for="title">标题</label></td>
			<td>
			<input type="text" name="title" id="title" size="50" value="<?php if(isset($detail['title'])){echo $detail['title'];}?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><label for="views">浏览数</label></td>
			<td>
			<input type="text" name="views" id="views" value="<?php if(isset($detail['views'])){echo $detail['views'];}?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><label for="post_time">发布时间</label></td>
			<td>
			<input type="text" name="post_time" id="post_time" value="<?php if(isset($detail['post_time'])){echo $detail['post_time'];}?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><label for="title">关键字</label></td>
			<td>
			<input type="text" name="keywords" id="keywords" value="<?php if(isset($detail['keywords'])){echo $detail['keywords'];}?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><label for="brief">简介</label></td>
			<td>
			<textarea name="brief" id="brief" cols="45" rows="5"><?php if(isset($detail['brief'])){echo $detail['brief'];}?></textarea>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><label for="content">内容</label></td>
			<td>
			<textarea name="content" id="content" cols="85" rows="15"><?php if(isset($detail['content'])){echo $detail['content'];}?></textarea>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" id="button2" value="提交" />
				<input type="reset" id="button3" value="重置" />
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</form>
</body>
</html>