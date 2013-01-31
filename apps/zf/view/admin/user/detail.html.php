<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加/修改用户信息</title>
<style>
table,td{border:1px solid #CCC;border-collapse:collapse;}
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="?co=user&do=detailSave">
	<table width="100%" border="0" cellpadding="6" cellspacing="0">
		<tr>
			<td width="21%" align="right"><label for="username">用户名</label></td>
			<td width="79%">
			<input type="text" name="username" id="username" /></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td>
			<select name="birth_year" id="birth_year">
				<option value="1970">1970</option>
				<option value="1980">1980</option>
				<option value="1990">1990</option>
				<option value="2000">2000</option>
			</select>
			<select name="birth_month" id="birth_month">
				<option value="1">01</option>
				<option value="2">02</option>
				<option value="3">03</option>
				<option value="4">04</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
			<select name="birth_day" id="birth_day">
				<option value="1">01</option>
				<option value="2">02</option>
				<option value="3">03</option>
				<option value="4">04</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="31">31</option>
			</select>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td><select name="gender" id="gender">
				<option value="0">女</option>
				<option value="1">男</option>
				<option value="2">保密</option>
			</select></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td><select name="status" id="status">
				<option value="0">未激活</option>
				<option value="1">正常</option>
				<option value="2">冻结</option>
			</select></td>
		</tr>
		<tr>
			<td align="right"><label for="qq"></label></td>
			<td>
			<input type="text" name="qq" id="qq" /></td>
		</tr>
		<tr>
			<td align="right"><label for="email"></label></td>
			<td>
			<input type="text" name="email" id="email" /></td>
		</tr>
		<tr>
			<td align="right"><label for="mobile"></label></td>
			<td>
			<input type="text" name="mobile" id="mobile" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" id="button" value="保存" />
			 	&nbsp;
		 	<input type="reset" id="button2" value="重置" /></td>
		</tr>
	</table>
</form>
</body>
</html>
