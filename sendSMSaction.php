 <?php
  require_once('sms/SendTemplateSMS.php');
  require_once('./PhoneCaptcha.php');
        $uid = $_POST["memberuser"];
        $aid = $_POST["aid"];
        $phone = $_POST["phone"];;
        $telCode = new TelephoneCheck();
        $code = $telCode->getTelephoneCode($uid, $aid, $phone);
        error_log($code+"\n", 3, "error.log");
        $result = sendTemplateSMS($phone,array($code,'1'),"1");
?>