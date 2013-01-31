<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$pageTitle}</title>
<style>
#content{margin:120px auto;width:500px;height:220px;line-height:28px;text-align:center;}
</style>
<?php if ($goBack) {?>
<script language="javascript">
setTimeout('<?php echo $url;?>', 2000);
</script>
<?php }?>
</head>



<body>

<div id="content">
{$msg}
</div>


</body>

</html>