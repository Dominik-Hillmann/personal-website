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
   $success = imap_mail(
      "dominik.hillmann@gmx.de", // to
      $_POST["subject"] . " from " . $_POST["firstname"] . $_POST["lastname"], // subject
      $_POST["message"] . "\nReply to " . $_POST["adress"] // message text
   );


   if($success)
      echo '<p>Thank you for your message. I\'ll reply as soon as possible.</p>'
   else
      echo '<p>Something has gone wrong. Please try again later.</p>';
   ?>
</body>

</html>
