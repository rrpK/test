<?php
if (!isset($_SESSION))
    session_start();
require_once("seccheck.php");
//$_SESSION["salamat"]["user"]=$_SESSION["edari"]["user"];

include("../connect.php");
include("jdf-sample.php");

//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";
//
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

//Check True Step
if ($_SESSION["Register"]["Step"] <> 2) {
    header("location:Main.php");
}
//Check Set Section
if (!(($_SESSION["Register"]["Section"] == 4) || ($_SESSION["Register"]["Section"] == 3) || ($_SESSION["Register"]["Section"] == 1))) {
    header("location:Main.php");
}
//Check Set Gender
if (!(($_SESSION["Register"]["Gender"] == 1) || ($_SESSION["Register"]["Gender"] == 2))) {
    header("location:Main.php");
}

function get_real_ip_addr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = $_SERVER['AR_REAL_IP'];
    }
    return $ip;
}

try {
    $qq = null;
    $sql = "INSERT INTO register_regvisit (RegVisitID,
RegVisitIP,
RegVisitDate,
RegVisitTime,
RegVisitDateTime,
RegVisitPage,
RegVisitRefer,
RegVisitSessionID)
 VALUES(NULL,:RegVisitIP,
:RegVisitDate,
:RegVisitTime,
NOW(),
:RegVisitPage,
:RegVisitRefer,
:RegVisitSessionID)";
    $result = $connect->prepare($sql);
    $result->bindValue(":RegVisitIP", get_real_ip_addr());
    $result->bindValue(":RegVisitDate", getGtoPDate(date("Y-m-d H:i:s"), 'en'));
    $result->bindValue(":RegVisitTime", getStandardTime(date("Y-m-d H:i:s")));
    $result->bindValue(":RegVisitPage", "Step2");
    $result->bindValue(":RegVisitRefer", $_SERVER["HTTP_REFERER"]);
    $result->bindValue(":RegVisitSessionID", session_id());
    $qq = $result->execute();
    if (isset($qq)) {
        $error = '<div class="alert alert-success"><span class="glyphicon glyphicon-ok" aria-hidden="True"></span>ثبت اطلاعات انجام شد</div>';
    }
} catch (PDOException $error) {
    echo $error->getMessage();
}


$Section = $_SESSION["Register"]["Section"];
$Gender = $_SESSION["Register"]["Gender"];


$allgrades = [];
$sql = "select * from grades where section=?";
$result = $connect->prepare($sql);
$result->bindValue(1, $Section);
$result->execute();
foreach ($result as $rows) {
    $allgrades[$rows["id"]] = $rows["name"];
}


$allschools = [];
$sql = "select * from schools where section=? and sex=? order by Region ASC,sex ASC,name ASC";
$result = $connect->prepare($sql);
$result->bindValue(1, $Section);
$result->bindValue(2, $Gender);
$result->execute();
foreach ($result as $rows) {
    if (!isset($_SESSION["Register"]["School"]))
        $allschools[$rows["id"]] = [$rows["name"], $rows["Region"], $rows["sex"]];
    else
        if ($_SESSION["Register"]["School"] == $rows["id"]) {
        $allschools[$rows["id"]] = [$rows["name"], $rows["Region"], $rows["sex"]];
    }
}


$alltahsilat = [];
$sql = "select * from tahsilat";
$result = $connect->prepare($sql);
$result->execute();
foreach ($result as $rows) {
    $alltahsilat[$rows["id"]] = $rows["name"];
}


$allmonth = [];
$sql = "select * from monthr";
$result = $connect->prepare($sql);
$result->execute();
foreach ($result as $rows) {
    $allmonth[$rows["id"]] = $rows["name"];
}


$allyears = [];
if ($Section == 1) {
    $allyears = [1397, 1396, 1395, 1394, 1393, 1392, 1391, 1390, 1389, 1388, 1387, 1386, 1385, 1384, 1383];
}
if ($Section == 3) {
    $allyears = [1389, 1388, 1387, 1386, 1385, 1384, 1383, 1382, 1381, 1380];
}
if ($Section == 4) {
    $allyears = [1386, 1385, 1384, 1383, 1382, 1381, 1380, 1379, 1378, 1377, 1376];
}


$allintru = [];
$sql = "select * from intru";
$result = $connect->prepare($sql);
$result->execute();
foreach ($result as $rows) {
    $allintru[$rows["id"]] = $rows["name"];
}


$allfields = [];
$sql = "select * from fields";
$result = $connect->prepare($sql);
$result->execute();
foreach ($result as $rows) {
    $allfields[$rows["id"]] = $rows["name"];
}
if ($Section == 4) {
    $RiaziText = "نمره ریاضی یا حسابان";
    $OloomText = "نمره فیزیک";
} else {
    $RiaziText = "نمره ریاضی";
    $OloomText = "نمره علوم";
}

$FieldArray[] = "";
// $FieldArray["name"] = "";
// $FieldArray["last"] = "";
$FieldArray["ncode"] = "";

$FieldArray["t_day"] = "";
$FieldArray["t_month"] = "";
$FieldArray["t_year"] = "";

$FieldArray["paye"] = "";
$FieldArray["field"] = "";
$FieldArray["olaviyat"] = "";

$FieldArray["pedmobile"] = "";
$FieldArray["madmobile"] = "";
$FieldArray["telephone"] = "";
$FieldArray["zaroori"] = "";

$FieldArray["schoolname"] = "";
$FieldArray["moaddel"] = "";
$FieldArray["riazi"] = "";
$FieldArray["oloom"] = ""; //TO DO Add TO DB
$FieldArray["enzebat"] = "";

$FieldArray["distric"] = ""; //TO DO Add TO DB
$FieldArray["ashnaee"] = "";
$FieldArray["takmil"] = "";


$FieldArrayError[] = "";
// $FieldArrayError["name"] = "";
// $FieldArrayError["last"] = "";
$FieldArrayError["ncode"] = "";

$FieldArrayError["t_day"] = "";
$FieldArrayError["t_month"] = "";
$FieldArrayError["t_year"] = "";

$FieldArrayError["paye"] = "";
$FieldArrayError["field"] = "";
$FieldArrayError["olaviyat"] = "";

$FieldArrayError["pedmobile"] = "";
$FieldArrayError["madmobile"] = "";
$FieldArrayError["telephone"] = "";
$FieldArrayError["zaroori"] = "";

$FieldArrayError["schoolname"] = "";
$FieldArrayError["moaddel"] = "";
$FieldArrayError["riazi"] = "";
$FieldArrayError["oloom"] = ""; //TO DO Add TO DB
$FieldArrayError["enzebat"] = "";

$FieldArrayError["distric"] = ""; //TO DO Add TO DB
$FieldArrayError["ashnaee"] = "";
$FieldArrayError["takmil"] = "";


$FieldArrayHelp[] = "";
// $FieldArrayHelp["name"] = "";
$FieldArrayHelp["last"] = "";
$FieldArrayHelp["ncode"] = "";

$FieldArrayHelp["t_day"] = "";
$FieldArrayHelp["t_month"] = "";
$FieldArrayHelp["t_year"] = "";

$FieldArrayHelp["paye"] = "";
$FieldArrayHelp["field"] = "";
$FieldArrayHelp["olaviyat"] = "";

$FieldArrayHelp["pedmobile"] = "";
$FieldArrayHelp["madmobile"] = "";
$FieldArrayHelp["telephone"] = "";
$FieldArrayHelp["zaroori"] = "";

$FieldArrayHelp["schoolname"] = "";
$FieldArrayHelp["moaddel"] = "";
$FieldArrayHelp["riazi"] = "";
$FieldArrayHelp["oloom"] = ""; //TO DO Add TO DB
$FieldArrayHelp["enzebat"] = "";

$FieldArrayHelp["distric"] = ""; //TO DO Add TO DB
$FieldArrayHelp["ashnaee"] = "";
$FieldArrayHelp["takmil"] = "";


// $FieldArrayClass[] = '';
// $FieldArrayClass["name"] = 'class="form-group has-feedback"';
// $FieldArrayClass["last"] = 'class="form-group has-feedback"';
$FieldArrayClass["ncode"] = 'class="form-group has-feedback"';

$FieldArrayClass["t_day"] = 'class="form-group has-feedback"';
$FieldArrayClass["t_month"] = 'class="form-group has-feedback"';
$FieldArrayClass["t_year"] = 'class="form-group has-feedback"';

$FieldArrayClass["paye"] = 'class="form-group has-feedback"';
if ($Section <> 4) {
    $FieldArrayClass["field"] = 'class="form-group has-feedback hide"';
} else {
    $FieldArrayClass["field"] = 'class="form-group has-feedback"';
}

$FieldArrayClass["olaviyat"] = 'class="form-group has-feedback"';

$FieldArrayClass["pedmobile"] = 'class="form-group has-feedback"';
$FieldArrayClass["madmobile"] = 'class="form-group has-feedback"';
$FieldArrayClass["telephone"] = 'class="form-group has-feedback"';
$FieldArrayClass["zaroori"] = 'class="form-group has-feedback  hide"';

$FieldArrayClass["schoolname"] = 'class="form-group has-feedback"';
if ($Section == 1) {
    $FieldArrayClass["moaddel"] = 'class="form-group has-feedback hide"';
    $FieldArrayClass["riazi"] = 'class="form-group has-feedback hide"';
    $FieldArrayClass["oloom"] = 'class="form-group has-feedback hide"'; //TO DO Add TO DB
    $FieldArrayClass["enzebat"] = 'class="form-group has-feedback hide"';
} else {
    $FieldArrayClass["moaddel"] = 'class="form-group has-feedback"';
    $FieldArrayClass["riazi"] = 'class="form-group has-feedback"';
    $FieldArrayClass["oloom"] = 'class="form-group has-feedback"'; //TO DO Add TO DB
    $FieldArrayClass["enzebat"] = 'class="form-group has-feedback"';
}


$FieldArrayClass["distric"] = 'class="form-group has-feedback"'; //TO DO Add TO DB
$FieldArrayClass["ashnaee"] = 'class="form-group has-feedback"';
$FieldArrayClass["takmil"] = 'class="form-group has-feedback"';


if (isset($_POST["NextStep"])) {
    $IsValid = true;

    // $_SESSION["Register"]["last"] = $_POST["last"];
    $_SESSION["Register"]["ncode"] = ConvertToEnglish($_POST["ncode"]);
    $_SESSION["Register"]["ncode-fa"] = $_POST["ncode"];

    $_SESSION["Register"]["t_day"] = $_POST["t_day"];
    $_SESSION["Register"]["t_month"] = $_POST["t_month"];
    $_SESSION["Register"]["t_year"] = $_POST["t_year"];

    $_SESSION["Register"]["paye"] = $_POST["paye"];
    $_SESSION["Register"]["field"] = $_POST["field"];
    $_SESSION["Register"]["olaviyat"] = $_POST["olaviyat"];

    $_SESSION["Register"]["pedmobile"] = ConvertToEnglish($_POST["pedmobile"]);
    $_SESSION["Register"]["pedmobile-fa"] = $_POST["pedmobile"];
    $_SESSION["Register"]["madmobile"] = ConvertToEnglish($_POST["madmobile"]);
    $_SESSION["Register"]["madmobile-fa"] = $_POST["madmobile"];
    $_SESSION["Register"]["telephone"] = ConvertToEnglish($_POST["telephone"]);
    $_SESSION["Register"]["telephone-fa"] = $_POST["telephone"];
    $_SESSION["Register"]["zaroori"] = ConvertToEnglish($_POST["zaroori"]);
    $_SESSION["Register"]["zaroori-fa"] = $_POST["zaroori"];


    $_SESSION["Register"]["schoolname"] = $_POST["schoolname"];
    $_SESSION["Register"]["moaddel"] = ConvertToEnglish($_POST["moaddel"]);
    $_SESSION["Register"]["riazi"] = ConvertToEnglish($_POST["riazi"]);
    $_SESSION["Register"]["oloom"] = ConvertToEnglish($_POST["oloom"]); //TO DO Add TO DB
    $_SESSION["Register"]["enzebat"] = ConvertToEnglish($_POST["enzebat"]);

    $_SESSION["Register"]["distric"] = $_POST["distric"]; //TO DO Add TO DB
    $_SESSION["Register"]["ashnaee"] = $_POST["ashnaee"];
    $_SESSION["Register"]["takmil"] = $_POST["takmil"];

    $_SESSION["Register"]["tavalod"] = $_POST["t_year"] . "/" . FixLen($_POST["t_month"]) . "/" . FixLen($_POST["t_day"]);

    // if (trim($_POST["name"]) == "") {


    //     $FieldArrayError["name"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نام خود را وارد کنید</label>';
    //     //$FieldArrayHelp["name"] = '<span class="help-block"></span>';
    //     $FieldArrayClass["name"] = 'class="form-group has-feedback has-error"';
    //     $IsValid = false;
    // }
    // else {
    //        if (CheckIsPersian($_POST["name"]) == 0) {
    //            $FieldArrayError["name"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نام خود را وارد کنید</label>';
    //            $FieldArrayHelp["name"] = '<span class="help-block">نام خود را به زبان فارسی وارد کنید</span>';
    //            $FieldArrayClass["name"] = 'class="form-group has-feedback has-error"';
    //        }
    //
    //    }
    if (trim($_POST["last"]) == "") {
        $FieldArrayError["last"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نام خانوادگی خود را وارد کنید</label>';
        //$FieldArrayHelp["last"] = '<span class="help-block"></span>';
        $FieldArrayClass["last"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    // else {
    //        if (CheckIsPersian($_POST["last"]) == 0) {
    //            $FieldArrayError["last"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نام خانوادگی خود را وارد کنید</label>';
    //            $FieldArrayHelp["last"] = '<span class="help-block">نام خانوادگی خود را به زبان فارسی وارد کنید</span>';
    //            $FieldArrayClass["last"] = 'class="form-group has-feedback has-error"';
    //        }
    //    }
    if (trim($_POST["ncode"]) == "") {
        $FieldArrayError["ncode"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا کدملی خود را وارد کنید</label>';
        $FieldArrayHelp["ncode"] = '<span class="help-block">کدملی را 10 رقم به همراه صفر های ابتدا وارد کنید</span>';
        $FieldArrayClass["ncode"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    } else {
        if (CheckNationalCode(trim(ConvertToEnglish($_POST["ncode"]))) <> 1) {
            $FieldArrayError["ncode"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>کد ملی صحیح نیست</label>';
            $FieldArrayHelp["ncode"] = '<span class="help-block">کدملی را 10 رقم به همراه صفر های ابتدا وارد کنید</span>';
            $FieldArrayClass["ncode"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        } else {
            $counter = 0;
            $sql = "SELECT * FROM students WHERE ncode=? and RegDate > '1399/08/01'";
            $result = $connect->prepare($sql);
            $result->bindValue(1, ConvertToEnglish($_POST["ncode"]));
            $result->execute();
            foreach ($result as $rows) {
                $counter++;
            }
            if ($counter > 0) {
                $HelpText = "کد ملی وارد شده قبلاً در سیستم ثبت شده و امکان ثبت نام مجدد وجود ندارد.";
                $HelpText .= "\n";
                $HelpText .= "اگر قبلا ثبت نام کرده اید از <b class='text-danger'><a href='http://register.salamsch.com/user'>اینجا</a></b> وارد ناحیه کاربری خود شوید در غیر این صورت ";
                $HelpText .= "\n";
                $HelpText .= " جهت دریافت اطلاعات بیشتر با شماره 71059 تماس حاصل فرمایید";;
                $FieldArrayError["ncode"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>کد ملی تکراری است</label>';
                $FieldArrayHelp["ncode"] = '<span class="help-block">' . $HelpText . '</span>';
                $FieldArrayClass["ncode"] = 'class="form-group has-feedback has-error"';
                $IsValid = false;
            }
        }
    }

    if (trim($_POST["t_day"]) == 0) {
        $FieldArrayError["t_day"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا روز تولد خود را انتخاب کنید</label>';
        //$FieldArrayHelp["t_day"] = '<span class="help-block"></span>';
        $FieldArrayClass["t_day"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if (trim($_POST["t_month"]) == 0) {
        $FieldArrayError["t_month"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا ماه تولد خود را انتخاب کنید</label>';
        //$FieldArrayHelp["t_month"] = '<span class="help-block"></span>';
        $FieldArrayClass["t_month"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if (trim($_POST["t_year"]) == 0) {
        $FieldArrayError["t_year"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا سال تولد خود را انتخاب کنید</label>';
        //$FieldArrayHelp["t_year"] = '<span class="help-block"></span>';
        $FieldArrayClass["t_year"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if (trim($_POST["paye"]) == 0) {
        $FieldArrayError["paye"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا پایه خود را انتخاب کنید</label>';
        //$FieldArrayHelp["paye"] = '<span class="help-block"></span>';
        $FieldArrayClass["paye"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if ($Section == 4) {
        if (trim($_POST["field"]) == 0) {
            $FieldArrayError["field"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا رشته خود را انتخاب کنید</label>';
            //$FieldArrayHelp["field"] = '<span class="help-block"></span>';
            $FieldArrayClass["field"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        }
    }
    if (trim($_POST["olaviyat"]) == 0) {
        $FieldArrayError["olaviyat"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا مدرسه مورد نظر خود را انتخاب کنید</label>';
        //$FieldArrayHelp["olaviyat"] = '<span class="help-block"></span>';
        $FieldArrayClass["olaviyat"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if (trim(ConvertToEnglish($_POST["pedmobile"])) == "") {
        $FieldArrayError["pedmobile"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا موبایل پدر را وارد کنید</label>';
        $FieldArrayHelp["pedmobile"] = '<span class="help-block">از این شماره جهت اطلاع رسانی پیامکی روند پذیرش شما استفاده خواهد شد</span>';
        $FieldArrayClass["pedmobile"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    } else {
        if (!is_numeric(ConvertToEnglish($_POST["pedmobile"]))) {
            $FieldArrayError["pedmobile"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا موبایل پدر را وارد کنید</label>';
            $FieldArrayHelp["pedmobile"] = '<span class="help-block">از این شماره جهت اطلاع رسانی پیامکی روند پذیرش شما استفاده خواهد شد</span>';
            $FieldArrayClass["pedmobile"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        } else {

            if (strlen(trim(ConvertToEnglish($_POST["pedmobile"]))) <> 11) {
                $FieldArrayError["pedmobile"] = '<label class="control-label" for="name"><i class="fa fa-times"></i> لطفا موبایل پدر را همراه با صفر اولیه وارد کنید</label>';
                $FieldArrayHelp["pedmobile"] = '<span class="help-block">از این شماره جهت اطلاع رسانی پیامکی روند پذیرش شما استفاده خواهد شد</span>';
                $FieldArrayClass["pedmobile"] = 'class="form-group has-feedback has-error"';
                $IsValid = false;
            }
        }
    }

    if (trim(ConvertToEnglish($_POST["madmobile"])) == "") {
        $FieldArrayError["madmobile"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا موبایل مادر را وارد کنید</label>';
        $FieldArrayHelp["madmobile"] = '<span class="help-block">از این شماره جهت اطلاع رسانی پیامکی روند پذیرش شما استفاده خواهد شد</span>';
        $FieldArrayClass["madmobile"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    } else {
        if (!is_numeric(ConvertToEnglish($_POST["madmobile"]))) {
            $FieldArrayError["madmobile"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا موبایل پدر را وارد کنید</label>';
            $FieldArrayHelp["madmobile"] = '<span class="help-block">از این شماره جهت اطلاع رسانی پیامکی روند پذیرش شما استفاده خواهد شد</span>';
            $FieldArrayClass["madmobile"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        } else {

            if (strlen(trim(ConvertToEnglish($_POST["madmobile"]))) <> 11) {
                $FieldArrayError["madmobile"] = '<label class="control-label" for="name"><i class="fa fa-times"></i> لطفا موبایل مادر را همراه با صفر اولیه وارد کنید</label>';
                $FieldArrayHelp["madmobile"] = '<span class="help-block">از این شماره جهت اطلاع رسانی پیامکی روند پذیرش شما استفاده خواهد شد</span>';
                $FieldArrayClass["madmobile"] = 'class="form-group has-feedback has-error"';
                $IsValid = false;
            }
        }
    }
    if (trim($_POST["telephone"]) == "") {
        $FieldArrayError["telephone"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا تلفن منزل را وارد کنید</label>';
        $FieldArrayHelp["telephone"] = '<span class="help-block">از این شماره جهت اطلاع رسانی روند پذیرش شما استفاده خواهد شد</span>';
        $FieldArrayClass["telephone"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    } else {
        if (!is_numeric(ConvertToEnglish($_POST["telephone"]))) {
            $FieldArrayError["telephone"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا تلفن منزل را وارد کنید</label>';
            $FieldArrayHelp["telephone"] = '<span class="help-block">از این شماره جهت اطلاع رسانی روند پذیرش شما استفاده خواهد شد</span>';
            $FieldArrayClass["telephone"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        } else {

            if (strlen(trim(ConvertToEnglish($_POST["telephone"]))) <> 11) {
                $FieldArrayError["telephone"] = '<label class="control-label" for="name"><i class="fa fa-times"></i> لطفا تلفن منزل را همراه با کد شهر وارد کنید</label>';
                $FieldArrayHelp["telephone"] = '<span class="help-block">از این شماره جهت اطلاع رسانی روند پذیرش شما استفاده خواهد شد</span>';
                $FieldArrayClass["telephone"] = 'class="form-group has-feedback has-error"';
                $IsValid = false;
            }
        }
    }
    //    if (trim($_POST["zaroori"]) == "") {
    //        $FieldArrayError["zaroori"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا تلفن ضروری را وارد کنید</label>';
    //        //$FieldArrayHelp["zaroori"] = '<span class="help-block"></span>';
    //        $FieldArrayClass["zaroori"] = 'class="form-group has-feedback has-error"';
    //        $IsValid = false;
    //    } else {
    //        if (!is_numeric(ConvertToEnglish($_POST["zaroori"]))) {
    //            $FieldArrayError["zaroori"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا تلفن ضروری را وارد کنید</label>';
    //            $FieldArrayHelp["zaroori"] = '<span class="help-block">از این شماره جهت اطلاع رسانی روند پذیرش شما استفاده خواهد شد</span>';
    //            $FieldArrayClass["zaroori"] = 'class="form-group has-feedback has-error"';
    //            $IsValid = false;
    //        } else {
    //
    //            if (strlen(trim(ConvertToEnglish($_POST["zaroori"]))) <> 11) {
    //                $FieldArrayError["zaroori"] = '<label class="control-label" for="name"><i class="fa fa-times"></i> لطفا تلفن ضروری را همراه با کد شهر وارد کنید</label>';
    //                $FieldArrayHelp["zaroori"] = '<span class="help-block">از این شماره جهت اطلاع رسانی روند پذیرش شما استفاده خواهد شد</span>';
    //                $FieldArrayClass["zaroori"] = 'class="form-group has-feedback has-error"';
    //                $IsValid = false;
    //            }
    //        }
    //    }
    if (trim($_POST["schoolname"]) == "") {
        $FieldArrayError["schoolname"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا مدرسه فعلی خود را وارد کنید</label>';
        $FieldArrayHelp["schoolname"] = '<span class="help-block">اگر مدرسه فعلی ندارید بنویسید : نامشخص</span>';
        $FieldArrayClass["schoolname"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if ($Section <> 1) {
        if (trim($_POST["moaddel"]) == "") {
            $FieldArrayError["moaddel"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا معدل خود را وارد کنید</label>';
            $FieldArrayHelp["moaddel"] = '<span class="help-block">اگر هنوز کارنامه شما صادر نشده بنویسید : نامشخص</span>';
            $FieldArrayClass["moaddel"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        }
        if (trim($_POST["riazi"]) == "") {
            $FieldArrayError["riazi"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نمره ریاضی خود را وارد کنید</label>';
            $FieldArrayHelp["riazi"] = '<span class="help-block">اگر هنوز کارنامه شما صادر نشده بنویسید : نامشخص</span>';
            $FieldArrayClass["riazi"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        }
        if (trim($_POST["oloom"]) == "") {
            $FieldArrayError["oloom"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نمره علوم خود را وارد کنید</label>';
            $FieldArrayHelp["oloom"] = '<span class="help-block">اگر هنوز کارنامه شما صادر نشده بنویسید : نامشخص</span>';
            $FieldArrayClass["oloom"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        }
        if (trim($_POST["enzebat"]) == "") {
            $FieldArrayError["enzebat"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نمره انضباط خود را وارد کنید</label>';
            $FieldArrayHelp["enzebat"] = '<span class="help-block">اگر هنوز کارنامه شما صادر نشده بنویسید : نامشخص</span>';
            $FieldArrayClass["enzebat"] = 'class="form-group has-feedback has-error"';
            $IsValid = false;
        }
    }
    if (trim($_POST["distric"]) == 0) {
        $FieldArrayError["distric"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا منطقه محل سکونت خود را انتخاب کنید</label>';
        //$FieldArrayHelp["distric"] = '<span class="help-block"></span>';
        $FieldArrayClass["distric"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if (trim($_POST["ashnaee"]) == 0) {
        $FieldArrayError["ashnaee"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا نحوه آشنایی با سلام را انتخاب کنید</label>';
        //$FieldArrayHelp["ashnaee"] = '<span class="help-block"></span>';
        $FieldArrayClass["ashnaee"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }
    if (trim($_POST["takmil"]) == 0) {
        $FieldArrayError["takmil"] = '<label class="control-label" for="name"><i class="fa fa-times"></i>لطفا مشخص کنید چه کسی فرم را تکمیل کرد</label>';
        $FieldArrayHelp["takmil"] = '<span class="help-block">تکمیل کننده فرم به عنوان پیگیر روند پذیرش دانش آموز از طرف ما در نظر گرفته خواهد شد</span>';
        $FieldArrayClass["takmil"] = 'class="form-group has-feedback has-error"';
        $IsValid = false;
    }


    if ($IsValid == true) {
        $_SESSION["Register"]["Step"] = 3;
        header("location:Step_3.php");
    }
}

if (isset($_POST["StepBack"])) {
    $_SESSION["Register"]["Step"] = 1;
    header("location:Main.php");
}

function ConvertToEnglish($string)
{
    $persinaDigits1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $persinaDigits2 = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    $allPersianDigits = array_merge($persinaDigits1, $persinaDigits2);
    $replaces = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    return str_replace($allPersianDigits, $replaces, $string);
}

function CheckIsPersian($string)
{
    if ($string == "") return 1;
    $alphabet = ['ا', 'آ', 'ئ', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', 'ي', 'ة', 'ؤ', 'ژ']; # create an array that contains only Persian characters
    $alphabet = array_flip($alphabet);
    $chars = preg_split('/(?<!^)(?!$)/u', $string);
    foreach ($chars as $char) {
        if (!isset($alphabet[$char])) {
            return 0;
        }
    }
    return 1;
}

function CheckNationalCode($input)
{
    if (
        !preg_match("/^\d{10}$/", $input)
        || $input == '0000000000'
        || $input == '1111111111'
        || $input == '2222222222'
        || $input == '3333333333'
        || $input == '4444444444'
        || $input == '5555555555'
        || $input == '6666666666'
        || $input == '7777777777'
        || $input == '8888888888'
        || $input == '9999999999'
    ) {
        return false;
    }
    $check = (int)$input[9];
    $sum = array_sum(array_map(function ($x) use ($input) {
        return ((int)$input[$x]) * (10 - $x);
    }, range(0, 8))) % 11;
    return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
}

function FixLen($Str)
{
    if (strlen($Str) == 1) {
        return "0" . $Str;
    } else {
        return $Str;
    }
}


if ($Section == 1) {
    $HideSection = 'hide';
} else {
    $HideSection = '';
}
?>
<!DOCTYPE html>
<html>

<head>

    <?php
    include('head.php');
    ?>
    <?php
    include('foot.php');
    ?>
    <!--    <link rel="stylesheet" href="https;//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">-->
    <!--    <script src="https;//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>-->
    <!--    <script src="https;//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>-->
    <!--    <script src="https;//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>-->
    <!--    <script src="https;//www.chartjs.org/samples/latest/utils.js"></script>-->
    <style>
        .login-box,
        .register-box {
            width: 360px;
            margin: 7% auto;
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>

<body class="hold-transition white-back">
    <div class="wrapper">

        <?php require_once('includes/regTopNav.php') ?>

        <!-- Full Width Column -->
        <div class="reg-content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <!--            <section class="content-header">-->
                <!--                <h1>-->
                <!--                    ناحیه کاربری مسئول ثبت نام-->
                <!--                    <small>سامانه ثبت نام مجموعه مدارس سلام</small>-->
                <!--                </h1>-->
                <!--               -->
                <!--            </section>-->

                <!-- Main content -->
                <section class="content">


                    <div class="personal-inf-panel">

                        <div class="personal-inf-header reg-road-header section-style">
                            <?php require_once("progressBar.php"); ?>
                        </div>

                        <div class="personal-inf-body section-style">



                            <div class="col-md-3 col-xs-12 pull-right">

                                <p class="inf-title">اطلاعات اولیه دانش‌آموز</p>

                                <form method="post" action="#">

                                    <div <?php echo $FieldArrayClass["name"]; ?>>
                                        <?php echo $FieldArrayError["name"]; ?>
                                        <input id="name" class="register-input-destination inf-input" name="name" type="text" class="form-control" autocomplete="off" placeholder="نام و نام خانوادگی" <?php if ($_SESSION["Register"]["name"] != "") echo 'value="' . $_SESSION["Register"]["name"] . '"'; ?> />
                                        <?php echo $FieldArrayHelp["name"]; ?>
                                    </div>


                                    <!-- <input class="inf-input form-control register-input-destination" dir="rtl" type="text" id="name" name="name" autocomplete="off" placeholder="نام و نام خانوادگی"> -->

                                    <div class="selector-container  register-input-destination">
                                        <div class="select-box">
                                            <div class="options-container year-options-container">
                                                <!-- <select name="Section" id="Section"> -->



                                                <?php foreach ($allyears as $key => $value) { ?>

                                                    <div class="option year-option">
                                                        <input type="radio" class="radio" id="<?php echo $value; ?>" name="t_year" value="<?php echo $value; ?>" />
                                                        <label for="<?php echo $value; ?>" id="<?php echo $value; ?>" class="noselect"><?php echo $value; ?></label>
                                                    </div>

                                                <?php }; ?>



                                            </div>
                                            <div id="" class="selected year-selected noselect">
                                                سال تولد
                                            </div>
                                            <!-- </select> -->
                                        </div>

                                        <div class="clear"></div>
                                    </div>
                                    <div class="selector-container  register-input-destination">
                                        <div class="select-box">
                                            <div class="options-container sex-options-container">
                                                <!-- <select name="Gender" id="Gender"> -->


                                                <div class="option sex-option">
                                                    <input type="radio" class="radio" id="boy" name="Gender" value="1" />
                                                    <label for="boy" id="elem" class="noselect">پسر</label>
                                                </div>
                                                <div class="option sex-option">
                                                    <input type="radio" class="radio" id="girl" name="Gender" value="2" />
                                                    <label for="girl" id="h1" class="noselect">دختر</label>
                                                </div>
                                                <!-- </select> -->


                                            </div>
                                            <div id="" class="selected sex-selected noselect">
                                                جنسیت
                                            </div>


                                        </div>

                                        <div class="clear"></div>
                                    </div>



                                    <div class="row">

                                        <div class="col-xs-12">
                                        </div>

                                    </div>


                            </div>

                            <div class="clear"></div>




                        </div>








                    </div>



















                    <!-- /.register-box -->
                    <div class="register-box-body">
                        <p class="login-box-msg">لطفا فرم زیر را تکمیل بفرمایید</p>

                        <form method="post">
                            <!--                            <div class="form-group has-success">-->
                            <!--                                <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> مقدار درست</label>-->
                            <!--    <label class="control-label" for="inputSuccess"><i class="fa fa-times-circle-o"></i> مقدار درست</label>-->
                            <!--                                <input type="text" class="form-control" id="inputSuccess" placeholder="متن">-->
                            <!--                                <span class="help-block">راهنمای ورودی</span>-->
                            <!--                            </div>-->
                            <div class="col-xs-12 col-sm-12 col-md-6">



                                <div <?php echo $FieldArrayClass["ncode"]; ?>>
                                    <?php echo $FieldArrayError["ncode"]; ?>
                                    <input id="ncode" name="ncode" type="text" class="form-control" placeholder="کدملی" <?php if ($_SESSION["Register"]["ncode"] != "") echo 'value="' . $_SESSION["Register"]["ncode"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["ncode"]; ?>
                                </div>


                                <div <?php echo $FieldArrayClass["t_day"]; ?>>
                                    <?php echo $FieldArrayError["t_day"]; ?>
                                    <select id="t_day" name="t_day" class="form-control">
                                        <option value="0">روز تولد؟</option>
                                        <?php
                                        if ($_SESSION["Register"]["t_day"] == 0) {
                                            for ($i = 1; $i <= 31; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        } else {
                                            for ($i = 1; $i <= 31; $i++) {
                                                if ($_SESSION["Register"]["t_day"] == $i)
                                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                                else
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["t_day"]; ?>
                                </div>

                                <div <?php echo $FieldArrayClass["t_month"]; ?>>
                                    <?php echo $FieldArrayError["t_month"]; ?>
                                    <select id="t_month" name="t_month" class="form-control">
                                        <option value="0">ماه تولد؟</option>
                                        <?php
                                        foreach ($allmonth as $key => $value) {
                                            if ($key == $_SESSION["Register"]["t_month"])
                                                echo '<option value="' . $key . '" selected>' . $value . '</option>';
                                            else
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["t_month"]; ?>
                                </div>

                                <div <?php echo $FieldArrayClass["t_year"]; ?>>
                                    <?php echo $FieldArrayError["t_year"]; ?>
                                    <select id="t_year" name="t_year" class="form-control">
                                        <option value="0">سال تولد؟</option>
                                        <?php
                                        foreach ($allyears as $key => $value) {
                                            if ($value == $_SESSION["Register"]["t_year"])
                                                echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                            else
                                                echo '<option value="' . $value . '">' . $value . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["t_year"]; ?>
                                </div>
                                <div <?php echo $FieldArrayClass["distric"]; ?>>
                                    <?php echo $FieldArrayError["distric"]; ?>
                                    <select id="distric" name="distric" class="form-control">
                                        <option value="0">منطقه محل سکونت؟</option>
                                        <option value="1">تهران منطقه 1</option>
                                        <option value="2">تهران منطقه 2</option>
                                        <option value="3">تهران منطقه 3</option>
                                        <option value="4">تهران منطقه 4</option>
                                        <option value="5">تهران منطقه 5</option>
                                        <option value="6">تهران منطقه 6</option>
                                        <option value="7">تهران منطقه 7</option>
                                        <option value="8">تهران منطقه 8</option>
                                        <option value="9">تهران منطقه 9</option>
                                        <option value="10">تهران منطقه 10</option>
                                        <option value="11">تهران منطقه 11</option>
                                        <option value="12">تهران منطقه 12</option>
                                        <option value="13">تهران منطقه 13</option>
                                        <option value="14">تهران منطقه 14</option>
                                        <option value="15">تهران منطقه 15</option>
                                        <option value="16">تهران منطقه 16</option>
                                        <option value="17">تهران منطقه 17</option>
                                        <option value="18">تهران منطقه 18</option>
                                        <option value="19">تهران منطقه 19</option>
                                        <option value="20">تهران منطقه 20</option>
                                        <option value="21">تهران منطقه 21</option>
                                        <option value="22">تهران منطقه 22</option>
                                        <option value="23">شهر کرج</option>
                                        <option value="24">شهر مشهد</option>
                                        <option value="25">شهر شاهرود</option>
                                        <option value="26">شهر تبریز</option>
                                        <option value="27">سایر شهرستان ها</option>
                                    </select>
                                    <?php echo $FieldArrayHelp["distric"]; ?>
                                </div>
                                <hr>

                                <div <?php echo $FieldArrayClass["pedmobile"]; ?>>
                                    <?php echo $FieldArrayError["pedmobile"]; ?>
                                    <input id="pedmobile" name="pedmobile" type="text" class="form-control" placeholder="شماره موبایل پدر" <?php if ($_SESSION["Register"]["pedmobile"] != "") echo 'value="' . $_SESSION["Register"]["pedmobile"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["pedmobile"]; ?>
                                </div>

                                <div <?php echo $FieldArrayClass["madmobile"]; ?>>
                                    <?php echo $FieldArrayError["madmobile"]; ?>
                                    <input id="madmobile" name="madmobile" type="text" class="form-control" placeholder="شماره موبایل مادر" <?php if ($_SESSION["Register"]["madmobile"] != "") echo 'value="' . $_SESSION["Register"]["madmobile"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["madmobile"]; ?>
                                </div>

                                <div <?php echo $FieldArrayClass["telephone"]; ?>>
                                    <?php echo $FieldArrayError["telephone"]; ?>
                                    <input id="telephone" name="telephone" type="text" class="form-control" placeholder="شماره تلفن منزل همراه با کد شهر: 02188888888" <?php if ($_SESSION["Register"]["telephone"] != "") echo 'value="' . $_SESSION["Register"]["telephone"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["telephone"]; ?>
                                </div>
                                <div <?php echo $FieldArrayClass["zaroori"]; ?>>
                                    <?php echo $FieldArrayError["zaroori"]; ?>
                                    <input id="zaroori" name="zaroori" type="text" class="form-control" placeholder="شماره تلفن ضروری همراه با کد شهر: 02188888888" <?php if ($_SESSION["Register"]["zaroori"] != "") echo 'value="' . $_SESSION["Register"]["zaroori"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["zaroori"]; ?>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div <?php echo $FieldArrayClass["paye"]; ?>>
                                    <?php echo $FieldArrayError["paye"]; ?>
                                    <select id="paye" name="paye" class="form-control">
                                        <option value="0">متقاضی کدام پایه هستید؟</option>
                                        <?php
                                        foreach ($allgrades as $key => $value) {
                                            if ($_SESSION["Register"]["paye"] == $key) {
                                                echo '<option value="' . $key . '" selected>' . $value . '</option>';
                                            } else {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["paye"]; ?>
                                </div>

                                <div <?php echo $FieldArrayClass["field"]; ?>>
                                    <?php echo $FieldArrayError["field"]; ?>
                                    <select id="field" name="field" class="form-control">
                                        <option value="0">متقاضی کدام رشته هستید؟</option>
                                        <?php
                                        foreach ($allfields as $key => $value) {
                                            if ($_SESSION["Register"]["field"] == $key) {
                                                echo '<option value="' . $key . '" selected>' . $value . '</option>';
                                            } else {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["field"]; ?>
                                </div>


                                <div <?php echo $FieldArrayClass["olaviyat"]; ?>>
                                    <?php echo $FieldArrayError["olaviyat"]; ?>
                                    <select id="olaviyat" name="olaviyat" class="form-control">
                                        <option value="0">متقاضی کدام مدرسه سلام هستید؟</option>
                                        <?php
                                        foreach ($allschools as $key => $value) {

                                            if (!in_array($key, [2, 45])) {
                                                if ($_SESSION["Register"]["olaviyat"] == $key) {
                                                    if ($value[1] == 100) {
                                                        echo '<option value="' . $key . '" selected>' . $value[0] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $key . '" selected>منطقه ' . $value[1] . '-' . $value[0] . '</option>';
                                                    }
                                                } else {
                                                    if ($value[1] == 100) {
                                                        echo '<option value="' . $key . '" >' . $value[0] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $key . '" >منطقه ' . $value[1] . '-' . $value[0] . '</option>';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["olaviyat"]; ?>
                                </div>

                                <hr>
                                <div <?php echo $FieldArrayClass["schoolname"]; ?>>
                                    <?php echo $FieldArrayError["schoolname"]; ?>
                                    <input id="schoolname" name="schoolname" type="text" class="form-control" placeholder="مدرسه فعلی دانش آموز" <?php if ($_SESSION["Register"]["schoolname"] != "") echo 'value="' . $_SESSION["Register"]["schoolname"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-home form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["schoolname"]; ?>
                                </div>
                                <div <?php echo $FieldArrayClass["moaddel"]; ?>>
                                    <?php echo $FieldArrayError["moaddel"]; ?>
                                    <input id="moaddel" name="moaddel" type="text" class="form-control" placeholder="معدل" <?php if ($_SESSION["Register"]["moaddel"] != "") echo 'value="' . $_SESSION["Register"]["moaddel"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-education form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["moaddel"]; ?>
                                </div>
                                <div <?php echo $FieldArrayClass["riazi"]; ?>>
                                    <?php echo $FieldArrayError["riazi"]; ?>
                                    <input id="riazi" name="riazi" type="text" class="form-control" placeholder="<?php echo $RiaziText ?>" <?php if ($_SESSION["Register"]["riazi"] != "") echo 'value="' . $_SESSION["Register"]["riazi"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-education form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["riazi"]; ?>
                                </div>
                                <div <?php echo $FieldArrayClass["oloom"]; ?>>
                                    <?php echo $FieldArrayError["oloom"]; ?>
                                    <input id="oloom" name="oloom" type="text" class="form-control" placeholder="<?php echo $OloomText ?>" <?php if ($_SESSION["Register"]["oloom"] != "") echo 'value="' . $_SESSION["Register"]["oloom"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-education form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["oloom"]; ?>
                                </div>
                                <div <?php echo $FieldArrayClass["enzebat"]; ?>>
                                    <?php echo $FieldArrayError["enzebat"]; ?>
                                    <input id="enzebat" name="enzebat" type="text" class="form-control" placeholder="نمره انضباط" <?php if ($_SESSION["Register"]["enzebat"] != "") echo 'value="' . $_SESSION["Register"]["enzebat"] . '"'; ?> />
                                    <span class="glyphicon glyphicon-education form-control-feedback"></span>
                                    <?php echo $FieldArrayHelp["enzebat"]; ?>
                                </div>


                                <div <?php echo $FieldArrayClass["ashnaee"]; ?>>
                                    <?php echo $FieldArrayError["ashnaee"]; ?>
                                    <select class="form-control" name="ashnaee" id="ashnaee">
                                        <option value="0">چطور با سلام آشنا شدید؟</option>
                                        <?php
                                        foreach ($allintru as $key => $value) {
                                            if ($_SESSION["Register"]["ashnaee"] == $key) {
                                                echo '<option value="' . $key . '" selected>' . $value . '</option>';
                                            } else {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["ashnaee"]; ?>
                                </div>
                                <hr>
                                <div <?php echo $FieldArrayClass["takmil"]; ?>>
                                    <?php echo $FieldArrayError["takmil"]; ?>
                                    <select id="takmil" name="takmil" class="form-control">
                                        <?php
                                        if ($_SESSION["Register"]["takmil"] == 0)
                                            echo '<option value="0">چه کسی فرم را تکمیل کرد؟</option>
										<option value="1">پدر دانش آموز</option>
										<option value="2">مادر دانش آموز</option>
										<option value="3">خود دانش آموز</option>
										<option value="4">سایر</option>';
                                        if ($_SESSION["Register"]["takmil"] == 1)
                                            echo '<option value="0">چه کسی فرم را تکمیل کرد؟</option>
										<option value="1" selected>پدر دانش آموز</option>
										<option value="2">مادر دانش آموز</option>
										<option value="3">خود دانش آموز</option>
										<option value="4">سایر</option>';
                                        if ($_SESSION["Register"]["takmil"] == 2)
                                            echo '<option value="0">چه کسی فرم را تکمیل کرد؟</option>
										<option value="1">پدر دانش آموز</option>
										<option value="2" selected>مادر دانش آموز</option>
										<option value="3">خود دانش آموز</option>
										<option value="4">سایر</option>';
                                        if ($_SESSION["Register"]["takmil"] == 3)
                                            echo '<option value="0">چه کسی فرم را تکمیل کرد؟</option>
										<option value="1">پدر دانش آموز</option>
										<option value="2">مادر دانش آموز</option>
										<option value="3" selected>خود دانش آموز</option>
										<option value="4">سایر</option>';
                                        if ($_SESSION["Register"]["takmil"] == 4)
                                            echo '<option value="0">چه کسی فرم را تکمیل کرد؟</option>
										<option value="1">پدر دانش آموز</option>
										<option value="2">مادر دانش آموز</option>
										<option value="3">خود دانش آموز</option>
										<option value="4" selected>سایر</option>';
                                        ?>
                                    </select>
                                    <?php echo $FieldArrayHelp["takmil"]; ?>
                                </div>
                            </div>


                            <div class="row">
                                <!-- /.col -->
                                <div class="col-xs-12">
                                    <input type="submit" id="NextStep" name="NextStep" class="btn btn-success btn-block btn-flat" value="ثبت نام - مرحله بعدی" />
                                    <input type="submit" id="StepBack" name="StepBack" class="btn btn-primary btn-block btn-flat" value="بازگشت" />

                                </div>
                                <!-- /.col -->
                            </div>
                        </form>


                    </div>

                    <!-- /.form-box -->
                    <style>
                        .material-button-anim {
                            position: relative;
                            padding: 27px 15px 120px;
                            text-align: center;
                            max-width: 320px;
                            margin: 0 auto 7px;
                        }

                        .material-button {
                            position: relative;
                            top: 0;
                            z-index: 1;
                            width: 40px;
                            height: 40px;
                            font-size: 1em;
                            color: #fff;
                            background: #2C98DE;
                            border: none;
                            border-radius: 50%;
                            box-shadow: 0 3px 6px rgba(0, 0, 0, .275);
                            outline: none;
                        }

                        .material-button-toggle {
                            z-index: 3;
                            width: 60px;
                            height: 60px;
                            margin: 0 auto;
                        }

                        .material-button-toggle span {
                            -webkit-transform: none;
                            transform: none;
                            -webkit-transition: -webkit-transform .175s cubic-bazier(.175, .67, .83, .67);
                            transition: transform .175s cubic-bazier(.175, .67, .83, .67);
                        }

                        .material-button-toggle.open {
                            -webkit-transform: scale(1.3, 1.3);
                            transform: scale(1.3, 1.3);
                            -webkit-animation: toggleBtnAnim .175s;
                            animation: toggleBtnAnim .175s;
                        }

                        .material-button-toggle.open span {
                            -webkit-transform: rotate(45deg);
                            transform: rotate(45deg);
                            -webkit-transition: -webkit-transform .175s cubic-bazier(.175, .67, .83, .67);
                            transition: transform .175s cubic-bazier(.175, .67, .83, .67);
                        }

                        #options {
                            height: 20px;
                        }

                        .option {
                            position: relative;
                        }

                        .option .option1,
                        .option .option2,
                        .option .option3 {
                            filter: blur(5px);
                            -webkit-filter: blur(5px);
                            -webkit-transition: all .175s;
                            transition: all .175s;
                        }

                        .option .option1 {
                            -webkit-transform: translate3d(90px, 90px, 0) scale(.8, .8);
                            transform: translate3d(90px, 90px, 0) scale(.8, .8);
                        }

                        .option .option2 {
                            -webkit-transform: translate3d(0, 90px, 0) scale(.8, .8);
                            transform: translate3d(0, 90px, 0) scale(.8, .8);
                        }

                        .option .option3 {
                            -webkit-transform: translate3d(-90px, 90px, 0) scale(.8, .8);
                            transform: translate3d(-90px, 90px, 0) scale(.8, .8);
                        }

                        .option.scale-on .option1,
                        .option.scale-on .option2,
                        .option.scale-on .option3 {
                            filter: blur(0);
                            -webkit-filter: blur(0);
                            -webkit-transform: none;
                            transform: none;
                            -webkit-transition: all .175s;
                            transition: all .175s;
                        }

                        .option.scale-on .option2 {
                            -webkit-transform: translateY(-28px) translateZ(0);
                            transform: translateY(-28px) translateZ(0);
                            -webkit-transition: all .175s;
                            transition: all .175s;
                        }

                        @keyframes toggleBtnAnim {
                            0% {
                                -webkit-transform: scale(1, 1);
                                transform: scale(1, 1);
                            }

                            25% {
                                -webkit-transform: scale(1.4, 1.4);
                                transform: scale(1.4, 1.4);
                            }

                            75% {
                                -webkit-transform: scale(1.2, 1.2);
                                transform: scale(1.2, 1.2);
                            }

                            100% {
                                -webkit-transform: scale(1.3, 1.3);
                                transform: scale(1.3, 1.3);
                            }
                        }

                        @-webkit-keyframes toggleBtnAnim {
                            0% {
                                -webkit-transform: scale(1, 1);
                                transform: scale(1, 1);
                            }

                            25% {
                                -webkit-transform: scale(1.4, 1.4);
                                transform: scale(1.4, 1.4);
                            }

                            75% {
                                -webkit-transform: scale(1.2, 1.2);
                                transform: scale(1.2, 1.2);
                            }

                            100% {
                                -webkit-transform: scale(1.3, 1.3);
                                transform: scale(1.3, 1.3);
                            }
                        }
                    </style>
                    <style>
                        .pinkBg {
                            background-color: #ed184f !important;
                            background-image: linear-gradient(90deg, #2d43fd, #34fdf8);
                        }

                        .intro-banner-vdo-play-btn {
                            height: 60px;
                            width: 60px;
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            text-align: center;
                            margin: -30px 0 0 -30px;
                            border-radius: 100px;
                            z-index: 1
                        }

                        .intro-banner-vdo-play-btn i {
                            line-height: 56px;
                            font-size: 30px
                        }

                        .intro-banner-vdo-play-btn .ripple {
                            position: absolute;
                            width: 120px;
                            height: 120px;
                            z-index: -1;
                            left: 50%;
                            top: 50%;
                            opacity: 0;
                            margin: -59px 0 0 -59px;
                            border-radius: 100px;
                            -webkit-animation: ripple 1.8s infinite;
                            animation: ripple 1.8s infinite
                        }

                        @-webkit-keyframes ripple {
                            0% {
                                opacity: 1;
                                -webkit-transform: scale(0);
                                transform: scale(0)
                            }

                            100% {
                                opacity: 0;
                                -webkit-transform: scale(1);
                                transform: scale(1)
                            }
                        }

                        @keyframes ripple {
                            0% {
                                opacity: 1;
                                -webkit-transform: scale(0);
                                transform: scale(0)
                            }

                            100% {
                                opacity: 0;
                                -webkit-transform: scale(1);
                                transform: scale(1)
                            }
                        }

                        .intro-banner-vdo-play-btn .ripple:nth-child(2) {
                            animation-delay: .3s;
                            -webkit-animation-delay: .3s
                        }

                        .intro-banner-vdo-play-btn .ripple:nth-child(3) {
                            animation-delay: .6s;
                            -webkit-animation-delay: .6s
                        }
                    </style>
                    <script>
                        $(document).ready(function() {
                            $('.material-button-toggle').on("click", function() {
                                $(this).toggleClass('open');
                                $('.option').toggleClass('scale-on');
                                $('.option').toggleClass('hide');
                            });
                        });
                    </script>


                    <div class="material-button-anim">
                        <ul class="list-inline" id="options">
                            <li class="option hide">
                                <a href="tel:02171059">
                                    <button class="material-button option1" type="button" data-toggle="tooltip" title="تماس با دفتر مرکزی">
                                        <span class="fa fa-phone" aria-hidden="true"></span>
                                    </button>
                                </a>
                            </li>
                            <li class="option hide">
                                <button class="material-button option2" type="button" data-toggle="tooltip" title="ارسال پیام">
                                    <span class="fa fa-envelope" aria-hidden="true"></span>
                                </button>
                            </li>
                            <li class="option hide">
                                <span data-toggle="modal" data-target="#modal-default">
                                    <button class="material-button option3" type="button" data-toggle="tooltip" title="سوالات متداول">
                                        <span class="fa fa-question" aria-hidden="true"></span>
                                    </button>
                                </span>
                            </li>
                        </ul>
                        <a class="intro-banner-vdo-play-btn pinkBg" target="_blank">
                            <button class="material-button material-button-toggle" type="button">
                                <span class="fas fa-question-circle fa-2x" aria-hidden="true"></span>
                            </button>

                            <span class="ripple pinkBg"></span>
                            <span class="ripple pinkBg"></span>
                            <span class="ripple pinkBg"></span>
                        </a>
                    </div>

                </section>
                <!-- /.content -->
            </div>
            <!-- /.container -->
        </div>
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">سوالات متداول</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-danger">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                                            سوال1 :هنگام ثبت نام با وارد کردن شماره ملی درفرم ثبت نام ایراد گرفته می شود که شاره کد ملی اشتباه یا تکراری
                                            است.برای این کار چه باید کرد ؟
                                        </a>
                                    </h4>
                                </div>

                                <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="box-body">
                                        پاسخ : در صورتیکه قبلا فرم ثبت نام را تکمیل کرده اید با استفاده از <b class="text-green"> <a href="http://register.salamsch.com/user"> پیگیری ثبت نام </a></b> با وارد کردن شماره ملی می توانید
                                        وارد پنل ثبت نام شوید. در غیر اینصورت با شماره تلفن 71059 دفتر مرکزی مجموعه مدارس سلام تماس حاصل فرمائید.
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-danger">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                                            سوال 2 : در کارنامه ای که از مدرسه دریافت کرده ایم معدل درج نشده است. در فرم ثبت نام در قسمت معدل چه چیزی را
                                            باید وارد کنیم ؟
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="box-body">
                                        پاسخ : با توجه به زمان اعلام معدل از مدرسه مورد نظر در قسمت فیلد معدل می توانید کلمه "نامشخص" را وارد بفرمایید.
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-danger">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                                            سوال 3 : در کارنامه ای که از مدرسه دریافت کرده ایم معدل به صورت توصیفی درج شده است. در فرم ثبت نام در قسمت
                                            معدل چه چیزی را باید وارد کنیم ؟
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="box-body">
                                        پاسخ : با توجه به اینکه معدل به صورت توصیفی بیان شده است در قسمت فیلد معدل کلمه "توصیفی" را وارد بفرمایید.
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-danger">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4" class="collapsed" aria-expanded="false">
                                            سوال 4: پس از ثبت نام در سایت سلام برای من کد تاییدیه ارسال نشده است. باید چه کاری انجام دهم ؟
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse4" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="box-body">
                                        پاسخ : ابتدا وارد لینک ناحیه کاربری شوید و با درج شماره ملی خود، رمز عبور ورود به ناحیه کاربری برای شما ارسال
                                        خواهد شد.سپس بعد از ورود به ناحیه کاربری می توانید کد پیگیری خود را مشاهده کنید.در غیر اینصورت با شماره 71059 دفتر
                                        مرکزی مجموعه مدارس سلام تماس حاصل فرمائید.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- /.content-wrapper -->
        <footer>
            <!--        <strong>مجموعه مدارس سلام</strong>-->
            <!-- <strong>تلفن دفتر مرکزی: 71059-021</strong> -->
            <?php require_once('includes/regFotter.php') ?>


        </footer>
    </div>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112079517-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-112079517-1');


        $(document).ready(function() {

            const year_optionsContainer = document.querySelector('.year-options-container');
            const year_selected = document.querySelector('.year-selected');
            const year_options = document.querySelectorAll('.year-options-container .year-option');


            year_selected.addEventListener('click', () => {
                year_optionsContainer.classList.toggle('active');
                // optionsContainer.classList.toggle('active');

            });

            $(document).mouseup((ev) => {
                if ($(ev.target).closest('.options-container').length === 0) {
                    if ($(document).find('.options-container.active')) {
                        $('.options-container.active').removeClass('active');
                    }
                }
                // deputy_optionsContainer.classList.remove('active');
            })

            year_options.forEach(option => {
                option.addEventListener('click', () => {
                    const label = option.querySelector('label');
                    year_selected.innerHTML = label.innerHTML;
                    year_optionsContainer.classList.remove('active');
                    // console.log($(this).val());
                    // console.log(label.id);
                    year_selected.id = label.id;

                })
            })




        })
    </script>

</body>

</html>