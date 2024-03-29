<?php
/**
 * Declare variables defined in config / dbInfo files.
 *
 * @var $conn mysqli The database connection variable
 * @var $companyName string The name of the company
 */
?>
<footer>
  <div class="row">
    <div class="left-align col s6">Copyright © <?php echo $companyName;?></div>
    <div class="right-align col s6">Designed and Developed by <a href="https://timuroberhuber.com">Timur Oberhuber</a></div>
  </div>
</footer>

<?php
  include 'dbInfo.php';
  $conn->close();
?>