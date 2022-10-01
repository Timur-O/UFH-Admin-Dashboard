<?php
session_start();

include 'loginCheck.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['connectID'])) {
    $_SESSION['user'] = $_POST['connectID'];
    $_SESSION['connectedByAdmin'] = true;
    // For Session Fixation Safeguard Avoidance
    $_SESSION['initialized'] = true;
    $_SESSION['loginTime'] = time();
    //Redirect to Dashboard
    header("Location: https://app.ultifreehosting.com/dashboard/home.php"); die();
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include 'head.php';?>
    <title>Manage Users - Admin Panel</title>
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
        
        if (isset($_GET['page'])) {
          $pageNum = $_GET['page'];
          $offset = ($pageNum - 1) * 10;
        } else {
          $offset = 0;
        }
      ?>
      
      <div class="row rowtoppadded2">
        <div class="borderedbox col s10 offset-s1">
          <form class="col s12" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <div class="row">
              <div class="input-field col s9 offset-s1">
                <input id="icon_prefix2" placeholder="Search users..." class="validate" name="searchquery" required></input>
              </div>
            </div>
            
            <div class="row">
              <div class="col s10 offset-s1">
                <label>Search by...</label>
              </div>
              <div class="col s10 offset-s1">
                <label class="padright">
                  <input name="searchoptions" type="radio" value="clientID" />
                  <span>Client ID</span>
                </label>
                  <label class="padright">
                    <input name="searchoptions" type="radio" value="email" />
                    <span>Email</span>
                  </label>
              </div>
            </div>
            
            <div class="row">            
              <button class="btn waves-effect waves-light col s2 offset-s5" type="submit" name="action" value="search">Search
                <i class="material-icons right">search</i>
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <div class="row respon-table">
        <table id="userstable" class="col s10 offset-s1 centered">
          <thead>
            <th>Client ID</th>
            <th>Email</th>
            <th>Last Login</th>
            <th>Number of Accounts</th>
            <th>Actions</th>
          </thead>
          <tbody>
            <?php
              if (!isset($_GET['searchquery'])) {
                $sql = "SELECT `clientID`, `email`, `lastLogin`, `account1`, `account2`, `account3` FROM `clients` LIMIT 10 OFFSET {$offset}";
                $fullResult = $conn->query($sql);
                
                while ($row = $fullResult->fetch_assoc()) {
                  $clientID = $row['clientID'];
                  $email = $row['email'];
                  $lastLogin = $row['lastLogin'];
                  $account1 = $row['account1'];
                  $account2 = $row['account2'];
                  $account3 = $row['account3'];
                  
                  $accountCounter = 0;
                  if ($account1 != NULL) {
                    $accountCounter++;
                  }
                  if ($account2 != NULL) {
                    $accountCounter++;
                  }
                  if ($account3 != NULL) {
                    $accountCounter++;
                  }
                  echo "<tr>";
                    echo "<td>" . $clientID . "</td>";
                    echo "<td>" . $email . "</td>";
                    echo "<td>" . $lastLogin . "</td>";
                    echo "<td>" . $accountCounter . "</td>";
                    echo '<td>
                      <form class="rowbottompadded" action="' . $_SERVER["PHP_SELF"] . '" method="post">
                         <input name="connectID" type="hidden" value="' . $clientID . '">
                         <button class="btn waves-effect waves-light" type="submit" name="connect_dash_button" value="Connect to Dashboard">Connect to Dashboard
                           <i class="material-icons left">cloud_upload</i>
                         </button>
                      </form>
                      <a class="waves-effect waves-light btn green viewAccountsButton"><i class="material-icons left">info_outline</i>View Accounts</a>
                    </td>';
                  echo "</tr>";
                  echo "<tr class='moreAccountsInfoRow'>";
                    if ($account1 == NULL) {
                      $account1Value = "N/A";
                    } else {
                      $account1Value = $account1;
                    }
                    if ($account2 == NULL) {
                      $account2Value = "N/A";
                    } else {
                      $account2Value = $account2;
                    }
                    if ($account3 == NULL) {
                      $account3Value = "N/A";
                    } else {
                      $account3Value = $account3;
                    }
                    echo "<td>Account 1: <strong>" . $account1Value . "</strong></td>";
                    echo "<td>Account 2: <strong>" . $account2Value . "</strong></td>";
                    echo "<td>Account 3: <strong>" . $account3Value . "</strong></td>";
                    echo '<td colspan="2"><a class="waves-effect waves-light btn green hideAccountsButton"><i class="material-icons left">keyboard_arrow_up</i>Hide Accounts</a></td>';
                  echo "</tr>";
                }
              } else {
                if ($_GET['searchoptions'] == 'email') {
                  $searchquery = urldecode($_GET['searchquery']);
                  $searchquery = '%' . $searchquery . '%';
                  $sql = "SELECT `clientID`, `email`, `lastLogin`, `account1`, `account2`, `account3` FROM `clients` WHERE `email` LIKE '{$searchquery}' LIMIT 10 OFFSET {$offset}";
                  $fullResult = $conn->query($sql);
                  
                  while ($row = $fullResult->fetch_assoc()) {
                    $clientID = $row['clientID'];
                    $email = $row['email'];
                    $lastLogin = $row['lastLogin'];
                    $account1 = $row['account1'];
                    $account2 = $row['account2'];
                    $account3 = $row['account3'];
                    
                    $accountCounter = 0;
                    if ($account1 != NULL) {
                      $accountCounter++;
                    }
                    if ($account2 != NULL) {
                      $accountCounter++;
                    }
                    if ($account3 != NULL) {
                      $accountCounter++;
                    }
                    echo "<tr>";
                      echo "<td>" . $clientID . "</td>";
                      echo "<td>" . $email . "</td>";
                      echo "<td>" . $lastLogin . "</td>";
                      echo "<td>" . $accountCounter . "</td>";
                      echo '<td>
                        <form class="rowbottompadded" action="' . $_SERVER["PHP_SELF"] . '" method="post">
                           <input name="connectID" type="hidden" value="' . $clientID . '">
                           <button class="btn waves-effect waves-light" type="submit" name="connect_dash_button" value="Connect to Dashboard">Connect to Dashboard
                             <i class="material-icons left">cloud_upload</i>
                           </button>
                        </form>
                        <a class="waves-effect waves-light btn green viewAccountsButton"><i class="material-icons left">info_outline</i>View Accounts</a>
                      </td>';
                      echo "</tr>";
                      echo "<tr class='moreAccountsInfoRow'>";
                        if ($account1 == NULL) {
                          $account1Value = "N/A";
                        } else {
                          $account1Value = $account1;
                        }
                        if ($account2 == NULL) {
                          $account2Value = "N/A";
                        } else {
                          $account2Value = $account2;
                        }
                        if ($account3 == NULL) {
                          $account3Value = "N/A";
                        } else {
                          $account3Value = $account3;
                        }
                        echo "<td>Account 1: <strong>" . $account1Value . "</strong></td>";
                        echo "<td>Account 2: <strong>" . $account2Value . "</strong></td>";
                        echo "<td>Account 3: <strong>" . $account3Value . "</strong></td>";
                        echo '<td colspan="2"><a class="waves-effect waves-light btn green hideAccountsButton"><i class="material-icons left">keyboard_arrow_up</i>Hide Accounts</a></td>';
                      echo "</tr>";
                  }
                } else if ($_GET['searchoptions'] == 'clientID') {
                  $sql = "SELECT `clientID`, `email`, `lastLogin`, `account1`, `account2`, `account3` FROM `clients` WHERE `clientID` = '{$_GET["searchquery"]}' LIMIT 10 OFFSET {$offset}";
                  $fullResult = $conn->query($sql);
                  
                  while ($row = $fullResult->fetch_assoc()) {
                    $clientID = $row['clientID'];
                    $email = $row['email'];
                    $lastLogin = $row['lastLogin'];
                    $account1 = $row['account1'];
                    $account2 = $row['account2'];
                    $account3 = $row['account3'];
                    
                    $accountCounter = 0;
                    if ($account1 != NULL) {
                      $accountCounter++;
                    }
                    if ($account2 != NULL) {
                      $accountCounter++;
                    }
                    if ($account3 != NULL) {
                      $accountCounter++;
                    }
                    echo "<tr>";
                      echo "<td>" . $clientID . "</td>";
                      echo "<td>" . $email . "</td>";
                      echo "<td>" . $lastLogin . "</td>";
                      echo "<td>" . $accountCounter . "</td>";
                      echo '<td>
                        <form class="rowbottompadded" action="' . $_SERVER["PHP_SELF"] . '" method="post">
                           <input name="connectID" type="hidden" value="' . $clientID . '">
                           <button class="btn waves-effect waves-light" type="submit" name="connect_dash_button" value="Connect to Dashboard">Connect to Dashboard
                             <i class="material-icons left">cloud_upload</i>
                           </button>
                        </form>
                        <a class="waves-effect waves-light btn green viewAccountsButton"><i class="material-icons left">info_outline</i>View Accounts</a>
                      </td>';
                    echo "</tr>";
                    echo "<tr class='moreAccountsInfoRow'>";
                      if ($account1 == NULL) {
                        $account1Value = "N/A";
                      } else {
                        $account1Value = $account1;
                      }
                      if ($account2 == NULL) {
                        $account2Value = "N/A";
                      } else {
                        $account2Value = $account2;
                      }
                      if ($account3 == NULL) {
                        $account3Value = "N/A";
                      } else {
                        $account3Value = $account3;
                      }
                      echo "<td>Account 1: <strong>" . $account1Value . "</strong></td>";
                      echo "<td>Account 2: <strong>" . $account2Value . "</strong></td>";
                      echo "<td>Account 3: <strong>" . $account3Value . "</strong></td>";
                      echo '<td colspan="2"><a class="waves-effect waves-light btn green hideAccountsButton"><i class="material-icons left">keyboard_arrow_up</i>Hide Accounts</a></td>';
                    echo "</tr>";
                  }
                } else {
                  echo "<p class='col s11 offset-s1 red-text'>Please select what to search by.</p>";
                }
              }
            ?>
            <tr class="paginator">
              <td colspan="5" class="center">
                <ul class="pagination">
                  <?php 
                    if (isset($_GET['page'])) {
                      $pageNum = $_GET['page'];
                      if ($pageNum > 2) {
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', ($pageNum - 1)) . '"><i class="material-icons">chevron_left</i></a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', ($pageNum - 2)) . '">' . ($pageNum - 2) . '</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', ($pageNum - 1)) . '">' . ($pageNum - 1) . '</a></li>';
                        echo '<li class="active"><a href="manageusers.php?' . addQueryToURL('page', ($pageNum)) . '">' . $pageNum . '</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', ($pageNum + 1)) . '">' . ($pageNum + 1) . '</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', ($pageNum + 2)) . '">' . ($pageNum + 2) . '</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', ($pageNum + 1)) . '"><i class="material-icons">chevron_right</i></a></li>';
                      } else if ($pageNum == 2) {
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 1) . '"><i class="material-icons">chevron_left</i></a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 1) . '">1</a></li>';
                        echo '<li class="active"><a href="manageusers.php?' . addQueryToURL('page', 2) . '">2</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 3) . '">3</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 4) . '">4</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 5) . '">5</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 3) . '"><i class="material-icons">chevron_right</i></a></li>';
                      } else {
                        echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
                        echo '<li class="active"><a href="manageusers.php?' . addQueryToURL('page', 1) . '">1</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 2) . '">2</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 3) . '">3</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 4) . '">4</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 5) . '">5</a></li>';
                        echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 2) . '"><i class="material-icons">chevron_right</i></a></li>';
                      }
                    } else {
                      echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
                      echo '<li class="active"><a href="manageusers.php?' . addQueryToURL('page', 1) . '">1</a></li>';
                      echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 2) . '">2</a></li>';
                      echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 3) . '">3</a></li>';
                      echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 4) . '">4</a></li>';
                      echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 5) . '">5</a></li>';
                      echo '<li class="waves-effect"><a href="manageusers.php?' . addQueryToURL('page', 2) . '"><i class="material-icons">chevron_right</i></a></li>';
                    }
                    function addQueryToURL($query, $queryValue) {
                      $url = "https://admin.ultifreehosting.com" . $_SERVER['REQUEST_URI'];
                      $url_parts = parse_url($url);
                      if (isset($url_parts['query'])) {
                          parse_str($url_parts['query'], $params);
                      } else {
                          $params = array();
                      }
                      
                      $params[$query] = $queryValue;
                      
                      $url_parts['query'] = http_build_query($params);
                      
                      return $url_parts['query'];
                    }
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
