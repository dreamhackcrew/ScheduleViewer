<?php

session_start();

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dreamhack Schedule</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 


    <style type="text/css">
      html, body {
        background-color: #eee;
      }
      body {
        padding-top: 40px; 
      }
      .container {
        width: 1500px;
      }

      /* The white background content wrapper */
      .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; 
        -webkit-border-radius: 10px 10px 10px 10px;
           -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

      .login-form {
        margin-left: 20px;
      }
    
      legend {
        margin-right: -50px;
        font-weight: bold;
        color: #404040;
      }

    </style>

</head>
<body>
    <div class="container">
        <h1>DHCC Schedule</h1>
        <div class="content">
            <div class="row">
                <div class="login-form">
                    <?php
                    
                    include('lib/oauth.php');

                    if ( isset($_GET['exit']) )
                        $_SESSION = array();

                    $oauth->set_customer_key('b0d9553a59654eaaae55d368eed01481d2ec5095');
                    $oauth->set_customer_secret('24ab92e544c87b423306b8da595095959f77d4f9');

                    if ( !isset($_SESSION['request']) )
                        $_SESSION['request'] = $oauth->request_token('http://schedule.crew.dreamhack.se/');

                    if ( isset($_SESSION['request']['oauth_token_secret']) )
                        $oauth->set_token_secret($_SESSION['request']['oauth_token_secret']);

                    if ( isset($_SESSION['token']['oauth_token_secret']) )
                        $oauth->set_token($_SESSION['token']['oauth_token']);
                        $oauth->set_token_secret($_SESSION['token']['oauth_token_secret']);

                    if ( isset($_GET['oauth_token']) ) {
                        $_SESSION['auth'] = $_GET;
                    }


                    if ( !isset($_SESSION['auth']) ) {
                        echo '<a href="http://api.crew.dreamhack.se/oauth/authorize?oauth_token='.$_SESSION['request']['oauth_token'].'">Logga in</a>';
                    } elseif ( !isset($_SESSION['token']['oauth_token_secret']) )  {
                        $_SESSION['token'] = $oauth->access_token($_SESSION['auth']['oauth_token'],$_SESSION['auth']['oauth_verifier']);

                        if ( $_SESSION['token']['oauth_problem'] ) {
                        //    $_SESSION = array();
                        }
                    } else {
                        $response = $oauth->get('http://api.crew.dreamhack.se/1/user/get');
                        print_r($response);
                    }
                    
                    echo '<pre>';
                    print_r($_SESSION);
                    echo '</pre>';
                    ?>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
     <script src="http://code.jquery.com/jquery-latest.js"></script>
     <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
