<?php
session_start();

include 'loginCheck.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include 'head.php';?>
    <title>Manage Ads - Admin Panel</title>
  </head>
  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>
    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      
      <div class="row rowtoppadded2">
        <div class="col s12 center"><h5 id="overalluptimeheader">Manage Website Ads</h5></div>
      </div>
      
      <div class="row">
        <div class="col m10 offset-m1">
          <table>
            <thead>
              <th class="center">Ad ID</th>
              <th class="center">Information</th>
              <th class="center">Ad Code</th>
              <th class="center">Location</th>
            </thead>
            <tbody>
          <?php
            include 'dbInfo.php';
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              if (isset($_POST['code'])) {
                $id = $_POST['id'];
                $code = $_POST['code'];
                
                $sql = "UPDATE `ads` SET `adCode` = '{$code}' WHERE `adID` = '{$id}'";
                $result = $conn->query($sql);
              } else if (isset($_POST['newLocation'])) {
                $newLocation = $_POST['newLocation'];
                $id = $_POST['id'];
                
                $sql = "UPDATE `ads` SET `location` = '{$newLocation}' WHERE `adID` = '{$id}'";
                $result = $conn->query($sql);
              } else if (isset($_POST['locationAdd'])) {
                $locationAdd = $_POST['locationAdd'];
                $codeAdd = $_POST['codeAdd'];
                $notesAdd = $_POST['notesAdd'];
                
                $sql = "INSERT INTO `ads` (`location`, `adCode`, `notes`) VALUES ('{$locationAdd}', '{$codeAdd}', '{$notesAdd}')";
                $result = $conn->query($sql);
              }
            }
            
            $sql = "SELECT `adID`, `location`, `adCode`, `notes` FROM `ads`";
            $fullResult = $conn->query($sql);
            
            while ($row = $fullResult->fetch_assoc()) {
              $adID = $row['adID'];
              $location = $row['location'];
              $adCode = $row['adCode'];
              $notes = $row['notes'];
              
              echo '<tr>';
                echo '<td class="center">' . $adID . '</td>';
                echo '<td class="center">' . $notes . '</td>';
                echo '<td class="center"><form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
                  <textarea name="code">' . $adCode . '</textarea>
                  <input type="hidden" value="' . $adID . '" name="id" />
                  <button class="btn waves-effect waves-light" type="submit" value="Change Code">Change Code
                    <i class="material-icons left">done</i>
                  </button>
                </form></td>';
                echo '<td class="center"><form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
                  <input name="newLocation" value="' . $location . '" />
                  <input type="hidden" value="' . $adID . '" name="id" />
                  <button class="btn waves-effect waves-light" type="submit" value="Change Location">Change Location
                    <i class="material-icons left">done</i>
                  </button>
                </form></td>';
              echo '</tr>';
            }
          ?>
              <tr>
                <td colspan="4" class="center">
                  <h6><strong>Create New Ad</strong></h6>
                </td>
              </tr>
              <tr>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <td class="center">
                    <button class="btn waves-effect waves-light" type="submit" value="Create Ad">Create Ad
                      <i class="material-icons left">done</i>
                    </button>
                  </td>
                  <td class="center">
                    <label>Ad Notes...</label>
                    <input type="text" name="notesAdd" />
                  </td>
                  <td class="center">
                    <label>Ad Code...</label>
                    <textarea name="codeAdd"></textarea>
                  </td>
                  <td class="center">
                    <label>Ad Location...</label>
                    <input type="text" name="locationAdd" />
                  </td>
                </form>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
    </div>
    <?php include 'foot.php';?>
  </body>
</html>
