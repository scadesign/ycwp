<?php

// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(-1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'config.php';


$json = file_get_contents('php://input');
$data = json_decode($json, true);
$seawatch = $data['seawatch'];

// sanitize the record data
$date = htmlspecialchars($seawatch['date']);
$siteName = htmlspecialchars($seawatch['station']);
$latLong = htmlspecialchars($seawatch['latLong']);
$observer = htmlspecialchars($seawatch['name']);
$email = filter_var(trim($seawatch['email']), FILTER_SANITIZE_EMAIL);
$telephone = htmlspecialchars($seawatch['telephone']);

// build the Email
$recording  = '<h3>'.$observer.'-'.$record['siteName'].'-'.$record['date'].'-landbasedform</h3>';
$recording .='<style> table { font-family: Verdana, Arial, Helvetica, sans-serif; margin-bottom:10px;width:95%; font-size:10pt;}';
$recording  .='td {border: 1px solid;padding:2px 10px;}.underline{background: lightgray;border-bottom:3px solid gray; font-weight:bold;}';
$recording  .='h3 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size:1.3em</style>';
$recording .= '<table><tr class="underline">';
$recording .= '<td>Date</td>';
$recording .= '<td>Site Name</td>';
$recording .= '<td colspan="2">Latitude/Longitude</td>';
$recording .= '</tr><tr>';
$recording .= '<td>'.$date.'</td>';
$recording .= '<td>'.$siteName.'</td>';
$recording .= '<td colspan="2">'.$latLong.'</td>';
$recording .= '</tr>';
$recording .= '<tr class="underline">';
$recording .= '<td>Obs. Name</td>';
$recording .= '<td>Email</td>';
$recording .= '<td>Tel</td>';
$recording .= '</tr><tr>';
$recording .= '<td>'.$observer.'</td>';
$recording .= '<td>'.$email.'</td>';
$recording .= '<td>'.$telephone.'</td>';
$recording .= '</tr></table>';


$env = $data['environment'];
$envDat = '<h3>Effort and environmental data</h3>';

for($i=0; $i < sizeOf($env); $i++){
	$envDat  .= '<table>';
	$envDat .= '<tr class="underline">';
	$envDat .= '<td>GMT/BST?</td>';
	$envDat .= '<td>Start</td>';
	$envDat .= '<td>End</td>';
	$envDat .= '<td>Sea State</td>';
	$envDat .= '<td>Swell Height</td>';
	$envDat .= '<td>Glare</td>';
	$envDat .= '<td>Visibility</td>';
	$envDat .= '<td>Active Vessels</td>';
	$envDat .= '</tr>';
	$envDat .= '<tr>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['zone']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['start']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['end']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['seaState']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['swellHeight']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['glare']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['visibility']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['vessels']).'</td>';
	$envDat .= '<tr><tr>';
	$envDat .= '<td colspan="7" style="border:none;"><b>Additional Notes;</b> '.htmlspecialchars($env[$i]['notes']).'</td>';
	$envDat .= '</tr>';
	$envDat .= '</table>';
}
  
  


$cets = $data['sightings'];

$sighting = '<h3>Sightings</h3>';

	$sighting .= '<table><tr class="underline">';
	$sighting .= '<td>First Seen</td>';
	$sighting .= '<td>Last Seen</td>';
	$sighting .= '<td>Species</td>';
	$sighting .= '<td>Confidence</td>';
	$sighting .= '<td>Group Size</td>';
	$sighting .= '<td>Calves No.</td>';
	$sighting .= '<td>Juveniles No.</td>';
	$sighting .= '<td>Bearing</td>';
	$sighting .= '<td>Distance</td>';
	$sighting .= '<td>Behaviour</td>';
	$sighting .= '<td>Associated birds</td>';
	$sighting .= '</tr>';

for($i=0; $i < sizeOf($cets); $i++){
	$sighting .= '<tr>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['firstSeen']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['lastSeen']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['species']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['confidence']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['groupSize']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['calves']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['juveniles']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['bearing']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['distance']).'km</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['behaviour']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['associatedBirds']).'</td>';
	$sighting .= '</tr>';

}
  $sighting .= '</table></tr></table>';
  

	

	$content = $recording.$envDat.$sighting;


    // Check that data was sent to the mailer.
    if ( empty($record['observer']) OR empty($record['siteName']) OR !filter_var($record['email'], FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "There was a problem with your submission. <br>Please check all the site and observer details have been added.";
        exit;
    }



    $mail = new PHPMailer(true);  // Passing `true` enables exceptions
    try {
        //Server settings
        //$mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP(); 
        $mail->Host = HOST; 
        $mail->SMTPAuth = true;  
        $mail->Username = USER; 
        $mail->Password = PASS; 
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587;  

        //Recipients
        $mail->setFrom(FROM);
        $mail->addAddress($record['email'], $record['observer']);


        //Content
        $mail->isHTML(true);
        $mail->Subject = $record['observer'].'-'.$record['siteName'].'-'.$record['date'].'-landbasedform';
        $mail->Body    = $content;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

        $mail->send();
				echo 'data submitted successfully and has been sent to ' . $record['email'] . ' <br />Thank you';
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }

?>