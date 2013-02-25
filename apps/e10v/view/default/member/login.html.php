<onloadjs>
<script>
	setInterval('$("#authcodeimg").attr("src", $("#authcodeimg").attr("src"))', 600000);
	$('#form1').submit(function(){
		$.post('/index.php?co=member&do=dosignup', $(this).serializeArray(), function(response){
			alert(response.msg);
			if (response.status == 1) {
				//window.location = '/admin.php';
			} else {
				if (response.status == 2) {
					$("#authcodeimg").attr("src", $("#authcodeimg").attr("src"));
				}
			}
		}, 'json');
		return false;
	});
</script>
</onloadjs>
<include file="../header" />

<div id="content">
<form id="form1" name="form1" method="post" action="">
  <p>
    <label for="textfield"></label>
    <label for="username">用户名</label>
    <input type="text" name="username" id="username" />
  </p>
  <p>
    <label for="password">密码</label>
    <input type="password" name="password" id="password" />
  </p>
  <p>
    <input type="submit" value="注册" />
  </p>
</form>
</div>

<include file="../footer" />