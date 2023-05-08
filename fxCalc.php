<!DOCTYPE html>

<?php
//THIS LINE if check before START SESSION
//require_once we use only one time to get  access to lig in
require_once('LoginDataModel.php');

if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION[LoginDataModel::USERNAME_KEY])) {
  include('login.php');

  exit();
}

require_once('FxDataModel.php');

if (isset($_SESSION[FxDataModel::FX_DATA_MODEL_KEY])) {
  $FxDataModel = unserialize($_SESSION[FxDataModel::FX_DATA_MODEL_KEY]);
} else {
  $FxDataModel = new FxDataModel();

  $_SESSION[FxDataModel::FX_DATA_MODEL_KEY] = serialize($FxDataModel);
}
$currencies = $FxDataModel->getFxCurrencies();
$iniArray       = $FxDataModel->getIniArray();

$dest_amount = $iniArray[FxDataModel::DST_AMT_KEY];
$source_amount = $iniArray[FxDataModel::SRC_AMT_KEY];
$source_currency = $iniArray[FxDataModel::SRC_CUCY_KEY];
$dest_currency = $iniArray[FxDataModel::DST_CUCY_KEY];


// $source_amount = "source_amount";
// $dest_amount = "dest_amount";
// print_r($currencies);

if (array_key_exists($source_amount, $_POST) && is_numeric($_POST[$source_amount])) {

  $amount = $_POST[$source_amount];
  $source = $_POST[$source_currency];
  $dest = $_POST[$dest_currency];
  $convertAmount = $amount * $FxDataModel->getFxRate($source, $dest);
} else {

  $convertAmount = '';
  $amount     = '';
  $source     =  $currencies[0];
  $dest      =   $currencies[0];
}


?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>F/X Calculator</title>
</head>

<body>
  <h1 align="center">Money Banks F/X Calculator</h1>
  <hr /><br />
  <form name="fxCalc" action="fxCalc.php" method="post">

    <center>
      <h2>Welcome <?php echo $_SESSION[LoginDataModel::USERNAME_KEY] ?></h2>

      <select name="<?php echo $source_currency ?>">
        <?php
        foreach ($currencies as $r) {
        ?>
          <option value="<?php echo $r ?>" <?php
                                            if ($r == $source) {
                                            ?> selected <?php
                                                      }
                                                        ?>><?php echo "$r" ?></option>
        <?php
        }
        ?>
      </select>

      <input type="text" name="<?php echo $source_amount ?>" value="<?php echo  $amount ?>" />

      <select name="<?php echo $dest_currency ?>">
        <?php
        foreach ($currencies as $t) {
        ?>
          <option value="<?php echo $t ?>" <?php
                                            if ($t == $dest) {
                                            ?> selected <?php
                                                      }
                                                        ?>><?php echo "$t" ?></option>

        <?php
        }
        ?>
      </select>

      <input type="text" name="<?php echo $dest_amount ?>" disabled="disabled" value="<?php echo   $convertAmount  ?>" />

      <br /><br />

      <input type="submit" value="Convert" />
      <input type="reset" value="Reset">

    </center>
  </form>

</body>

</html>