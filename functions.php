<?php
require 'plugins/PHPMailer/src/Exception.php';
require 'plugins/PHPMailer/src/PHPMailer.php';
require 'plugins/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function getAddressCoordinates($address) 
{
	// Encode the address
	$encodedAddress = urlencode($address);
	
	// Nominatim API endpoint
	$url = "https://nominatim.openstreetmap.org/search.php?q=" . $encodedAddress . "&format=jsonv2";

		// Make a request to the API
	$opts = array(
			'http' => array(
					'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.93 Safari/537.36'
			)
		);
		$context = stream_context_create($opts);
		$response = file_get_contents($url, false, $context);
	
	$result = json_decode($response, true);
	if (is_array($result) && !empty($result)) {
		// Extract latitude and longitude
		$latitude = $result[0]['lat'];
		$longitude = $result[0]['lon'];

		return array('latitude' => $latitude, 'longitude' => $longitude);
	}

	return null;
}

function deleteDirectory($dir) 
{
	if (!file_exists($dir)) {
		return true;
	}

	if (!is_dir($dir)) {
		return unlink($dir);
	}

	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') {
			continue;
		}

		if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
			return false;
		}
	}

	return rmdir($dir);
}

function generatePassword() 
{
	// Define the character set for the password
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';

	// Get the total number of characters in the character set
	$characterCount = strlen($characters);

	// Initialize an empty password string
	$password = '';

	// Generate the password
	for ($i = 0; $i <= 8; $i++) {
		// Get a random index within the character set
		$randomIndex = mt_rand(0, $characterCount - 1);

		// Append the randomly selected character to the password string
		$password .= $characters[$randomIndex];
	}

	return $password;
}

function sendEmail($email, $subject, $body, $altbody)
{
	$mail = new PHPMailer(true);

	try {
		//Server settings
    $mail->isSMTP();
		$mail->SMTPDebug = false;
		$mail->Host       = 'smtp.hostinger.com';
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port       = 587;
		$mail->Username   = 'admin@studyhive.social';
		$mail->Password   = 'pP1FRim0ZD9$';

		//Recipients
		$mail->setFrom('admin@studyhive.social', 'StudyHive Admin');
		$mail->addAddress($email);

		//Content
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $body;
		$mail->AltBody = $altbody;

		if ($mail->send()) {
			$success = true;
		}
	} catch (Exception $e) {
		$success = false;
	}
	return $success;
}
?>