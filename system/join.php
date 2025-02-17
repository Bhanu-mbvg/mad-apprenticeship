<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$data = $_REQUEST;
if(empty($data['name'])) {
	// Takes raw data from the request
	$json = file_get_contents('php://input');
	$data = json_decode($json, true);
}

// Data edit before API Push...
// If people mark volunteer experiance as 'yes' and they didn't write anything, that should be sent to API
if(!empty($data['volunteering_experience'])) {
	if($data['volunteering_experience'] == 'Yes' and !empty($data['volunteering_experience_details'])) {
		$data['volunteering_experience'] = $data['volunteering_experience_details'];
	}
}

// Why MAD should be joined and send as a json string
if(!empty($data['why_mad_other'])) {
	$data['why_mad'] = "Other: " . $data['why_mad_other']; 
}

// var_dump($data);exit;

$client = new Client(['http_errors' => false]); //GuzzleHttp\Client
$response = '';
try {
    $result = $client->post('https://makeadiff.in/api/v1/users', [
        'form_params' => [
			"name" 		=> $data['name'],
			"sex" 		=> $data['sex'],
			"email" 	=> $data['email'],
			"phone" 	=> $data['phone'],
			"city_id" 	=> $data['city_id'],
			"dob" 	=> $data['dob'],
			"source" 	=> isset($data['source']) ? $data['source'] : '',
			"applied_role" 	=> isset($data['applied_role']) ? $data['applied_role'] : '',
			"applied_role_secondary" 	=> isset($data['applied_role_secondary']) ? $data['applied_role_secondary'] : '',
			"company" 	=> isset($data['company']) ? $data['company'] : '',
			"edu_institution" 	=> isset($data['edu_institution']) ? $data['edu_institution'] : '',
			"edu_course"=> isset($data['edu_course']) ? $data['edu_course'] : '',
			"edu_year" 	=> isset($data['edu_year']) ? $data['edu_year'] : '',
			"address" 	=> isset($data['address']) ? $data['address'] : '',
			"job_status"=> $data['job_status'],
			"why_mad" 	=> $data['why_mad'],
			"volunteering_experience" 	=> $data['volunteering_experience'],
			"campaign" 	=> isset($data['campaign']) ? $data['campaign'] : '',
			'user_type'	=> 'applicant',
			"password" 	=> "pass"
        ],
        'auth' => ['data.simulation@makeadiff.in', 'pass']
    ]);
    $response = $result->getBody();
} catch (Exception $e) {
    // Can't send data to Zoho
    echo $response;
} finally {
    if ($response) {

    	echo $response; //json_encode(['status' => 'success', 'message' => "Volunteer Added"]);
	}
}