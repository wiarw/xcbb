﻿<?php
if (!isset ($_SESSION)) {
	ob_start();
	session_start();
}
 $hostname="ykhah"; //mysql地址
 $basename="xcbb"; //mysql用户名
 $basepass="xcbb"; //mysql密码
 $database="xcbb_users"; //mysql数据库名称

 $conn=mysql_connect($hostname,$basename,$basepass)or die("error!"); //连接mysql              
 mysql_select_db($database,$conn); //选择mysql数据库
 mysql_query("set names 'utf8'");//mysql编码
?>