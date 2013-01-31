<preCssStyle>
<style>
body{background-color:#016AA9;}
div{}
#loginblock{margin:50px auto;width:962px;height:501px;background:url(/public/images/admin/loginbg.jpg);border:1px solid #016AA9;}
#loginform{margin:220px auto;width:210px;}
#loginform form{display:inline-block;}
.line{display:inline-block;height:25px;line-height:25px;}
.line div{display:inline-block;height:25px;line-height:25px;}
.input{background-color:#292929;border:solid 1px #7DBAD7;color:#6CD0FF;width:150px;}
.input2{width:50px;}
table{}
td{border:0px;}
</style>
</preCssStyle>
<onloadjs>
<script>
	setInterval('$("#authcodeimg").attr("src", $("#authcodeimg").attr("src"))', 600000);
	$('#form1').submit(function(){
		$.post('/admin.php?do=doLogin', $(this).serializeArray(), function(response){
			if (response.status == 1) {
				//window.location = '/admin.php';
			} else {
				alert(response.msg);
				if (response.status == 2) {
					$("#authcodeimg").attr("src", $("#authcodeimg").attr("src"));
				}
			}
		}, 'json');
		return false;
	});
</script>
</onloadjs>
<include file="header" />

<div id="loginblock">
	<div id="loginform">
        <form id="form1" name="form1" method="post" action="/admin.php?do=doLogin">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50">{$_L['text_username']}</td>
            <td colspan="2">
              <input name="username" type="text" class="input" id="username" />
            </td>
          </tr>
          <tr>
            <td>{$_L['text_password']}</td>
            <td colspan="2">
              <input type="password" name="password" id="password" class="input" />
            </td>
          </tr>
          <tr>
            <td>{$_L['text_authcode']}</td>
            <td><input type="text" name="authcode" id="authcode" class="input input2" /></td>
            <td><img src="/admin.php?do=authcode&amp;sc=admin&amp;l=1" id="authcodeimg" alt="{$_L['text_authcode_title']}" title="{$_L['text_authcode_title']}" width="60" height="22" align="absmiddle" onclick="this.src=this.src+'?r='+Math.random()'" /></td>
          </tr>
          <tr>
            <td height="30" colspan="3" align="center"><input type="image" src="/public/images/admin/login.gif" /></td>
          </tr>
        </table>
        </form>
		<div class="clear"></div>
    </div>
</div>

<include file="footer" />