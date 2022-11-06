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
         * @var $conversionsTableName string The name of the table containing information about conversions
         * @var $currency string The currency
         */
        include 'head.php';
    ?>
    <title>Conversions - Admin Panel</title>
  </head>

  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>

    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      
      <?php
        $sql = "SELECT COUNT(*) as 'num' FROM `$conversionsTableName`";
        $result = $conn->query($sql)->fetch_assoc();
        $numberOfConversions = $result['num'];
        
        if (isset($_GET['page'])) {
          $pageNum = $_GET['page'];
          $offset = ($pageNum - 1) * 10;
        } else {
          $offset = 0;
        }
      ?>
      
      <div class="row respon-table">
        <div class="col s10 offset-s1">
          <h5 class="center">All Conversions</h5>
          <hr>
        </div>
        <table id="userstable" class="col s10 offset-s1 centered">
          <thead>
            <th>Date</th>
            <th>Affiliate</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Referer</th>
            <th>IP Address</th>
            <th>Possible Other IPs</th>
            <th>Approved</th>
            <th>Note</th>
            <th>Actions</th>
          </thead>
          <tbody>
            <?php
              if ($numberOfConversions > 0) {
                $sql = "SELECT `conversionID`, `affiliate`, `date`, `type`, `commissionAmount`, `approved`, `note`,`httpReferer`, `ipAddress`, `ipProxyAddress` FROM `$conversionsTableName` LIMIT 10 OFFSET $offset";
                $fullResult = $conn->query($sql);
                
                while ($row = $fullResult->fetch_assoc()) {
                  $commissionID = $row['conversionID'];
                  $commissionDate = $row['date'];
                  $commissionType = $row['type'];
                  $commissionAmount = $row['commissionAmount'];
                  $commissionHTTPReferer = $row['httpReferer'];
                  $commissionIPAddress = $row['ipAddress'];
                  $commissionProxyAddress = $row['ipProxyAddress'];
                  $commissionApproved = $row['approved'];
                  $commissionNote = $row['note'];
                  $commissionAffiliate = $row['affiliate'];

                  echo "<tr>";
                    echo "<td>" . $commissionDate . "</td>";
                    echo "<td>" . $commissionAffiliate . "</td>";
                    echo "<td>" . $commissionType . "</td>";
                    echo "<td>" . $commissionAmount . " " . $currency . "</td>";
                    echo "<td>" . $commissionHTTPReferer . "</td>";
                    echo "<td>" . $commissionIPAddress . "</td>";
                    echo "<td>" . $commissionProxyAddress . "</td>";
                    if ($commissionApproved == 1) {
                      echo "<td class='green-text'>Approved</td>";
                    } else if ($commissionApproved == 2) {
                      echo "<td class='red-text'>Rejected</td>";
                    } else {
                      echo "<td>Pending Approval</td>";
                    }
                    echo "<td>" . $commissionNote . '</td>';
                    echo "<td>";
                      echo '<form action="changeConversionStatus.php" method="POST" class="formActionButton">';
                      echo '<input hidden name="conversionID" type="text" value="' . $commissionID .'">';
                      echo '<input hidden name="affiliateID" type="text" value="' . $commissionAffiliate .'">';
                      echo '<button class="btn waves-effect waves-light" type="submit" name="approveConversion">Approve Conversion</button>';
                      echo '</form>';
                      echo '<form action="changeConversionStatus.php" method="POST" class="formActionButton">';
                      echo '<input hidden name="conversionID" type="text" value="' . $commissionID .'">';
                      echo '<button class="btn waves-effect waves-light" type="submit" name="rejectConversion">Reject Conversion</button>';
                      echo '</form>';
                    echo "</td>";
                  echo "</tr>";
                }
              } else {
                echo '<td colspan="10"><p class="red-text">No conversions yet.</p></td>';
              }
            ?>
            <tr class="paginator">
              <td colspan="10" class="center">
                <ul class="pagination">
                    <?php
                        include('pagination.php');
                        displayPagination('conversions.php');
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
