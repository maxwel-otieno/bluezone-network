<?php 
    include 'config.php';
    require './AutoMailer/smtpmail/PHPMailer.php';

    $Err = "";
    function checkemail($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    //Mail variables
    $mail = new \PHPMailer;

    //Enable SMTP debugging.
    $mail->SMTPDebug = 3;                               
    //Set PHPMailer to use SMTP.
    $mail->isSMTP();            
    //Set SMTP host name                          
    $mail->Host = "mail.bluezonenetwork.co.ke";
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;                          
    //Provide username and password     
    $mail->Username = "maxwelooduor@bluezonenetwork.co.ke";                 
    $mail->Password = "3EkV49LmN";                           
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";                           
    // $mail->SMTPSecure = "ssl";                           
    //Set TCP port to connect to
    $mail->Port = 587;
    // $mail->Port = 465;

    $mail->setFrom('maxwelooduor@bluezonenetwork.co.ke', 'BluezoneNetwork');
  	$mail->addAddress('maxweloduoro@gmail.com', 'My Friend');
    
    if(isset($_POST['sbt-contact'])){
        // echo "Ni kuzuri. Thank you God.";
        $username = $_POST['fullname'];
        $mobile = $_POST['phone'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $Err = "<span class='alert d-flex justify-content-center mb-5 spanAlert'>The username already exists.</span>";


        // check if the message already exists
        // `fullname`, `email`, `phone`, `message`, `date_created`
        $check_msg = $pdo->prepare("SELECT * FROM `messages` WHERE full_name=? AND email=? AND message=? AND phone=?");
        $check_msg->execute([$username, $email, $message, $mobile]);
        // print_r($check_msg->fetchAll(PDO::FETCH_OBJ));

        if($check_msg->rowCount() <= 0){
            $insert_comment->execute([$username, $email, $mobile, $message]);

            if($insert_comment->rowCount() > 0){
                $Err = "<span class='alert d-flex justify-content-center mb-5 spanAlert'>Message sent successfully...</span>";                
            }else{
                $Err = "<span class='alert d-flex justify-content-center mb-5 spanWarning'>There was an error trying to send your message. Try agian later.</span>";
            }
        }else{
            $Err = "<span class='alert d-flex justify-content-center mb-5 spanWarning'>This message has already been received.</span>";
        }
    }

    if(isset($_POST['sbt-package'])){
        $username = $_POST['fullname'];
        $mobile = substr($_POST['phone'], 1);
        $bundle = $_POST['package'];

        //get the bundle ID
        $query_bundle->execute([strtolower($bundle)]);
        $row_bundle = $query_bundle->fetch(PDO::FETCH_OBJ);
        $bundle_id = $row_bundle->id;
    
        $mail->Subject  = "New Package Order";
        $mail->Body     = "Name: $username.\nPhone: $mobile.\nPackage requested:$bundle";

        //check if the package order exists
        $check_pkg = $pdo->prepare("SELECT * FROM `pkg_order` WHERE username=? AND pkg_id=? AND phone=?");
        $check_pkg->execute([$username, $bundle_id, $mobile]);
        // print_r($check_pkg->fetch(PDO::FETCH_OBJ));    

        if($check_pkg->rowCount() <= 0){          

            if(!$mail->send()) {
                //echo 'Message was not sent.'
                echo 'Mailer error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent.';
            }          
            $pkg_order->execute([$username, $mobile, $bundle_id]);

            if($pkg_order->rowCount() > 0){
                $Err = "<span class='alert d-flex justify-content-center mb-5 spanAlert'>Thank you $username, We'll reach out to you soon.</span>";                
            }else{
                $Err = "<span class='alert d-flex justify-content-center mb-5 spanWarning'>There was an error trying to place make your order.</span>";
            }
        }else{
            $Err = "<span class='alert d-flex justify-content-center mb-5 spanWarning'>Your enquiry has already been received.</span>";
        }
        // echo "Hallo";
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Box icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Roboto+Mono:300,400,500"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,500;0,900;1,600&display=swap" rel="stylesheet">
    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="./css/stylesheet.css?v=1.7">

    <!-- icon -->
    <link rel="icon" type="image/png" sizes="192x192"  href="./images/logo.png">
    <style>
        html{
            scroll-behavior: smooth;
        }
        .headers_min{
            font-size: 15px;
            letter-spacing: 2px;
            color: orange; 
            border: 3px solid orange; 
            padding: 10px 30px; 
            margin-bottom: 2rem;
        }
        .small-container{
            width: 80%; 
            margin: auto;
        }
        /* The Modal (background) */
        .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 30%;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s
        }
        .spanAlert{
            font-size:16px; width: 60%; margin: auto; margin-top: 2rem; background-color:#2AAA00; color:#fff; border-radius:6px; padding-left:5rem; padding-right:5rem;
        }
        .spanWarning{
            font-size: 16px; width: 60%; margin: auto; margin-top: 2rem; background-color:#c10717; color:#fff; border-radius:6px; padding-left:5rem; padding-right:5rem;
        }
        .header{
            background:url(./images/bluezone_1.png); background-color: #cccccc; background-position: center; background-repeat: no-repeat; background-size: cover; position: relative;
        }
        #my_header{
            margin-top: 7rem;
        }

        #header_cont{
            background-color: #f4f4f4; opacity: 0.7; z-index: 100; margin-top: 13vh; padding: 0 10px;
        }
        #header_cont h1{
            font-weight: 1000; color: #027996; font-size: 20px; margin-bottom: 3rem;
        }
        #header_cont p{
            color: black; font-size: 16px;
        }
        .p_quote{
            color: white; font-size: 14px;
            margin-top: 23vh; 
            text-align: center;
        }
        #about{
            min-height: 90vh; background-color: #027996;
        }
        .blue-small{
            width: 60%; margin: auto;
        }
        .blue-small p{
            margin: 3rem 0; font-size: 1.8rem; color: #f4f4f4; line-height: 1cm; letter-spacing: 0.2rem; text-align: center;
        }

        /* PRODUCTS */
        #products{
            background: url('./images/grey_blue_bg_2.jpg'); min-height: 200px;
            padding-bottom: 1rem;
        }
        #internet-package{
            padding: 40px 0 20px 0;
        }
        .cardYangu{
            width: 100%;
            min-height: 40rem;
            background-color: #f4f4f4;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        .cardYangu:hover{
            min-height: 30rem;
            box-shadow: 0 15px 22px 0 rgba(0, 0, 0, 0.8), 0 10px 40px 0 rgba(0, 0, 0, 0.35);
        }
        .cardYangu:first-child h1{
            padding: 10px 15px; color: black; font-size: 25px;
        }
        #package_1{
            font-size: 135px;
        }
        .package_11{
            font-size: 24px;
        }
        #package_2{
            font-size: 165px;
        }
        #package_3{
            font-size: 125px;
        }
        .btn-amount{
            font-size: 18px; padding: 12px 36px; border-radius: 6px; color: white;
        }
        .card-body{
            text-align: left; padding-left: 3rem;
        }
        .card-body span{
            font-size: 15px;
        }
        .get-btn{
            font-size: 15px;
        }
        .gold_edit{
            padding: 5px 0;
        }
        .silver-space{
            margin-top: 3.5rem;
        }
        .bronze-space{
            margin-top: 5.5rem;
        }

        /* FEATURES */
        #features{
            background-color: #243943; min-height: 100vh;
            padding: 10px 0;
        }
        .feature-space{
            padding: 25px 0;
        }
        .feature-space img{
            border-radius: 100%; margin-bottom: 4rem;
        }
        .feature-space p{
            color: white; font-size: 14px;
            /* line-height: 0.8cm; */
        }

        /* CONTACT */
        #contact{
            min-height: 100vh;
        }
        form label{
            font-size: 14px;
        }
        .form-control{
            height: 3rem; font-size: 15px;
            margin-bottom: 1rem;
        }
        .form-control:focus{
            border: 2px solid #027996;
        }
        .f-mes{
            font-size: 15px;
        }
        .send-mes{
            font-size: 15px;
            text-transform: uppercase;
            background-color: #027996;
            padding: 7px 35px;
            color: white;
            border: none;
        }

        /* FOOTER */
        .company-name{
            font-size: 17px; letter-spacing: 0.3rem;
        }

        @media only screen and (max-width: 567px){
            .header{
                height: 100vh;
            }
            .small-container{
                width: 90% !important;
                margin: auto;
            }
            .spanAlert{
                width: 95%;
                font-size: 15px;
            }
            #my_header{
                /* margin-top: 20px; */
                padding: 0 1rem;
            }
            #header_cont{
                margin-top: 2.5vh;
                padding: 0 2rem;
                /* display: none; */
            }
            #header_cont h1{
                font-size: 20px;
            }
            #header_cont p{
                font-size: 15px;
            }
            .p_quote{
                /* font-size: 14px; */
                margin-top: 33vh;
            }
            #about p{
                font-size: 15px !important;
            }
            .blue-small{
                width: 100%;
                margin: auto;
                padding-bottom: -20px;
            }
            .blue-small p{
                font-size: 16px !important; line-height: 0.9cm; color: #f4f4f4 !important; letter-spacing: 0.2rem; text-align: center;
                margin-top: 4rem;
                /* margin-top: -7px; */
            }

            /* PRODUCTS */
            .cardYangu{
                min-height: 40rem;
                width: 80%;
                margin: auto;
                padding-bottom: 0;
                margin-bottom: 2.5rem;
            }
            .cardYangu:first-child h1{
                padding: 8px 16px;
                font-size: 27px;
            }
            .headers_min{
                margin-top: -30px !important;2
            }
            #internet-package{
                width: 90%;
                margin: auto;
                padding: 40px 0 20px 0;
            }
            #package_1{
                font-size: 130px;
            }
            .package_1.1{
                font-size: 28px !important;
            }
            #package_2{
                font-size: 145px;
            }
            #package_3{
                font-size: 115px;
            }
            .btn-amount{       
                font-size: 19px; padding: 10px 27px; border-radius: 7px;
            }
            .card-body{
                text-align: left; padding-left: 1.5rem;
            }
            .card-body span{
                font-size: 15px;
            }
            .get-btn{
                font-size: 15px;
            }
            .gold_edit{
                padding: 0;
            }
            .silver-space{
                margin-top: -10px;
            }
            .bronze-space{
                margin-top: 0rem;
            }

            /* FEATURES */
            .feature-space{
                padding: 5px 0;
            }
            .feature-space img{
                border-radius: 100%;
                width: 180px;
                margin-bottom: 0rem;
            }
            .feature-space p{
                font-size: 15px; line-height: 0.5cm;
            }
            .form-control{
                height: 3rem; font-size: 14px;
                border: ;
                margin-bottom: 7px;
            }
            .form-cotrol:focus{
                border: 2px solid green;
            }
            .f-mes{
                font-size: 14px;
            }
            .send-mes{
                font-size: 15px;
                background-color: #027996;
                text-transform: none;
                padding: 5px 15px;
                color: white;
                border: none;
            }

            /* FOOTER */
            .company-name{
                font-size: 16px; 
                letter-spacing: 0.1rem;
            }
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
        from {top:-300px; opacity:0} 
        to {top:0; opacity:1}
        }

        @keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
        }

        /* The Close Button */
        .close {
            position: relative;
            right: 0;
            top: 10;
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        
        .closed {
            position: relative;
            right: 0;
            top: 1;
            color: white;
            float: right;
            font-size: 20px;
            font-weight: bold;
        }

        .closed:hover,
        .closed:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            padding: 10px 16px;
            background-color: #027996;
            color: white;
            font-size: 20px;
        }

        .modal-body {padding: 2px 16px;}

        }
        .sectionToMinBlack td{
            font-size: 22px;
        }
        .small-container{
            width: 80%;
            margin: auto;
        }
        @media only screen and (max-width: 768px){
            .container{
                width: 100%;
            }
            .small-container{
                width: 90%;
            }
            .menu h1{
                color: white;
            }
            .modal-content{
                width: 90%;
            }
            .headers_min{
                font-size: 14px;
            }
        }

        @media only screen and (max-width: 992px){
            .feature-space img{
                border-radius: 100%; margin-bottom: 4rem;
                width: 50%;
            }
        }
    </style>
    <title>Bluezone</title>
</head>
<body style="background-color: white; color: black; overflow-x: hidden">
    <!-- header -->
    <header id='home' class='header'>
        <!-- Navigation -->
        <nav class="nav" style="background-color: #fff; color: black;">
            <div class="navigation container">
                <div class="logo">
                    <h1><span style="color: #027996;">Bluezone</span> Network</h1>
                </div>
                <div class="menu">
                    <div class="top-nav">
                        <div class="logo">
                            <h1><span style="color: #027996;">Bluezone</span> Network</h1>
                        </div>
                        <div class="close">
                            <i class='bx bx-x' ></i>
                        </div>
                    </div>

                    <ul class="nav-list">
                        <li class="nav-item active">
                            <a href="#home" class="nav-link scroll-link" style="font-size:18px;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="#about" class="nav-link scroll-link" style="font-size:18px;">About</a>
                        </li>
                        <li class="nav-item">
                            <a href="#products" class="nav-link scroll-link" style="font-size:18px;">Packages</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="livescore.php" class="nav-link scroll-link">Livescore</a>
                        </li> -->
                        <li class="nav-item">
                            <a href="#features" class="nav-link scroll-link" style="font-size:18px;">Features</a>
                        </li>
                        <li class="nav-item">
                            <a href="#contact" class="nav-link scroll-link" style="font-size:18px;">Contact Us</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="cart.html" class="nav-link icon"><i class="bx bx-shopping-bag"></i></a>
                        </li> -->
                    </ul>
                </div>
                <!-- 
                <a href="cart.html" class="cart-icon">
                    <i class="bx bx-shopping-bag"></i>
                </a> -->

                <div class="hamburger text-white">
                    <i class="bx bx-menu"></i>
                </div>
            </div>
        </nav>
            <?php if(!empty($Err)){
                echo $Err;
            } ?>
        
        <div class="row pb-5">
            <div class="col-md-12 col-lg-1"></div>
            <div class="col-md-12 col-lg-4" id="my_header">
                <div class="container" id="header_cont"><br>
                    <h1>Fast and Reliable Internet</h1>
                    <p>Enjoy fast and reliable internet speeds for:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Seamless browsing<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Zoom meetings<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Stream mvoies</p><br>
                </div>
            </div>
            <div class="col-md-12 col-lg-2"></div>
            <div class="col-md-12 col-lg-4"></div>
            <div class="col-md-12 col-md-1"></div>
        </div>
        <p class="p_quote">"Customer Service is not a department, it's an attitude." We are committed to providing you with the most reliable high speed Internet for your home & business</p>
    </header>

    <div id="about">
        <div class="small-container">
            <div class="section sectionToMin">
                <div class="d-flex justify-content-center">
                    <span class="headers_min">ABOUT US</span><br><br>
                </div>
                <div class="blue-small">
                    <p>Bluezone Network Internet Service offers Business/Home fixed wireless internet with unlimited bandwidth, true network redundancy, and guaranteed speeds reaching up to 500 Mbps. Backed by a Carrier-grade Service Level Agreement boasting 99.999% uptime and 24/7 in-house customer support, we are proud to offer the most resilient and scalable business/home fixed wireless network on the market. Best of all, our installation can have you up and running in as little as 3 hours!</p>
                </div>
            </div>       <!--Current odds preview section end --> 
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-dialog-centered">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>ORDER YOUR PACKAGE</h3>                           
                    <span class="closed">&times;</span>
                </div>
                <div class="modal-body">          
                    <form action="index1.php" method="post" class="p-5 border m-4">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="font-weight-bold" for="fullname">Full Name</label>
                                <input type="text"  class="form-control" placeholder="" name="fullname" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                            <label class="font-weight-bold" for="phone">Mobile Number</label>
                            <input type="text" class="form-control" placeholder="E.g 0701234567" maxlength="10" name="phone" required>
                            </div>
                        </div>  

                        <div class="row form-group">
                            <div class="col-md-12">
                            <label class="font-weight-bold" for="package">Package</label>
                            <select name="package" class="form-control" required>
                                <option value="Gold">Gold &nbsp;&nbsp;- &nbsp;&nbsp;4500</option>
                                <option value="Silver">Silver &nbsp;&nbsp;- &nbsp;&nbsp; 2000</option>
                                <option value="Bronze">Bronze &nbsp;&nbsp;- &nbsp;&nbsp; 1500</option>
                            </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                            <input style="cursor: pointer;" type="submit" value="ORDER" class="send-mes rounded-0" name="sbt-package">
                            </div>
                        </div>        
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="products">
        <!-- <div> -->
        <!-- <div style="background-color: #f4f4f4; opacity: 0.3;"> -->
        <div class="small-container" id="internet-package">
            <div class="d-flex justify-content-center py-5">
                <span class="headers_min">INTERNET PACKAGES</span>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-4 mb-5 text-center silver-space">
                    <div class="cardYangu">
                        <div><h1 style="background-color: #BCC2D1;">SILVER</h1></div>
                        <div class="text-center">
                            <span id="package_1">8</span><span class="package_11">mbps</span>
                            <div><span style="background-color: #BCC2D1;" class="btn-amount">Ksh 2000</span></div>
                        </div><br>
                        <div class="card-body">
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>High speed downloads</span><br>
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>Super fast browsing</span><br>
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>SD TV programming</span><br><br>
                        </div>
                            <div onclick="clickedBtn(2)" class="btn btn-block get-btn" style="background-color: #BCC2D1;">Get SILVER</div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 mb-5 text-center">
                    <div class="cardYangu">
                        <div><h1 style="background-color: #E7BF26;">GOLD</h1></div>
                        <div class="text-center gold_edit"><span id="package_2">30</span><span class="package_11">mbps</span>
                            <div><span class="btn-amount" style="background-color: #E7BF26;">Ksh 4500</span></div>
                        </div><br>
                        <div class="card-body">
                            <!-- <a class="card-text" href="#"><h1>Betting tips for beginners</h1></a> -->
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>High speed downloads</span><br>
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>Super fast browsing</span><br>
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>UHD TV programming</span><br>
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>UHD movie and music<span><br>
                        </div>
                        <!-- <a href ="./articles.php?articleID=gold" style="text-decoration: none;"> -->
                            <div onclick="clickedBtn(1)" class="btn btn-block get-btn" style="background-color: #E7BF26;">Get GOLD</div>
                        <!-- </a> -->
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 mb-5 text-center bronze-space">
                    <!-- <a href ="./articles.php?articleID=1" style="text-decoration: none;"> -->
                    <div class="cardYangu">
                        <!-- <img class="card-img-top" src="./images/online_betting.jpg" alt="Card image cap"> -->                        
                        <div><h1 style="background-color: #B7541C;">BRONZE</h1></div>
                        <div class="text-center"><span id="package_3">5</span><span class="package_11">mbps</span>
                            <div><span style="background-color: #B7541C;" class="btn-amount">Ksh 1500</span></div>
                        </div><br>
                        <div class="card-body">
                            <!-- <a class="card-text" href="#"><h1>Betting tips for beginners</h1></a> -->
                            <i class="fa fa-star" aria-hidden="true"></i>&nbsp; &nbsp;<span>High speed downloads</span><br>
                            <i class="fa fa-star" aria-hidden="true"></i> &nbsp; &nbsp;<span>Super fast browsing</span><br><br>
                            <!-- <i class="fa fa-star d-none" aria-hidden="true"></i> &nbsp; &nbsp;<br> -->
                        </div>
                        <div onclick="clickedBtn(3)" class="btn btn-block get-btn" style="background-color: #B7541C;">Get BRONZE</div>
                    </div>
                <!-- </a> -->
                </div>
            </div>
        </div> 
        <!-- </div>            -->
    </div>

    <div id="features">
        <!-- <div> -->
        <!-- <div style="background-color: #f4f4f4; opacity: 0.3;"> -->
        <div class="small-container py-5">
            <div class="d-flex justify-content-center py-5">
                <span class="headers_min mb-5">FEATURES</span><br><br>
            </div>
            <div class="row feature-space">
                <div class="col-md-1"></div>
                <div class="col-md-5 col-lg-2 text-center my-5">
                    <img src="./images/features_1.jpg" alt=""><br><br>
                    <p>Video conferencing and online meetings from the comfort of your office or home.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5 col-lg-2 text-center my-5">
                    <img src="./images/features_2.jpg" alt=""><br><br>
                    <p>Connect and celebrate special moments with family anywhere in the world at anytime.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5 col-lg-2 text-center my-5">
                    <img src="./images/features_3.jpg" alt=""><br><br>
                    <p>Work online and access learning materials and platforms online at efficient speeds.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5 col-lg-2 text-center my-5">
                    <img src="./images/features_4.jpg" alt="Image here"><br><br>
                    <p>Enjoy a great Movie and Music streaming experience together with friends.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="contact">
        <!-- <div> -->
        <!-- <div style="background-color: #f4f4f4; opacity: 0.3;"> -->
        <div class="small-container py-5">
            <div class="d-flex justify-content-center pt-5">
                <span class="headers_min">CONTACT US</span><br><br>
            </div>
            <div class="row">        
                <div class="col-md-12 col-lg-8 mb-5">          
                    <form action="index1.php" method="post" class="p-5 bg-white border">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="font-weight-bold" for="fullname">Full Name</label>
                                <input type="text"  class="form-control" placeholder="" name="fullname" minlength="3" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                            <label class="font-weight-bold" for="email">Email</label>
                            <input type="email" id="email" class="form-control" placeholder="" name="email" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                            <label class="font-weight-bold" for="phone">Mobile Number</label>
                            <input type="tel" class="form-control" placeholder="E.g 0701234567" minlength="10" maxlength="10" name="phone" required>
                            </div>
                        </div>               

                        <div class="row form-group">
                            <div class="col-md-12">
                            <label class="font-weight-bold" for="message">Message</label> 
                            <textarea name="message" cols="30" rows="5" class="form-control f-mes" placeholder="Say hello to us ..." name="message" required></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                            <input type="submit" value="Send Message" class="send-mes rounded-0" name="sbt-contact">
                            </div>
                        </div>        
                    </form>
                </div>

                <div class="col-lg-4">                    
                    <div id="map"></div>
                    <div class="p-4 mb-3 bg-white">
                    <h1 class="h1 text-black mb-5 text-uppercase">Contact Info</h1>
                    <p class="h3 mb-0 font-weight-bold">Address</p>
                    <p class="mb-4" style="font-size: 15px;">1st avenue, off outering rd</p>

                    <p class="h3 mb-0 font-weight-bold">Phone</p>
                    <p class="h5 mb-4" style="font-size: 15px;">+254 718 172 354</p>

                    <p class="h3 mb-0 font-weight-bold">Email Address</p>
                    <p class="mb-0" style="font-size: 15px;"><a href="#">admin@bluezonenetwork.co.ke</a></p>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="footer" class="section footer">
        <div class="container">
            <div class="text-center text-white">
                <span class="company-name">Created by S-Tec. @<?php echo date('Y');?></span>
            </div>
        </div>
    </footer>

    <!-- Custom script -->    
    <script>
        //colors
        const colors = ['#E7BF26', '#BCC2D1', '#B7541C'];
        // console.log(colors);

        var modal_header = document.getElementsByClassName('modal-header');
        console.log(modal_header);

        // Get the modal
        var modal = document.getElementById("myModal");
        // console.log(modal);

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn1");

        // Get the <span> element that closes the modal
        var span = document.querySelector(".closed");
        console.log(span);

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
        modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
        modal.style.display = "none";
        console.log(span);
        }

        function clickedBtn(a){
            modal.style.display = "block";
                // console.log(a);
            span.onclick = function() {
                console.log('Hello');
            modal.style.display = "none";
            }

            var selectedItem = document.querySelectorAll('option')[a-1];
            selectedItem.selected = 'selected';
            // console.log(selectedItem);

            // modal_header.style.backgroundColor = colors[a];
            // var body = document.querySelector('body');
            // console.log(modal_header.style);
        }
    </script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.6.1/gsap.min.js"></script>
    <!-- Custom Script -->
    <script src="./js/index.js"></script>
</body>
</html>