<?php

//File settings
$fileatt = "../uploads/myLovelyFile.pdf";
$fileatttype = "application/octet-stream"; //Octet-stream is a file type to allow all types of files
$fileattname = "myLovelyFile";

//Read file contents
$file = fopen($fileatt, 'rb');
$data = fread($file, filesize($fileatt));
fclose($file);

//Prepare file contents for e-mail
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
$data = chunk_split(base64_encode($data));

$subject = "My Lovely Subject";

$mainMessage = "Hello,"
. "<br/><br/>This is my lovely message."
. "<br/>Please see the attached file."
. "<br/><br/>Best Regards,"
. "<br/>Kim";

$from = "Sandy Sender <sandy@sender.com>";

$to = "Randy Recipient <randy@recipient.com>";

$headers = "From: $from" . "\r\n";
$headers .= 'Reply-To: dispatcher@sender.com' . "\r\n"; //Optional
$headers .= "BCC: barbara@blindcopy.com\r\n"; //Optional

$headers .= "MIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"\n";
$headers .= "Importance: High\n"; //Optional
$message = "This is a multi-part message in MIME format.\n\n" . "–{$mime_boundary}\n"
. "Content-Type: text/html; charset=\"iso-8859-1\n"
. "Content-Transfer-Encoding: 7bit\n\n" . $mainMessage . "\n\n";

//Attach file to e-mail
$message .= "–{$mime_boundary}\n"
. "Content-Type: {$fileatttype};\n" . " name=\"{$fileattname}\"\n"
. "Content-Disposition: attachment;\n" . " filename=\"{$fileattname}\"\n"
. "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n" . "-{$mime_boundary}-\n";

// Send the email
mail($to, $subject, $message, $headers);

?>