<!DOCTYPE html>
<html lang="de">
<head>
   <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>Dominik Hillmann</title>
    <meta name="description" content=""> <!-- DO NOT FORGET DESCRIPTION-->
    <meta name="author" content="Dominik Hillmann">

   <!-- Mobile Specific Metas -->
    <meta>

   <!-- CSS links -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/mobile.css">

   <!-- Favicon -->
    <link rel="icon" type="image/png" href="">
</head>

<body>
   <?php
   $receiver = "dominik.hillmann.website@gmail.com";
   $subject = $_POST["subject"];
   $message = $_POST["message"];

   echo "TO: " . $receiver;
   foreach($_POST as $ele) {
      echo $ele . "<br>";
   }

   $message .= "\nvon" . $_POST["firstname"] . " " . $_POST["lastname"] . " <" .  $_POST["address"] . ">\r\n";
   //$headers = "From: vweb09.nitrado.net\r\n";
   $headers = "Reply-To: " . $_POST["firstname"] . " " . $_POST["lastname"] . " <" .  $_POST["address"] . ">\r\n";//"Reply-To: Dominik Hillmann <dominik.hillmann@gmx.de>\r\n";
   $headers .= "Return-Path: " . $_POST["firstname"] . " " . $_POST["lastname"] . " <" .  $_POST["address"] . ">\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
   $headers .= "X-Priority: 3\r\n";
   $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

   $allSet = TRUE;
   foreach($_POST as $ele) {
      if(!isset($ele)) {
         $allSet = FALSE;
         break;
      }
   }

   $success;
   if($allSet)
      $success = mail($receiver, $subject, $message, $headers);
   else
      echo "<h1>ACHTUNG</h1><p>Fehler</p><br>";

   echo($success ? "<h1>JA</h1>" : "<h1>NEIN</h1>");
   //echo "Reply-To: " . $_POST["firstname"] . " " . $_POST["lastname"] . " <" .  $_POST["address"] . ">\r\n";
   echo  $_POST["address"];
   echo "ISSET: " . isset($_POST["address"]);

  //$headers .= "Reply-To: The Sender <sender@sender.com>\r\n";
  //$headers .= "Return-Path: The Sender <sender@sender.com>\r\n";
  //$headers .= "From: The Sender <senter@sender.com>\r\n";
  //$headers .= "Organization: Sender Organization\r\n";
  ?>
</body>

</html>
