<?php
//includes database connection
require_once '../components/db_connect.php';
require_once '../components/prices.php';
//get session variables
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>NewGradNomad</title>
  <meta charset="utf-8">
  <link rel="icon" href="../style/icon.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../style/NavBar.css" rel="stylesheet">
  <link href="../style/Footer.css" rel="stylesheet">
  <link href="../style/PostAJob.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="../JavaScript/selectData.js"></script>
  <script src="../JavaScript/PostAJob.js"></script>
</head>

<body>
  <div id="navbar"></div>
  <?php
  if (!empty($_SESSION['missingInput']) && $_SESSION['missingInput']) {
    echo '
    <div class="alert alert-danger alert-dismissible fade show mt-1 text-center" role="alert">
    <strong>An Error Occurred, Please Try Again.</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    $_SESSION['missingInput'] = '';
  } else if (!empty($_SESSION['listingError']) && $_SESSION['listingError']) {
    echo '
      <div class="alert alert-danger alert-dismissible fade show mt-1 text-center" role="alert">
      <strong>Unknown Error Occurred, Please Try Again Later.</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    $_SESSION['listingError'] = '';
  } else if (!empty($_SESSION['contactSupport']) && $_SESSION['contactSupport']) {
    echo '
      <div class="alert alert-danger alert-dismissible fade show mt-1 text-center" role="alert">
      <strong>Unknown Error Occurred, Please Contact Support. Reference ID: ';
    echo $_SESSION['listingID'];
    echo '</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    $_SESSION['contactSupport'] = '';
  } else if (!empty($_SESSION['cancelSuccess']) && $_SESSION['cancelSuccess']) {
    echo '
      <div class="alert alert-success alert-dismissible fade show mt-1 text-center" role="alert">
      <strong>Listing was Successfully Canceled.</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    $_SESSION['cancelSuccess'] = '';
  }
  ?>

  <div class="container-fluid">
    <div class="mt-4 text-center container">
      <h2>Hire New Grads Naturally.</h2>
      <p class="lead"><b>We aggregate job listings from all around the web, but posting your job directly to our site gives top priority to your job posting.</b> </p>
    </div>

    <div class="gray-form mt-4 container">
      <form name="jobForm" method="POST" action="../scripts/processPostAJob">
        <label class="section-title my-3 form-label"><b>Getting Started</b></label>

        <div class="mb-3">
          <label class="form-label my-0" for="companyName"><b>Company Name</b></label>
          <small class="form-text" id="companyNameRequiredMessage" style="color: red !important;">* Required</small>
          <div class="mt-2"></div>
          <input autofocus required maxlength="200" placeholder="Enter Company Name" name="companyName" type="text" id="companyName" class="form-control" onkeyup="checkInputField(this)" />
          <div class="container"><small class="form-text">- Your company's brand name without business entities</small></div>
        </div>

        <div class="mb-3">
          <label class="form-label my-0" for="positionName"><b>Position Name</b></label>
          <small class="form-text" id="positionNameRequiredMessage" style="color: red !important;">* Required</small>
          <div class="mt-2"></div>
          <input required maxlength="200" placeholder="Enter Position Name" name="positionName" type="text" id="positionName" class="form-control" onkeyup="checkInputField(this)" />
          <div class="container"><small class="form-text">- Write terms like "Associate Software Engineer" or "Social Media Manager" or "Business Analyst"</small></div>
        </div>

        <div class="mb-3">
          <label class="form-label my-0" for="positionType"><b>Position Type</b></label>
          <small class="form-text" id="positionTypeRequiredMessage" style="color: red !important;">* Required</small>
          <div class="mt-2"></div>
          <select required class="form-select form-control" name="positionType" id="positionType" onchange="checkInputField(this)">
          </select>
          <div class="container"><small class="form-text">- Specify full-time, part-time, etc...</small></div>
        </div>

        <div class="mb-3">
          <label class="form-label my-0" for="primaryTag"><b>Primary Tag</b></label>
          <small class="form-text" id="primaryTagRequiredMessage" style="color: red !important;">* Required</small>
          <div class="mt-2"></div>
          <select required class="form-select form-control" name="primaryTag" id="primaryTag" onchange="checkInputField(this)">
          </select>
          <div class="container"><small class="form-text">- Main function of specified job</small></div>
        </div>

        <div class="mb-3">
          <label class="form-label my-0" for="keywords"><b>Keywords</b></label>
          <small class="form-text" id="keywordsRequiredMessage" style="color: red !important;">* Required: Max of 3.</small>
          <div class="mt-2"></div>
          <select required class="form-select form-control" multiple="multiple" name="keywords[]" id="keywords" onchange="checkInputField(this)">
          </select>
          <div class="container"><small class="form-text">- Add keywords that pertain to the jobs purpose</small></div>
        </div>

        <label class="mt-3 section-title form-label my-0"><b>Job Post Perks</b></label>

        <div class="mb-3">
          <div class="form-check">
            <input value="<?php echo $standardListingPrice; ?>" required name="basicPosting" type="checkbox" id="basicPosting" class="form-check-input" checked onclick="return false;" />
            <label title="" for="basicPosting" class="form-check-label">Basic Job Posting ($<?php echo $standardListingPrice; ?>)</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input value="<?php echo $supportPrice; ?>" name="support" type="checkbox" id="support" class="form-check-input" onclick="updateTotal(this)" />
            <label title="" for="support" class="form-check-label">Receive 24-hour support for your job posting (+$<?php echo $supportPrice; ?>)</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input value="<?php echo $pinPost24hrPrice; ?>" name="pinPost24hr" type="checkbox" id="pinPost24hr" class="form-check-input" onclick="checkCheckboxStatus(this)" />
            <label title="" for="pinPost24hr" class="form-check-label">Pin post on front page for 24 hours (+$<?php echo $pinPost24hrPrice; ?>)</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input value="<?php echo $pinPost1wkPrice; ?>" name="pinPost1wk" type="checkbox" id="pinPost1wk" class="form-check-input" onclick="checkCheckboxStatus(this)" />
            <label title="" for="pinPost1wk" class="form-check-label">Pin post on front page for 1 week (+$<?php echo $pinPost1wkPrice; ?>)</label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input value="<?php echo $pinPost1mthPrice; ?>" name="pinPost1mth" type="checkbox" id="pinPost1mth" class="form-check-input" onclick="checkCheckboxStatus(this)" />
            <label title="" for="pinPost1mth" class="form-check-label">Pin post on front page for 1 month (+$<?php echo $pinPost1mthPrice; ?>)</label>
          </div>
        </div>

        <label class="section-title form-label my-0"><b>Job Details</b></label>
        <div class="mb-3">
          <label class="form-label my-0" for="appURL"><b>Application URL</b></label>
          <small class="form-text" id="EmailURLRequiredMessage" style="color: red !important;">* Required: Please choose either email or URL.</small>
          <small class="form-text" id="URLFormatMessage" style="color: red !important;" hidden>* The URL must include https://</small>
          <div class="mt-2"></div>
          <input required maxlength="200" placeholder="https://" name="appURL" type="url" id="appURL" class="form-control" onkeyup="checkEmailOrURL()" />
          <div class="container"><small class="form-text">- This is the job link applicants will be forwarded to in order to apply top your job</small></div>
        </div>

        <div class="mb-3">
          <label class="form-label my-0" for="appEmail"><b>Gateway Email Address</b></label>
          <small class="form-text" id="EmailFormatMessage" style="color: red !important;" hidden>* This email is invalid, it needs to be in the format: name@example.com</small>
          <div class="mt-2"></div>
          <input required maxlength="200" placeholder="name@example.com" name="appEmail" type="email" id="appEmail" class="form-control" onkeyup="checkEmailOrURL()" />
          <div class="container"><small class="form-text">- Applicant is routed to this email if no application url is provided!</small></div>
        </div>

        <div class="mb-3">
          <label class="form-label my-0" id="salaryRange"><b>Position Salary Range</b></label>
          <small class="form-text" id="salaryRangeRequiredMessage" style="color: red !important;">* Required</small>
          <small hidden class="form-text" id="salaryRangeSwappedMessage" style="color: red !important;">* Max salary must be greater than min salary.</small>
          <div class="mt-2"></div>
          <select required class="form-select form-control" name="salaryRangeMin" id="salaryRangeMin" onchange="checkSalaryRange()">
          </select>
          <div class="mt-2"></div>
          <select required class="form-select form-control" name="salaryRangeMax" id="salaryRangeMax" onchange="checkSalaryRange()">
          </select>

          <div class="container"><small class="form-text">- Please select a minimum and maximum salary from the select boxes</small></div>
        </div>
        <label class="form-label my-0"><b>Job Description</b></label>
        <small class="form-text" id="jobDescRequiredMessage" style="color: red !important;">* Required</small>
        <div class="mt-2 mb-5">
          <textarea required maxlength="1000" placeholder="" name="jobDesc" id="jobDesc" class="form-control" style="height: 150px;" onkeyup="checkInputField(this)"></textarea>
        </div>

        <div class="mb-3">
          <div class="">
            <input value="<?php echo $standardListingPrice; ?>" required hidden="" onclick="return false;" name="totalCost" type="checkbox" id="totalCost" class="form-check-input" checked="" />
          </div>
        </div>
        <span class="d-flex" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Please fill out the required fields and ensure they are formatted correctly." id="ToolTipCheckout">
          <button id="checkoutButton" type="submit" class="checkout-Button mt-1 mb-4 form-control btn btn-primary" disabled>
        </span>
        <b>
          <div value="<?php echo $standardListingPrice; ?>" id="total">Checkout Job Posting $<?php echo $standardListingPrice; ?></div>
        </b>
        </button>
      </form>
    </div>
  </div>
</body>

<footer id="footer"></footer>

</html>