<?php
/**
 * Created by PhpStorm.
 * User: achsuthanmahendran
 * Date: 8/10/17
 * Time: 5:20 PM
*/
$output=$_GET["output"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Behaviour Details</title></title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Google fonts -->
    <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
    <!-- modernizr -->
    <script src="js/modernizr.js"></script>
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body style="width: 3500px; text-align: center">
<!-- ====================================================
header section -->

<div class="banner" id="home"></div>
<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="top-nav"></div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 no-padding">
                <div class="col-md-12 text-center user">
                    <h1>E-Secure</h1>
                    <h4>We help you to identify  malicious files in your computer system and email</h4>
                    <br/><br/><br/><br/><br/><br/><br/><br/><br/>
                </div>
                <!-- nav starts here -->
            </div>
        </div>
    </div>
</header><!-- end of header section -->

<!-- about section -->
<section class="about text-center" style="width: 1250px; font-size: 10px;">
    <div class="container">
        <div class="row">
            <br/><br/><br/><br/><br/><br/><br/><br/><br/>
            <div id="csv">

            </div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/>

        </div>


    </div>
</section>

<footer class="footer">
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <br/>
                <div class="col-md-6">
                    <p>&copy; 2017 — Designed &amp; Developed by E-Secure</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- script tags
============================================================= -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/jquery.mixitup.js"></script>
<script src="js/smoothscroll.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>


<script type="text/javascript">
    if (sessionStorage.user =="" || sessionStorage.user==null)
    {
        window.location.href = "../Login/index.html";
    }
    else {
        var htmlString = "<?php echo $output; ?>";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != "") {
                    console.log(this.responseText);
                    document.getElementById("csv").innerHTML = this.responseText;
                }
            }
        };
        xmlhttp.open("GET", "http://192.168.172.140/SLIIT/E-Sucure/get_csv_details.php?id=" + htmlString);
        xmlhttp.send();
    }
</script>

</body>
</html>
