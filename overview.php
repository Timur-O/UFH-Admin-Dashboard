<?php
session_start();

include 'loginCheck.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <?php
        /**
         * Declare variables defined in config file.
         *
         * @var $conn mysqli The database connection variable
         * @var $conversionsTableName string The name of the table which contains conversion information
         * @var $affiliateTableName string The name of the table containing affiliate information
         * @var $minPayoutAmount int The minimum payout amount
         * @var $currency string The currency
         * @var $twitterHandle string The twitter handle for which to display the feed
         */
        include 'head.php';
    ?>
    <title>Overview - Admin Panel</title>
  </head>

  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>
    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>

      <?php
        include 'dbInfo.php';
        
        $sql = "SELECT COUNT(*) as 'num' FROM `clients`";
        $result = $conn->query($sql)->fetch_assoc();
        $numberOfClients = $result['num'];

        $sql2 = "SELECT COUNT(*) AS 'num' FROM `$conversionsTableName`";
        $resultConversions = $conn->query($sql2)->fetch_assoc();
        $numberOfConversions = $resultConversions['num'];
        
        $sql3 = "SELECT SUM(`commissionBalance`) AS 'totalCommissions' FROM `$affiliateTableName`";
        $resultTotalCommission = $conn->query($sql3)->fetch_assoc();
        $totalCommissionValue = $resultTotalCommission['totalCommissions'];
        $totalCommissionValue = number_format((float)$totalCommissionValue, 2, '.', '');

        $sql4 = "SELECT SUM(`commissionBalance`) AS 'totalCommissions' FROM `$affiliateTableName` WHERE `commissionBalance` > $minPayoutAmount";
        $resultPayableCommission = $conn->query($sql4)->fetch_assoc();
        $payableCommissionValue = $resultPayableCommission['totalCommissions'];
        $payableCommissionValue = number_format((float)$payableCommissionValue, 2, '.', '');

        $sql5 = "SELECT SUM(clicks) AS 'totalClicks' FROM `$affiliateTableName`";
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
        <div class="col m6 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Users</span>
              <h5><?php echo $numberOfClients; ?></h5><p>Registered Users</p> <!-- Should be done with database -->
            </div>
          </div>
        </div>
        <div class="col m6 s12">
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

    </div>
    <?php include 'foot.php';?>
  </body>
</html>
