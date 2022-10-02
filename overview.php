<?php
// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

session_start();

include 'loginCheck.php';

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secrets.json');
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
$client->setAccessType('offline');
$client->setIncludeGrantedScopes(true);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include 'head.php';?>
    <title>Overview - Admin Panel</title>
  </head>
  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>
    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>

      <?php
        // If the user has already authorized this app then get an access token
        // else redirect to ask the user to authorize access to Google Analytics.
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
          // Set the access token on the client.
          $client->setAccessToken($_SESSION['access_token']);

          // Create an authorized analytics service object.
          $analytics = new Google_Service_AnalyticsReporting($client);

          // Call the Analytics Reporting API V4.
          $response = getReport($analytics);

          // Print the response.
          printResults($response);

        } else {
          $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
          header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }

        function getReport($analytics) {
          global $analyticsViewID;
          $VIEW_ID = $analyticsViewID;

          // Create the DateRange object.
          $dateRange = new Google_Service_AnalyticsReporting_DateRange();
          $dateRange->setStartDate("7daysAgo");
          $dateRange->setEndDate("yesterday");

          // Create the Metrics object.
          $users = new Google_Service_AnalyticsReporting_Metric();
          $users->setExpression("ga:users");
          $users->setAlias("users");

          // Create the ReportRequest object.
          $request = new Google_Service_AnalyticsReporting_ReportRequest();
          $request->setViewId($VIEW_ID);
          $request->setDateRanges($dateRange);
          $request->setMetrics(array($users));

          $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
          $body->setReportRequests( array( $request) );
          return $analytics->reports->batchGet( $body );
        }

        function printResults($reports) {
          for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
            $report = $reports[ $reportIndex ];
            $header = $report->getColumnHeader();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
              $row = $rows[ $rowIndex ];
              $metrics = $row->getMetrics();

              for ($j = 0; $j < count($metrics); $j++) {
                $values = $metrics[$j]->getValues();
                for ($k = 0; $k < count($values); $k++) {
                  $entry = $metricHeaders[$k];
                  //print($entry->getName() . ": " . $values[$k] . "\n");
                  global ${'analyticsValue' . $entry->getName()};
                  ${'analyticsValue' . $entry->getName()} = $values[$k];
                }
              }
            }
          }
        }
        
        include 'dbInfo.php';
        
        $sql = "SELECT COUNT(*) as 'num' FROM `clients`";
        $result = $conn->query($sql)->fetch_assoc();
        $numberOfClients = $result['num'];

        $sql2 = "SELECT COUNT(*) AS 'num' FROM `{$conversionsTableName}`";
        $resultConversions = $conn->query($sql2)->fetch_assoc();
        $numberOfConversions = $resultConversions['num'];
        
        $sql3 = "SELECT SUM(`commissionBalance`) AS 'totalCommissions' FROM `{$affiliateTableName}`";
        $resultTotalCommission = $conn->query($sql3)->fetch_assoc();
        $totalCommissionValue = $resultTotalCommission['totalCommissions'];
        $totalCommissionValue = number_format((float)$totalCommissionValue, 2, '.', '');

        $sql4 = "SELECT SUM(`commissionBalance`) AS 'totalCommissions' FROM `{$affiliateTableName}` WHERE `commissionBalance` > {$minPayoutAmount}";
        $resultPayableCommission = $conn->query($sql4)->fetch_assoc();
        $payableCommissionValue = $resultPayableCommission['totalCommissions'];
        $payableCommissionValue = number_format((float)$payableCommissionValue, 2, '.', '');

        $sql5 = "SELECT SUM(clicks) AS 'totalClicks' FROM `{$affiliateTableName}`";
        $resultClicks = $conn->query($sql5)->fetch_assoc();
        $numberOfClicks = $resultClicks['totalClicks'];

        if (is_null($resultClicks) || is_null($numberOfClicks)) {
          $numberOfClicks = 0;
        }

        if (is_null($resultConversions)) {
          $numberOfConversions = 0;
        }

        if (is_null($resultTotalCommission)) {
          $totalCommissionValue = 0;
        }

        if (is_null($resultPayableCommission)) {
          $payableCommissionValue = 0;
        }
      ?>

      <div class="row rowtoppadded2">
        <h5 class="center">General Stats:</h5>
      </div>

      <div class="row">
        <div class="col m4 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Users</span>
              <h5><?php echo $numberOfClients; ?></h5><p>Registered Users</p> <!-- Should be done with database -->
            </div>
          </div>
        </div>
        <div class="col m4 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">7 Day User Total</span>
              <h5><?php echo $analyticsValueusers;?></h5><p>Users</p>
            </div>
          </div>
        </div>
        <div class="col m4 s12">
          <a class="card-link" href="uptime.php">
            <div id="uptimecard" class="card green">
              <div class="card-content">
                <span class="card-title">Service Status</span>
                <h5>0</h5><p>Services Down</p>
              </div>
            </div>
          </a>
        </div>
      </div>

      <div class="row rowtoppadded2">
        <h5 class="center">Affiliate Program Stats:</h5>
      </div>

      <div class="row">
        <div class="col m6 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Clicks</span>
              <h5><?php echo $numberOfClicks; ?></h5><p> Clicks</p>
            </div>
          </div>
        </div>
        <div class="col m6 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Conversions</span>
              <h5><?php echo $numberOfConversions;?></h5><p> Conversions</p>
            </div>
          </div>
        </div>
        <div class="col m6 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Earned By Affiliates</span>
              <h5><?php echo $totalCommissionValue;?></h5><p> <?php echo $currency?></p>
            </div>
          </div>
        </div>
        <div class="col m6 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Earned By Affiliates (With Balance Above <?php echo $minPayoutAmount . " " . $currency; ?>)</span>
              <h5><?php echo $payableCommissionValue;?></h5><p> <?php echo $currency?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="row rowtoppadded2">
        <h5 class="center">Other Information:</h5>
      </div>

      <div class="row">
        <div class="col m4 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Twitter Feed</span>
              <a class="twitter-timeline" data-height="473" href="https://twitter.com/<?php echo $twitterHandle; ?>?ref_src=twsrc%5Etfw">Tweets by <?php echo $twitterHandle; ?></a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
          </div>
        </div>
      </div>

    </div>
    <?php include 'foot.php';?>
  </body>
</html>
