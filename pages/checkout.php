<?php
//includes database connection
require_once '../components/db_connect.php';
//includes session info
session_start();

if (empty($_SESSION['orderTotal']) && empty($_SESSION['listingNumber'])) header('Location: ./PostAJob');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="icon" href="../style/icon.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../style/NavBar.css" rel="stylesheet">
  <link href="../style/Footer.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <title>Accept a payment</title>
  <meta name="description" content="Payment on Stripe" />
  <script src="https://js.stripe.com/v3/"></script>
  <script src="../JavaScript/checkout.js" defer></script>
  <style>
    .checkout-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 2rem;
      background-color: #ffffff;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
    
    .alert-test-mode {
      background-color: #ffe9e9;
      border-color: #ffbfbf;
      color: #cc0000;
      font-size: 0.95rem;
      padding: 1rem;
    }

    .loading-container {
      padding: 3rem;
      text-align: center;
      background-color: #f8f9fa;
      border-radius: 8px;
      margin: 2rem auto;
      max-width: 600px;
    }

    .loading-text {
      margin-top: 1rem;
      color: #6c757d;
      font-size: 1.1rem;
    }

    .spinner-border {
      width: 3rem;
      height: 3rem;
      color: #0d6efd;
    }

    #checkout {
      min-height: 600px;
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
    }

    @media (max-width: 768px) {
      .checkout-container {
        padding: 1rem;
        margin: 1rem;
      }
    }
  </style>
</head>

<body class="bg-light">
  <div class="alert alert-test-mode mb-0 text-center" role="alert">
    <b>DO NOT use real cards or any personal information at checkout, this is against Stripe's TOS in test mode.<br> Use stripe test card: 4242424242424242 04/28 024</b>
  </div>
  
  <div id="navbar"></div>

  <div class="container py-5">
    <div class="checkout-container">
      <div id="loading-message" class="loading-container">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="loading-text">Initializing payment form...</p>
      </div>

      <div id="checkout" style="display: none;"></div>
    </div>
  </div>

  <footer id="footer"></footer>
</body>

</html>