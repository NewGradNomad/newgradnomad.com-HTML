<?php
//includes database connection
require_once '../components/db_connect.php';
require_once '../vendor/autoload.php';
require_once '../components/secrets.php';
require_once '../components/domain.php';
//includes session info
session_start();

$stripe = new \Stripe\StripeClient($stripeSecretKey);
header('Content-Type: application/json');

try {
  // retrieve JSON from POST body
  $jsonStr = file_get_contents('php://input');
  $jsonObj = json_decode($jsonStr);

  $session = $stripe->checkout->sessions->retrieve($jsonObj->session_id);

  if (!empty($_SESSION['listingData'])) {
    // Define expected columns and their corresponding session data indices
    $columns = [
      'listingNumber' => 0,
      'companyName' => 1,
      'positionName' => 2,
      'positionType' => 3,
      'primaryTag' => 4,
      'keywords' => 5,
      'support' => 6,
      'pin' => 7,
      'appURL' => 8,
      'appEmail' => 9,
      'combinedSalaryRange' => 10,
      'jobDesc' => 11,
      'date' => 12,
      'paymentStatus' => 13
    ];

    // Validate required fields
    foreach ($columns as $column => $index) {
      if (!isset($_SESSION['listingData'][$index]) || empty($_SESSION['listingData'][$index])) {
        throw new Exception("Missing required field: {$column}");
      }
    }

    // Prepare the query with bindParam
    $query = $db->prepare("INSERT INTO jobListings VALUES (:listingNumber, :companyName, :positionName, :positionType, :primaryTag, :keywords, :support, :pin, :appURL, :appEmail, :combinedSalaryRange, :jobDesc, :date, :paymentStatus)");
    $query->bindParam(':listingNumber', $_SESSION['listingData'][0]);
    $query->bindParam(':companyName', $_SESSION['listingData'][1]);
    $query->bindParam(':positionName', $_SESSION['listingData'][2]);
    $query->bindParam(':positionType', $_SESSION['listingData'][3]);
    $query->bindParam(':primaryTag', $_SESSION['listingData'][4]);
    $query->bindParam(':keywords', $_SESSION['listingData'][5]);
    $query->bindParam(':support', $_SESSION['listingData'][6]);
    $query->bindParam(':pin', $_SESSION['listingData'][7]);
    $query->bindParam(':appURL', $_SESSION['listingData'][8]);
    $query->bindParam(':appEmail', $_SESSION['listingData'][9]);
    $query->bindParam(':combinedSalaryRange', $_SESSION['listingData'][10]);
    $query->bindParam(':jobDesc', $_SESSION['listingData'][11]);
    $query->bindParam(':date', $_SESSION['listingData'][12]);
    $query->bindParam(':paymentStatus', $_SESSION['listingData'][13]);

    // Execute the query
    if ($query->execute()) {
      $_SESSION['listingSuccess'] = true;
    } else {
      $_SESSION['contactSupport'] = true;
      $_SESSION['listingID'] = $_SESSION['listingNumber'];
      header('Location: ../pages/PostAJob');
      exit();
    }
  }

  //closes database connection
  $db = null;
  $query = null;
  $_SESSION['listingNumber'] = null;
  $_SESSION['orderTotal'] = null;
  $_SESSION['listingData'] = null;

  echo json_encode(['status' => $session->status, 'customer_email' => $session->customer_details->email]);
  http_response_code(200);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
