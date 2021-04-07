<?php
    class Dbh {

      private $servername;
      private $username;
      private $password;
      private $dbname;
      private $charset;

      public function verzend() {
        if(isset($_GET['verzend'])) {
          $GLOBALS['locatieSelected'] = $_GET['locatie'];
          $GLOBALS['addressSelected'] = $_GET['address'];
          $GLOBALS['productSelected'] = $_GET['product'];
          $this->getTableData();
        }
      }

      public function getTableData() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "toolsforever";
        $charset = "utf8mb4";

        try {
          $dsn = "mysql:host=".$servername.";dbname=".$dbname.";charset".$charset;
          $pdo = new PDO($dsn, $username, $password);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          // get other values of products
          $GLOBALS['productInfo'] = array("");
          //foreach ($pdo->query("SELECT type, fabriek, voorraad, verkoopprijs FROM products WHERE product = \"".$_GET['product']."\" ") as $row) {
          foreach ($pdo->query("SELECT products.type, products.fabriek, products.voorraad, products.verkoopprijs \n
            FROM products \n
            INNER JOIN locatie_has_products ON products.idproduct = locatie_has_products.idproduct \n
            INNER JOIN locatie ON locatie_has_products.idlocatie = locatie.idlocatie \n
            WHERE locatie.naam = \"".$_GET['locatie']."\" AND locatie.address = \"".$_GET['address']."\" AND products.product = \"".$_GET['product']."\";") as $row) {
            array_push($GLOBALS['productInfo'], $row[0]);
            array_push($GLOBALS['productInfo'], $row[1]);
            array_push($GLOBALS['productInfo'], $row[2]);
            array_push($GLOBALS['productInfo'], $row[3]);
          }
          array_shift($GLOBALS['productInfo']);

          $pdo = null;
        } catch (PDOException $e) {
          echo "Connection failed: ".$e->getMessage();
          die();
        }
      }

      public function connect() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "toolsforever";
        $charset = "utf8";

      try {
        $dsn = "mysql:host=".$servername.";dbname=".$dbname.";charset".$charset;
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // get locatie values
        $GLOBALS['locatie'] = array("");
        foreach ($pdo->query("SELECT naam FROM locatie") as $row) {
          array_push($GLOBALS['locatie'], $row[0]);
        }
        array_shift($GLOBALS['locatie']);

        // get address values
        $GLOBALS['address'] = array("");
        foreach ($pdo->query("SELECT address FROM locatie") as $row) {
          array_push($GLOBALS['address'], $row[0]);
        }
        array_shift($GLOBALS['address']);

        // get product values
        $GLOBALS['product'] = array("");
        foreach ($pdo->query("SELECT product FROM products") as $row) {
          array_push($GLOBALS['product'], $row[0]);
        }
        array_shift($GLOBALS['product']);

        $pdo = null;
      } catch (PDOException $e) {
        echo "Connection failed: ".$e->getMessage();
        die();
      }
    }
  }
?>

<?php
  define('URL', 'http://localhost/toolsforever/');
  session_start();
  $object = new Dbh;
  $object->verzend();
  $object->connect();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style/overzicht.css">
    <title>ToolsForEver voorraad opvragen</title>
  </head>
  <body>
    <div id="yellow_bg">
      <div id="grid">
        <div id="logoDiv"><img src="Tools_For_Ever_Logo.png" alt="ToolsForEver_logo" id="logo"></div>
        <div id="tfeDiv"><span id="tfeText">ToolsForEver Vooraad</span></div>
        <div id="naamDiv"><?php

        $rol;
        if ($_SESSION['rol'] == 1) {
          $rol = "Manager";
        } else {
          $rol = "Medewerker";
        }

        echo "<p>".$rol.". ".$_SESSION['naam']."</p>";

          ?></div>
        <form action="overzicht.php" method="GET">
          <div id="line1"></div>
          <div id="line2"></div>
          <div id="line3"></div>
          <div id="line4"></div>
          <div id="line5"></div>
          <span id="kiesTxt">Kies een locatie en een product</span>
          <select name="locatie" id="locatieSelect">
            <?php
              foreach ($GLOBALS['locatie'] as $val) {
                echo "<option value=\"".$val."\">".$val."</option>";
              }
            ?>
          </select>
          <select name="address" id="addressSelect">
            <?php
              foreach ($GLOBALS['address'] as $val) {
                echo "<option value=\"".$val."\">".$val."</option>";
              }
            ?>
          </select>
          <select name="product" id="productSelect">
            <?php
              foreach ($GLOBALS['product'] as $val) {
                echo "<option value=\"".$val."\">".$val."</option>";
              }
            ?>
          </select>

          <script type="text/javascript">
            if (<?php if (!empty($_GET['locatie'])) { echo "true"; } ?>) {
              document.getElementById('locatieSelect').value = "<?php
                if(isset($_GET['verzend'])) {
                  echo $GLOBALS['locatieSelected'];
                }
              ?>";
            }

            if (<?php if (!empty($_GET['address'])) { echo "true"; } ?>) {
              document.getElementById('addressSelect').value = "<?php
                if(isset($_GET['verzend'])) {
                  echo $GLOBALS['addressSelected'];
                }
              ?>";
            }

            if (<?php if (!empty($_GET['product'])) { echo "true"; } ?>) {
              document.getElementById('productSelect').value = "<?php
                if(isset($_GET['verzend'])) {
                  echo $GLOBALS['productSelected'];
                }
              ?>";
            }
          </script>

          <div id="submitDiv">
            <input type="submit" name="verzend" value="verzenden" id="verzendSubmit">
            <input type="submit" name="uitlog" value="uitloggen" id="uitlogSubmit">
          </div>
        </form>
        <div id="overzicht">
          <span id="locatieTxt">
            Locatie:
            <?php
              if (empty($GLOBALS['locatieSelected'])) {
                echo "---";
              } else {
                echo $GLOBALS['locatieSelected'];
              }
            ?>
          </span>
          <span id="addressTxt">
            Address:
            <?php
              if (empty($GLOBALS['addressSelected'])) {
                echo "---";
              } else {
                echo $GLOBALS['addressSelected'];
              }
            ?>
          </span>
          <div id="table">
            <!-- Col means column -->
            <span id="productCol" class="textStyleBold">Product</span>
            <span id="typeCol" class="textStyleBold">Type</span>
            <span id="fabriekCol" class="textStyleBold">Fabriek</span>
            <span id="inVoorraadCol" class="textStyleBold">In voorraad</span>
            <span id="verkoopprijsCol" class="textStyleBold">Verkoopprijs</span>
            <!-- Val means Value -->
            <span id="productVal" class="textStyle">
              <?php
                if (empty($GLOBALS['productSelected'])) {
                  echo "---";
                } else {
                  echo $GLOBALS['productSelected'];
                }
              ?>
            </span>
            <span id="typeVal" class="textStyle">
              <?php
                if (empty($GLOBALS['productInfo'])) {
                  echo "---";
                } else {
                  echo utf8_encode($GLOBALS['productInfo'][0]);
                }
              ?>
            </span>
            <span id="fabriekVal" class="textStyle">
              <?php
              if (empty($GLOBALS['productInfo'])) {
                echo "---";
              } else {
                echo utf8_encode($GLOBALS['productInfo'][1]);
              }
              ?>
            </span>
            <span id="inVoorraadVal" class="textStyle">
              <?php
              if (empty($GLOBALS['productInfo'])) {
                echo "---";
              } else {
                echo $GLOBALS['productInfo'][2];
              }
              ?>
            </span>
            <span id="verkoopprijsVal" class="textStyle">
              <?php
              if (empty($GLOBALS['productInfo'])) {
                echo "---";
              } else {
                echo "€".$GLOBALS['productInfo'][3];
              }
              ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
