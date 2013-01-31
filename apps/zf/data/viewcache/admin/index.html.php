<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php if(isset($title)){echo $title;}?></title>

<?php if (isset($preloadcss) && is_array($preloadcss)) foreach($preloadcss as $v) {echo '<link href="'.$csspath.$v.'" rel="stylesheet" type="text/css" />';}?>
<script type="text/javascript" src="/public/js/global.min.js"></script>
<script type="text/javascript">
$(function(){
	
});
</script>
<?php  if (isset($preloadjs) && is_array($preloadjs)) foreach($preloadjs as $v) {echo '<script type="text/javascript" src="'.$jspath.$v.'"></script>';}?>
<style></style></head>

<body>


</body>
</html>