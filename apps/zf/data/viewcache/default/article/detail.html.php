<?php $preloadjs=explode(",", "jform.js");$jspath="/public/js/"; ?>
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
<!--[if IE 6]>
<style type="text/css">
/*ie6 fix顶端元素*/
#top{
    *top:expression(eval(document.documentElement.scrollTop));
}
</style>
<![endif]-->
<style>body{
	font-size:62.5%;font-family:Verdana, Geneva, sans-serif, "宋体";padding:0px;margin:0px;padding-top:30px;
	*background-image:url(about:blank);*background-attachment:fixed;/*必要，防抖动*/
}
.clear{clear:both;}
div,ul,li,input,select{margin:0px;padding:0px;}
ul,li{list-style:none;}
#top{
	position:fixed !important;position:absolute;z-index:9999;width:100%;
	height:30px;line-height:30px;background:url(/public/images/topbg.jpg) 0px 0px repeat;top:0px;
}
#topbar{width:980px;margin:0px auto;height:36px;}
#header{height:86px;width:980px;margin:0px auto;border:1px solid #CCC;}
#content{height:386px;width:980px;margin:0px auto;border:1px solid #CCC;}
#bootom{}
#footer{width:980px;margin:10px auto;text-align:center;line-height:30px;border-top:1px dashed #CCC;}


#tagcloud{float:right;background-color:#09C;}


.page_panel_class{height:35px;line-height:35px;margin:0px auto;text-align:center;}
.page_panel_class a{text-decoration:none;color:#666;}
.page_panel_class .page_item{display:inline-block;border:1px solid #C2D5E3;padding:0px 6px;height:22px;line-height:22px;}
.page_panel_class .page_current{background-color:#E5EDF2;font-weight:bolder;}
.page_panel_class .page_first_label{padding-left:15px;background:url(/public/images/arw_l.gif) no-repeat;background-position:0px;}
.page_panel_class .page_last_label{padding-right:15px;background:url(/public/images/arw_r.gif) no-repeat;background-position:55px;}
</style></head>
<body>
<div id="top">
	<div id="topbar">
		<div class="welcome">xxxxxxxxxxxxxxxxxxxxx</div>
		<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>

<div id="header">

</div>

<div id="content">
	<pre>
<?php var_export($detail);?>
	</pre>
</div>

<div id="bootom">
	<div id="footer">
	&copy;
	</div>
</div>
<!-- Baidu Button BEGIN -->
<!--<script type="text/javascript" id="bdshare_js" data="type=slide&amp;mini=1&amp;img=6&amp;pos=left&amp;uid=745084" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
		var bds_config = {"bdTop":120};
		document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
</script>-->
<!-- Baidu Button END -->
</body>
</html>