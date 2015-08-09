<?php
if($_POST["submit"]){
 $telCode = new TelephoneCheck();
 echo "<script>alert('注册成功');</script>";
if(empty($_POST['member_user']))
	echo "<script>alert('用户名不能为空');location='?tj=register';</script>";
else if(empty($_POST['member_password']))
	echo "<script>alert('密码不能为空');location='?tj=register';</script>";
else if($_POST['member_password']!=$_POST['pass'])
	echo "<script>alert('两次密码不一样');location='?tj=register';</script>";
else if(!empty($_POST['member_phone'])&&!is_numeric($_POST['member_phone']))
	echo "<script>alert('手机号码必须全为数字');location='?tj=register';</script>";
else if($telCode->checkTelephoneCode($_POST["member_user"], $_POST["phone_aid"], 
  $_POST["member_phone"], $_POST["phone_captcha"]) == false)
  echo "<script>alert('短信验证码无效');location='?tj=register';</script>";
else{
  
$_SESSION['member']=$_POST['member_user'];
$sql="insert into member(member_user, member_password, member_phone)
 values('".$_POST['member_user']."','".md5($_POST['member_password'])."','".$_POST['member_phone']."')";
$result=mysql_query($sql)or die(mysql_error());
if($result)
echo "<script>location='member.php';</script>";
else
{
	echo "<script>alert('注册失败');location='index.php';</script>";
	mysql_close();
}
	}
}
?>