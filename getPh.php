<?php require_once('../connect.php'); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="css/reg.Style.css">
    <link rel="stylesheet" href="../css/loginstyle.css">
    <link rel="stylesheet" href="../css/font-awesome.css" media="screen">
    <title>سامانه ثبت نام | ورود</title>
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>

</head>

<body class="white-back">

    <?php require_once('includes/regTopNav.php'); ?>
    <?php require_once('includes/regFotter.php'); ?>


    <?php
    $error = "";
    require("HttpSample.php");

    function converttoenglish($string)
    {
        $persinaDigits1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $persinaDigits2 = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠');
        $allPersianDigits = array_merge($persinaDigits1, $persinaDigits2);
        $replaces = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        return str_replace($allPersianDigits, $replaces, $string);
    }
    function checkmobilevalidation($mobile)
    {
        if ($mobile == "") return 0;
        $flag = 0;
        $accept = ["0910", "0911", "0912", "0913", "0914", "0915", "0916", "0917", "0918", "0919", "0931", "0932", "0934", "0901", "0902", "0930", "0933", "0935", "0936", "0937", "0938", "0939", "0920", "0921", "0922", "0998", "0903", "0990"];
        if (!is_numeric($mobile)) $flag = 1;
        if (strlen($mobile) != 11) $flag = 1;
        if ($mobile[0] != 0) $flag = 1;
        $pish = substr($mobile, 0, 4);

        if (!in_array($pish, $accept)) $flag = 1;
        if ($flag == 1) return 0;
        else
            return 1;
    }
    function SendREST($username, $password, $Source, $Destination, $MsgBody, $Encoding)
    {

        $URL = "http://panel.asanak.ir/webservice/v1rest/sendsms";
        $msg = urlencode(trim($MsgBody));
        $url = $URL . '?username=' . $username . '&password=' . $password . '&source=' . $Source . '&destination=' . $Destination . '&message=' . $msg;
        $headers[] = 'Accept: text/html';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        try {
            if (($return = curl_exec($process))) {
                return $return;
            }
        } catch (Exception $ex) {
            // return $ex->errorMessage();
        }
    }
    if (!isset($_SESSION))
        session_start();
    $error = "";
    // $_SESSION = [];
    $_SESSION["mobile"] = "";
    $_SESSION["ccode"] = "";
    $_SESSION["user-login"] = "";
    $_SESSION['user'] = "";
    $_SESSION["userid"] = "";
    $_SESSION["passedsteptwo"] = 0;
    $_SESSION["attempts"] = 0;

    if (isset($_POST["loginbtn"])) {
        if (checkmobilevalidation($_POST["mobile"]) == 1) {
            if ($_POST["mobile"] != "") {
                $sql = "SELECT * FROM register_clients WHERE phone=?";
                $result = $connect->prepare($sql);
                $result->bindValue(1, converttoenglish($_POST["mobile"]));
                $result->execute();
                $num = 0;
                $error = "";
                foreach ($result as $rows) {
                    $_SESSION["phone"] = $rows["phone"];
                    $_SESSION["userid"] = $rows["id"];
                    $_SESSION["ip"] = $rows["ip"];
                    $num++;
                }

                if ($num >= 1) {


                    if($_SESSION["first-phone"] == converttoenglish($_POST["mobile"])) {

                        $_SESSION["ccode"] = rand(10000, 99999);
                        $_SESSION["first-phone"] = converttoenglish($_POST["mobile"]);

                    $encoding = (mb_detect_encoding($data['message']) == 'ASCII') ? "1" : "8";

                    $MSG = "*سامانه ثبت نام مجموعه مدارس سلام*";
                    $MSG .= "\n";
                    $MSG .= "کد تایید:";
                    $MSG .= "\n";
                    $MSG .= $_SESSION["ccode"];

                    //                $MSG = "*سامانه%20ثبت%20نام%20مجموعه%20مدارس%20سلام*";
                    //                $MSG .= "%0A";
                    //                $MSG .= "کد%20تایید:";
                    //                $MSG .= "%0A";
                    //                $MSG .= $_SESSION["ccode"];


                    //               $urll= 'http://185.4.31.5/sendsms.php?user=kamransheni&password=Rezakhan@2020&message='. $MSG .'&mobile='. $_SESSION["mobile"];
                    //               $res=file_get_contents($urll);
                    //echo $urll;
                    $send = SendREST("salamsch", "Kamiran@2018", "982171059", $_SESSION["phone"], $MSG, $encoding);

                    //                $obj = new HttpSample();
                    //                $obj->enqueueLoginMessage($_SESSION["mobile"],$MSG);


                    $_SESSION["admin-login"] = $_SESSION["user"];

                    echo $_SESSION["first-phone"];
                    echo converttoenglish($_POST["mobile"]);
                    header("Location: confirmP.php");

                    }  else {

                        $error = '<div dir="rtl"><h4><span class="label label-danger col-md-12 col-lg-12 col-sm-12 col-xs-12">داش همون یکی رو وارد کن</span></h4> 
                </div>';

                    }


                    
                    

                } else {
                    $error = '<div dir="rtl"><h4><span class="label label-danger col-md-12 col-lg-12 col-sm-12 col-xs-12">شماره وارد شده ثبت نمی باشد</span></h4> 
                </div>';
                }
            } else {
                $error = '<div dir="rtl"><h4><span class="label label-danger col-md-12 col-lg-12 col-sm-12 col-xs-12">لطفاًً موبایل را وارد نمایید!</span></h4> 
            </div>';
            }
        } else {
            $error = '<div dir="rtl"><h4><span class="label label-danger col-md-12 col-lg-12 col-sm-12 col-xs-12">لطفاًً موبایل را به صورت صحبح وارد نمایید!</span></h4> 
            </div>';
        }
    }
    ?>
    <div class="container">
        <header>
        </header>
        <div class="row">

            <!-- main start -->
            <div class="col-md-5 mx-auto login-panel-wrapper">
                <div class="login-panel section-style">
                    <div class="login-panel-head">
                        <div class="reg-header-text">ورود به سامانه یا ثبت نام</div>
                    </div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="form-group">
                                <?php echo $error ?>

                                <fieldset class="mobile-fieldset">
                                  
                                    <legend class="mobile-legend">شماره موبایل</legend>
                                        <input class="reg-input" dir="rtl" type="text" class="form-control" id="mobile" name="mobile" maxlength="11" autocomplete="off">
                          
                                </fieldset>
                                <div dir="rtl" name="ncode_error" id="ncode_error" style="color: red;" class="errorstyle"></div>
                            </div>
                            <button href="#" class="reg-btn" type="submit" name="loginbtn" onclick="return logincheck();">ادامه</button>
                        </form>
                       
                        <a class="go-to-user-panel under-btn-link" href="../NewUser/login.php"><p style="text-align: center;">پیگیری ثبت نام</p></a>
                    </div>
                </div>
            </div>
            <!-- main end -->
        </div>
    </div>


<script>
    
    $(document).ready(function() {

        $('.login-panel input').click(function() {

            $('.mobile-fieldset').css({
                borderColor: '#f6ba1e',
                transition: '0.3s'
            });

            // $('.problem-members-wrapper legend').css({
            //     color: '#1ab394',
            //     transition: '0.3s'
            // });

        })

        $(document).mouseup((ev) => {
            if ($(ev.target).closest('#mobile').length === 0) {

                $('.mobile-fieldset').css({
                    borderColor: '#dcdcdc',
                    transition: '0.3s'
                });


                // $('.problem-members-wrapper legend').css({
                //     color: 'rgb(148, 146, 146)',
                //     transition: '0.3s'
                // });

            }
        })

    })


</script>


</body>

</html>