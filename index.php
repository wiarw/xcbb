<?php
require_once ('config.php');
require_once('./PhoneCaptcha.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员注册</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="js/timeFormat.js" type="text/javascript"></script>
<script language="javascript">
function chk(theForm){
	if (theForm.member_user.value.replace(/(^\s*)|(\s*$)/g, "") == ""){
		alert("帐号不能为空！");
		theForm.member_user.focus();   
		return (false);   
	}		
	
	if (theForm.member_password.value.replace(/(^\s*)|(\s*$)/g, "") == ""){
		alert("密码不能为空！");
		theForm.member_password.focus();   
		return (false);   
	}	
	
	if (theForm.member_password.value != theForm.pass.value){
		alert("两次输入密码不一样！");
		theForm.pass.focus();   
		return (false);   
	}	
		 
	if (theForm.member_name.value.replace(/(^\s*)|(\s*$)/g, "") == "" || theForm.member_name.value.replace(/[\u4e00-\u9fa5]/g, "")){
		alert("姓名不能为空且必须为中文！");   
		theForm.member_name.focus();   
		return (false);   
	}
  
}
function sendSMS() {
  alert("验证码已发送，请注意查收");
  var aidNum = new Date().format('yyyyMMddhhmmss');
  //alert(aidNum);
   $.post("sendSMSaction.php", {memberuser:theForm.member_user.value, phone:
    theForm.member_phone.value, aid:aidNum});
   theForm.phone_aid.value = aidNum;
   //alert(theForm.phone_aid.value);
}

</script>
<?php
if($_POST["submit"]){
 $telCode = new TelephoneCheck();

if(empty($_POST['member_user']))
	echo "<script>alert('帐号不能为空');location='?tj=register';</script>";
else if(empty($_POST['member_password']))
	echo "<script>alert('密码不能为空');location='?tj=register';</script>";
else if($_POST['member_password']!=$_POST['pass'])
	echo "<script>alert('两次密码不一样');location='?tj=register';</script>";
else if(!empty($_POST['member_phone'])&&!is_numeric($_POST['member_phone']))
	echo "<script>alert('手机号码必须全为数字');location='?tj=register';</script>";
else if(!empty($_POST['member_email'])&&!ereg("([0-9a-zA-Z]+)([@])([0-9a-zA-Z]+)(.)([0-9a-zA-Z]+)",$_POST['member_email']))
	echo "<script>alert('邮箱输入不合法');location='?tj=register';</script>";
else if($telCode->checkTelephoneCode($_POST["member_user"], $_POST["phone_aid"], 
  $_POST["member_phone"], $_POST["phone_captcha"]) == false)
  echo "<script>alert('短信验证码无效');location='?tj=register';</script>";
else{
$_SESSION['member']=$_POST['member_user'];
$sql="insert into member(member_user, member_password, member_name, member_sex, member_phone, member_email)
 values('".$_POST['member_user']."','".md5($_POST['member_password'])."','".$_POST['member_name']."','".$_POST['member_sex']."','".$_POST['member_phone']."','".$_POST['member_email']."')";
$result=mysql_query($sql)or die(mysql_error());
if($result)
echo "<script>alert('恭喜你注册成功,马上进入主页面');location='member.php';</script>";
else
{
	echo "<script>alert('注册失败');location='index.php';</script>";
	mysql_close();
}
	}
}
?>
</head>
<body>
<?php if($_GET['tj'] == 'register'){ ?>
<form id="theForm" name="theForm" method="post" action="" onSubmit="return chk(this)" runat="server" style="margin-bottom:0px;">
  <table width="350" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#B3B3B3">
    <tr>
      <td colspan="2" align="center" bgcolor="#EBEBEB">会员注册&nbsp;&nbsp;以下打“*”为必填项</td>
    </tr>
    <tr>
      <td width="60" align="right" bgcolor="#FFFFFF">账&nbsp;&nbsp;&nbsp;号:</td>
      <td width="317" bgcolor="#FFFFFF"><input name="member_user" type="text" id="member_user" size="20" maxlength="20" />
	  <font color="#FF0000"> *</font>(由数字或字母组成)</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#FFFFFF">密&nbsp;&nbsp;&nbsp;码:</td>
      <td bgcolor="#FFFFFF"><input name="member_password" type="password" id="member_password" size="20" maxlength="20" />
      <font color="#FF0000"> *</font>(由数字或字母组成)</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#FFFFFF">确认密码:</td>
      <td bgcolor="#FFFFFF"><input name="pass" type="password" id="pass" size="20" />
      <font color="#FF0000"> *</font>(再次输入密码)</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#FFFFFF">姓名:</td>
      <td bgcolor="#FFFFFF"><input name="member_name" type="text" id="member_name" size="20" />
      <label><font color="#FF0000">*</font></label></td>
    </tr>
    <tr>
      <td align="right" bgcolor="#FFFFFF">性&nbsp;&nbsp;&nbsp;别:</td>
      <td align="left" bgcolor="#FFFFFF">
          <input name="member_sex" type="radio" id="0" value="男" checked="checked" />
          男
          <input type="radio" name="member_sex" value="女" id="1" />
          女&nbsp;</label></td>
    </tr>
   
    <tr>
      <td align="right" bgcolor="#FFFFFF">手机号:</td>
      <td bgcolor="#FFFFFF"><input name="member_phone" type="text" id="member_phone" size="20"/>
      <input type="button" onclick="sendSMS();" value="获取验证码">
      <input type="hidden" name="phone_aid" value="default"</input>
    </td>
    </tr>
    <tr>
      <td align="right" bgcolor="#FFFFFF">短信验证码:</td>
      <td bgcolor="#FFFFFF">
        <input name="phone_captcha" type="text" id="phone_captcha" size="20"/>
        <label><font color="#FF0000">*</font></label>
      </td>
    </tr>
    <tr>
      <td align="right" bgcolor="#FFFFFF">电子邮箱:</td>
      <td bgcolor="#FFFFFF"><input name="member_email" type="text" id="member_email" size="20"/>
        <label><font color="#FF0000">*</font></label>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" name="submit" id="submit" value="注册" />
        <input type="reset" name="button" id="button" value="重置" /></td>
    </tr>
  </table>
</form>
<?php
} 
	if($_GET['tj']== ''){
?>
<?php
if($_POST["submit2"]){
$name=$_POST['name'];
$pw=md5($_POST['password']);
$sql="select * from member where member_user='".$name."'"; 
$result=mysql_query($sql) or die("账号不正确");
$num=mysql_num_rows($result);
if($num==0){
	echo "<script>alert('帐号不存在');location='index.php';</script>";
	}
while($rs=mysql_fetch_object($result))
{
	if($rs->member_password!=$pw)
	{
		echo "<script>alert('密码不正确');location='index.php';</script>";
		mysql_close();
	}
	else 
	{
		$_SESSION['member']=$_POST['name'];
		header("Location:member.php");
		mysql_close();
		}
	}
}
?>
<form action="" method="post" name="regform" onSubmit="return Checklogin();" style="margin-bottom:0px;">
<table width="350" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#B3B3B3">
  <tr>
    <td colspan="2" align="center" bgcolor="#EBEBEB" class="font">会员登陆</td>
  </tr>
    <tr>
      <td width="65" align="center" bgcolor="#FFFFFF" class="font">用户名:</td>
      <td width="262" bgcolor="#FFFFFF" class="font"><input name="name" type="text" id="name"></td>
    </tr>
    <tr>
      <td align="center" valign="top" bgcolor="#FFFFFF" class="font">密&nbsp;码:</td>
      <td bgcolor="#FFFFFF" class="font"><input name="password" type="password" id="name">        </td>
    </tr>
    <tr>
    <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF" class="font"><input name="submit2" type="submit" value="会员登录"/>
      <a href='index.php?tj=register'>没有账号？立即注册...</a></td>
  </tr>
</table>
</form>
<?php } ?>
<table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="5"></td>
  </tr>
</table>

<table width="350" height="20" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
  <tr>
    <td align="left" bgcolor="#FFFFFF">&nbsp;Copyright @2013 XCBB All Rights Reserved. Build V.01</td>
  </tr>
</table>
</body>
</html>