<?php
function send_email($client_email, $client_name, $user_filename, $message){
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'PHPMailer/PHPMailerAutoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'sg1-ss1.a2hosting.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'autoreply@casascholarship.com';                     // SMTP username
    $mail->Password   = '6~2Aq-o%RtZq';                               // SMTP password
    $mail->SMTPSecure =  'ssl'; //PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465; //587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom($client_email, 'Message from  '. $client_name . ' ');
    $mail->addAddress('lynnsoft33@yahoo.com', 'Support Team');     // Add a recipient
   // $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo($client_email, $client_name);
 //   $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');


  //$file_path  ="user_files/". $user_filename;

  // try is code
  if(strlen($user_filename) > 0) {
 
    $file_path = 'user_files/'.$user_filename;

    $mail->addAttachment($file_path);
  }

  //******  */

    // Attachments
  // $mail->addAttachment($file_path);         // Add attachments
  //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Message from client';
    $mail->Body    = '$message';
    $mail->AltBody = '$message';

    $mail->send();
    echo '<div class="alert alert-success">    Message has been sent </div>';
} catch (Exception $e) {
    echo "<div class='alert alert-warning'> Message could not be sent. Mailer Error: {$mail->ErrorInfo} </div>";
}

}