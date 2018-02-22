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
   /*$success = imap_mail(
      "dominik.hillmann@gmx.de", // to
      $_POST["subject"] . " from " . $_POST["firstname"] . $_POST["lastname"], // subject
      $_POST["message"] . "\nReply to " . $_POST["adress"] // message text
   );


   if($success)
      echo '<p>Thank you for your message. I\'ll reply as soon as possible.</p>'
   else
      echo '<p>Something has gone wrong. Please try again later.</p>';
   echo $_POST["firstname"] . " " . $_POST["lastname"]. "\n";
   echo $_POST["address"] . "\n";
   echo $_POST["message"];*/


   $receiver = 'dominik.hillmann.wzl@gmail.com';
   $subject = 'Der Betreff';
   $message = 'Hallo';

   $headers = "From: vweb09.nitrado.net\r\n";
   $headers .= "Reply-To: Dominik Hillmann <dominik.hillmann@gmx.de>\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
   $headers .= "X-Priority: 3\r\n";
   $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

   $success = mail($receiver, $subject, $message, $headers);

   echo ($success ? "<h1>JA</h1>" : "<h1>NEIN</h1>");

  //$headers .= "Reply-To: The Sender <sender@sender.com>\r\n";
  //$headers .= "Return-Path: The Sender <sender@sender.com>\r\n";
  //$headers .= "From: The Sender <senter@sender.com>\r\n";
  //$headers .= "Organization: Sender Organization\r\n";
  ?>
</body>

</html>
