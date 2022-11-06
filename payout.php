<?php
session_start();

include 'loginCheck.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <?php
        /**
         * Declare variables defined in the config file.
         *
         * @var $affiliateTableName string The name of the table containing affiliate information
         * @var $minPayoutAmount int The minimum amount before a payout is possible
         * @var $conn mysqli The database connection variable
         * @var $currency string The currency
         * @var $payoutsTableName string The name of the table containing payout information
         */
        include 'head.php';
    ?>

    <title>Payouts - Admin Panel</title>
  </head>

  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>

    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>

      <?php
        $sql = "SELECT COUNT(*) as 'num' FROM `$affiliateTableName` WHERE `commissionBalance` > $minPayoutAmount";
        $result = $conn->query($sql)->fetch_assoc();
        $numberOfPayouts = $result['num'];
        
        if (isset($_GET['pagePending'])) {
          $pageNum = $_GET['pagePending'];
          $offset = ($pageNum - 1) * 10;
        } else {
          $offset = 0;
        }
      ?>

      <div class="row respon-table">
        <div class="col s10 offset-s1">
          <h5 class="center">Pending Payouts (With Balance Above <?php echo $minPayoutAmount . " " . $currency; ?>)</h5>
          <hr>
        </div>
        <table id="userstable" class="col s10 offset-s1 centered">
          <thead>
            <th>Payout Email</th>
            <th>Amount</th>
            <th>Actions</th>
          </thead>
          <tbody>
          <?php
            if ($numberOfPayouts > 0) {
              $sql = "SELECT `affiliateID`, `payoutEmail`, `commissionBalance` FROM `$affiliateTableName` WHERE  `commissionBalance` >= $minPayoutAmount LIMIT 10 OFFSET $offset";
              $fullResult = $conn->query($sql);
              while ($row = $fullResult->fetch_assoc()) {
                $payoutEmail = $row['payoutEmail'];
                $payoutAmount = $row['commissionBalance'];
                $affiliateID = $row['affiliateID'];

                echo "<tr>";
                  echo "<td>" . $payoutEmail . "</td>";
                  echo "<td>" . $payoutAmount . "</td>";
                  echo "<td>";
                    echo '<form action="payPendingPayout.php" method="POST">';
                    echo '<input hidden name="payoutAmount" type="text" value="' . $payoutAmount .'">';
                    echo '<input hidden name="affiliateID" type="text" value="' . $affiliateID .'">';
                    echo '<button class="btn waves-effect waves-light" type="submit" name="markPaid">Mark as Paid</button>';
                    echo '</form>';
                  echo "</td>";
                echo "</tr>";
              }
            } else {
              echo '<td colspan="3"><p class="red-text">No pending payouts yet...</p></td>';
            }
          ?>
          <tr class="paginator">
              <td colspan="5" class="center">
                <ul class="pagination">
                    <?php
                        include('pagination.php');
                        displayPagination("payout.php");
                    ?>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    <?php
        $sql = "SELECT COUNT(*) as 'num' FROM `$payoutsTableName`";
        $result = $conn->query($sql)->fetch_assoc();
        $numberOfPayouts = $result['num'];
        
        if (isset($_GET['page'])) {
          $pageNum = $_GET['page'];
          $offset = ($pageNum - 1) * 10;
        } else {
          $offset = 0;
        }
      ?>

      <div class="row respon-table">
        <div class="col s10 offset-s1">
          <h5 class="center">Payout History</h5>
          <hr>
        </div>
        <table id="userstable" class="col s10 offset-s1 centered">
          <thead>
            <th>Date</th>
            <th>Amount</th>
            <th>Paypal Email</th>
          </thead>
          <tbody>
          <?php
            if ($numberOfPayouts > 0) {
              $sql = "SELECT `date`, `amount`, `email` FROM `$payoutsTableName` LIMIT 10 OFFSET $offset";
              $fullResult = $conn->query($sql);
              while ($row = $fullResult->fetch_assoc()) {
                $payoutDate = $row['date'];
                $payoutAmount = $row['amount'];
                $payoutEmail = $row['email'];

                echo "<tr>";
                  echo "<td>" . $payoutDate . "</td>";
                  echo "<td>" . $payoutAmount . "</td>";
                  echo "<td>" . $payoutEmail . "</td>";
                echo "</tr>";
              }
            } else {
              echo '<td colspan="3"><p class="red-text">No payouts yet...</p></td>';
            }
          ?>
          <tr class="paginator">
              <td colspan="5" class="center">
                <ul class="pagination">
                    <?php
                        include('pagination.php');
                        displayPagination('payout.php');
                    ?>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <?php include 'foot.php';?>

  </body>

</html>
