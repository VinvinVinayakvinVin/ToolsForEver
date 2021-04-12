<?php
  define('URL', 'http://localhost/toolsforever/');
  session_start();
  $object = new Dbh;

  // if you did not login into this website, you will get back to the login screen!
  if (empty($_SESSION['naam'])) {
    header('Location: '.URL.'index.php', TRUE, 302);
  }

  $object->uitlog();
  $object->overzicht();
  $object->setFormSelectOptionData();

  // TODO set the other func ready. addLocatie() is done.

  if(isset($_GET['addLocatie']) && isset($_GET['addAdres']) && isset($_GET['addPlaceSubmit'])) {
    $object->addLocatie();
  } else if (!empty($_GET['changeLocatieSelect']) && !empty($_GET['changePlaceLocatieInput']) && !empty($_GET['changeLocatieSubmit'])) {
    $object->changeLocatie();
  } else if (!empty($_GET['changeAdresSelect']) && !empty($_GET['changePlaceAdresInput']) && !empty($_GET['changeAdresSubmit'])) {
    $object->changeAdres();
  }

  // $object->removeLocatie1();
  // $object->removeLocatie2();
  //
  // $object->addProduct();
  // $object->changeProduct1();
  // // $object->changeProduct2();
  // $object->changeProduct3();
  // $object->removeProduct();
  //
  // $object->addMedewerker();
  // $object->changeMedewerker1();
  // $object->changeMedewerker2();
  // $object->changeMedewerker3();
  // $object->removeMedewerker();
?>

<?php
  class Dbh {

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "toolsforever";
    private $charset = "utf8mb4";

      public function uitlog() {
        if(isset($_GET['uitlog'])) {
          session_unset();
          session_destroy();
          header('Location: '.URL.'index.php', TRUE, 302);
        }
      }

      public function overzicht() {
        if(isset($_GET['overzichtVenster'])) {
          header('Location: '.URL.'overzicht.php', TRUE, 302);
        }
      }

      public function setFormSelectOptionData() {
        try {
          $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          // set "locatie naam" for the forms.
          $GLOBALS['locatieNaam'] = array("");
          $stmt = $conn->prepare("SELECT naam FROM locatie");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['locatieNaam'], $row['naam']);
          }
          array_shift($GLOBALS['locatieNaam']);

          // set "locatie adres" for the forms.
          $GLOBALS['locatieAdres'] = array("");
          $stmt = $conn->prepare("SELECT address FROM locatie");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['locatieAdres'], $row['address']);
          }
          array_shift($GLOBALS['locatieAdres']);

          // set "product naam" for the forms.
          $GLOBALS['productNaam'] = array("");
          $stmt = $conn->prepare("SELECT product FROM products");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['productNaam'], $row['product']);
          }
          array_shift($GLOBALS['productNaam']);

          // set "product type" for the forms.
          $GLOBALS['productType'] = array("");
          $stmt = $conn->prepare("SELECT type FROM products");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['productType'], $row['type']);
          }
          array_shift($GLOBALS['productType']);

          // set "product fabriek" for the forms.
          $GLOBALS['productFabriek'] = array("");
          $stmt = $conn->prepare("SELECT fabriek FROM products");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['productFabriek'], $row['fabriek']);
          }
          array_shift($GLOBALS['productFabriek']);

          // set "medewerker voornaam" for the forms.
          $GLOBALS['medewerkerVoornaam'] = array("");
          $stmt = $conn->prepare("SELECT voornaam FROM medewerkers");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['medewerkerVoornaam'], $row['voornaam']);
          }
          array_shift($GLOBALS['medewerkerVoornaam']);

          // set "medewerker tussenvoegsel" for the forms.
          $GLOBALS['medewerkerTussenvoegsel'] = array("");
          $stmt = $conn->prepare("SELECT tussenvoegsel FROM medewerkers");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['medewerkerTussenvoegsel'], $row['tussenvoegsel']);
          }
          array_shift($GLOBALS['medewerkerTussenvoegsel']);

          // set "medewerker achternaam" for the forms.
          $GLOBALS['medewerkerAchternaam'] = array("");
          $stmt = $conn->prepare("SELECT achternaam FROM medewerkers");
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($result as $key => $row) {
            array_push($GLOBALS['medewerkerAchternaam'], $row['achternaam']);
          }
          array_shift($GLOBALS['medewerkerAchternaam']);

        } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
        $conn = null;
      }

      public function addLocatie() {
          try {
            $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SET SQL_SAFE_UPDATES = 0");
            $stmt->execute();
            $stmt = $conn->prepare("SET FOREIGN_KEY_CHECKS=0");
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO locatie (naam, address) VALUES (:naam, :adres)");
            $stmt->bindParam(':naam', $naam);
            $stmt->bindParam(':adres', $adres);

            $naam = $_GET['addLocatie'];
            $adres = $_GET['addAdres'];
            $stmt->execute();
          } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
          }
          $conn = null;
          header('Location: '.URL.'admin.php', TRUE, 302);
      }

      public function changeLocatie() {
        try {
          $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $stmt = $conn->prepare("SET SQL_SAFE_UPDATES = 0");
          $stmt->execute();

          $stmt = $conn->prepare("UPDATE toolsforever.locatie SET naam = :nieuwLocatie WHERE naam = :oudLocatie");
          $stmt->bindParam(':nieuwLocatie', $nieuwLocatie);
          $stmt->bindParam(':oudLocatie', $oudLocatie);

          $nieuwLocatie = $_GET['changePlaceLocatieInput'];
          $oudLocatie = $_GET['changeLocatieSelect'];
          $stmt->execute();
        } catch(PDOException $e) {
          echo $sql . "<br>" . $e->getMessage();
        }
          $conn = null;
          header('Location: '.URL.'admin.php', TRUE, 302);
      }

      public function changeAdres() {
        try {
          $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $stmt = $conn->prepare("SET SQL_SAFE_UPDATES = 0");
          $stmt->execute();

          $stmt = $conn->prepare("UPDATE toolsforever.locatie SET address = :nieuwAdres WHERE address = :oudAdres");
          $stmt->bindParam(':nieuwAdres', $nieuwAdres);
          $stmt->bindParam(':oudAdres', $oudAdres);

          $nieuwAdres = $_GET['changePlaceAdresInput'];
          $oudAdres = $_GET['changeAdresSelect'];
          $stmt->execute();
        } catch(PDOException $e) {
          echo $sql . "<br>" . $e->getMessage();
        }
          $conn = null;
          header('Location: '.URL.'admin.php', TRUE, 302);
      }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style/admin.css">
    <title>ToolsForEver administatie venster</title>
  </head>
  <body>
    <div id="yellow_bg">
      <div id="grid">
        <div id="logoDiv"><img src="Tools_For_Ever_Logo.png" alt="ToolsForEver_logo" id="logo"></div>
        <div id="tfeDiv"><span id="tfeText">ToolsForEver</span></div>
        <div id="naam_formDiv">
          <p>Manager. <?php echo $_SESSION['naam']; ?></p>
          <form method="GET">
            <input type="submit" name="uitlog" value="uitloggen" id="uitlogSubmit">
          </form>
        </div>
        <div id="bigFormDiv">
          <div id="line1"></div>
          <div id="line2"></div>
          <div id="line3"></div>
          <div id="line4"></div>
          <div id="locatieDiv">
            <span id="locatieInfo">Hier kan je de locatie en adres toevoegen, wijzigen of verwijderen</span>
            <div id="locatieAddDiv">
              <span id="locatieInfo1">- locatie en adres toevoegen</span>
              <form method="GET" id="addPlaceForm">
                <input type="text" name="addLocatie" value="" placeholder="type hier de nieuwe locatie" required id="addPlaceLocatieInput">
                <input type="text" name="addAdres" value="" placeholder="type hier de nieuwe adres" required id="addPlaceAdresInput">
                <input type="submit" name="addPlaceSubmit" value="Toevoegen" id="addPlaceSubmit">
              </form>
            </div>
            <div id="locatieChangeDiv">
              <span id="locatieInfo2">- locatie wijzigen.</span>
              <form method="GET" id="changeLocatieForm">
                <select name="changeLocatieSelect" id="changeLocatieSelect">
                  <?php
                    foreach ($GLOBALS['locatieNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="text" name="changePlaceLocatieInput" value="" placeholder="type hier de nieuwe gewijzigde locatie" id="changePlaceLocatieInput">
                <input type="submit" name="changeLocatieSubmit" value="Wijziging opslaan" id="changeLocatieSubmit">
              </form>
              <span id="locatieInfo3">- adres wijzigen.</span>
              <form method="GET" id="changeAdresForm">
                <select name="changeAdresSelect" id="changeAdresSelect">
                  <?php
                    foreach ($GLOBALS['locatieAdres'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="text" name="changePlaceAdresInput" value="" placeholder="type hier de nieuwe gewijzigde adres" id="changePlaceAdresInput">
                <input type="submit" name="changeAdresSubmit" value="Wijziging opslaan" id="changeAdresSubmit">
              </form>
            </div>
            <div id="locatieRemoveDiv">
              <span id="locatieInfo4">- locatie verwijderen</span>
              <form method="GET" id="removeLocatiePlaceForm">
                <select name="removeLocatieSelect" id="removeLocatieSelect">
                  <?php
                    foreach ($GLOBALS['locatieNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="submit" name="removeLocatiePlaceSubmit" value="Verwijder" id="removeLocatiePlaceSubmit">
              </form>
              <span id="locatieInfo5">- adres verwijderen</span>
              <form method="GET" id="removeAdresPlaceForm">
                <select name="removeAdresSelect" id="removeAdresSelect">
                  <?php
                    foreach ($GLOBALS['locatieAdres'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="submit" name="removeAdresPlaceSubmit" value="Verwijder" id="removeAdresPlaceSubmit">
              </form>
            </div>
          </div>
          <div id="productDiv">
            <span id="productInfo">Hier kan je de product informatie toevoegen, wijzigen of verwijderen</span>
            <div id="productAddDiv">
              <span id="productInfo1">- artikel, type, fabriek, voorraad, (locatie van artikel), minimumvoorraad, verkoopprijs toevoegen.</span>
              <form method="GET" id="addProductForm">
                <input type="text" name="addProductsNaam" value="" placeholder="type hier de nieuwe product naam" id="addProductsNaam" required>
                <input type="text" name="addProductsType" value="" placeholder="type hier de type van het product" id="addProductsType">
                <input type="text" name="addProductsFabriek" value="" placeholder="type hier van welke fabriek het product komt" id="addProductsFabriek" required>
                <input type="number" name="addProductsVoorraad" value="" min="0" placeholder="type hier het getal van hoeveel van dit product in het voorraad zit" id="addProductsVoorraad" required>
                <input type="text" value="Kies hierbeneden de voorraad locatie en adres voor de product" id="addProductsInfoSelect" readonly>
                <select name="addProductsLocatieSelect" id="addProductsLocatieSelect">
                  <?php
                    foreach ($GLOBALS['locatieNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="addProductsAdresSelect" id="addProductsAdresSelect">
                  <?php
                    foreach ($GLOBALS['locatieAdres'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="number" name="addProductsMinimumVoorraad" value="" min="0" placeholder="type hier het getal van het minimum voorraad van dit product" id="addProductsMinimumVoorraad" required>
                <input type="number" name="addProductsVerkoopprijs" value="" min="0" step=".01" placeholder="type hier wat de nieuwe verkoopprijs is van dit product" id="addProductsVerkoopprijs" required>
                <input type="submit" name="addProductSubmit" value="Toevoegen" id="addProductSubmit">
              </form>
            </div>
            <div id="productChangeDiv">
              <span id="productInfo2">- artikel naam wijzigen.</span>
              <form method="GET" id="changeProductNaamForm">
                <select name="changeProductNaamSelect" id="changeProductNaamSelect">
                  <?php
                    foreach ($GLOBALS['productNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="text" name="changeProductNaam" value="" placeholder="type hier de gewijzigde product naam" id="changeProductNaam">
                <input type="submit" name="changeProductNaamSubmit" value="Wijziging opslaan" id="changeProductNaamSubmit">
              </form>
              <span id="productInfo3">- artikel type wijzigen.</span>
              <form method="GET" id="changeProductTypeForm">
                <select name="changeProductTypeSelect" id="changeProductTypeSelect">
                  <?php
                    foreach ($GLOBALS['productType'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="text" name="changeProductType" value="" placeholder="type hier de gewijzigde product type" id="changeProductType">
                <input type="submit" name="changeProductTypeSubmit" value="Wijziging opslaan" id="changeProductTypeSubmit">
              </form>
              <span id="productInfo4">- artikel fabriek wijzigen.</span>
              <form method="GET" id="changeProductFabriekForm">
                <select name="changeProductFabriekSelect" id="changeProductFabriekSelect">
                  <?php
                    foreach ($GLOBALS['productFabriek'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="text" name="changeProductFabriek" value="" placeholder="type hier de gewijzigde product fabriek" id="changeProductFabriek">
                <input type="submit" name="changeProductFabriekSubmit" value="Wijziging opslaan" id="changeProductFabriekSubmit">
              </form>
              <span id="productInfo5">- Artikel locatie voorraad wijzigen.</span>
              <span id="productInfo6">- Middelste rij is de oude locatie die u wilt wijzigen.</span>
              <span id="productInfo7">- Kies je bij de rechter rij de nieuwe locatie voor het artikel.</span>
              <form method="GET" id="changeProductForm2">
                <select name="changeProductsNaamSelect2" id="changeProductsNaamSelect2">
                  <?php
                    foreach ($GLOBALS['productNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsTypeSelect2" id="changeProductsTypeSelect2">
                  <?php
                    foreach ($GLOBALS['productType'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsFabriekSelect2" id="changeProductsFabriekSelect2">
                  <?php
                    foreach ($GLOBALS['productFabriek'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsLocatieSelect" id="changeProductsLocatieSelect">
                  <?php
                    foreach ($GLOBALS['locatieNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsAdresSelect" id="changeProductsAdresSelect">
                  <?php
                    foreach ($GLOBALS['locatieAdres'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsLocatieSelect2" id="changeProductsLocatieSelect2">
                  <?php
                    foreach ($GLOBALS['locatieNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsAdresSelect2" id="changeProductsAdresSelect2">
                  <?php
                    foreach ($GLOBALS['locatieAdres'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="submit" name="changeProductSubmit2" value="Wijziging opslaan" id="changeProductSubmit2">
              </form>
              <span id="productInfo8">- artikel voorraad wijzigen.</span>
              <form method="GET" id="changeProductVoorraadForm">
                <select name="changeProductsNaamSelect3" id="changeProductsNaamSelect3">
                  <?php
                    foreach ($GLOBALS['productNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsTypeSelect3" id="changeProductsTypeSelect3">
                  <?php
                    foreach ($GLOBALS['productType'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsFabriekSelect3" id="changeProductsFabriekSelect3">
                  <?php
                    foreach ($GLOBALS['productFabriek'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="number" name="changeProductsVoorraad" value="" min="0" placeholder="type hier het nieuwe getal van hoeveel van dit product in het voorraad zit" id="changeProductsVoorraad">
                <input type="submit" name="changeProductVoorraadSubmit" value="Wijziging opslaan" id="changeProductVoorraadSubmit">
              </form>
              <span id="productInfo9">- artikel minimum voorraad wijzigen.</span>
              <form method="GET" id="changeProductMinimumVoorraadForm">
                <select name="changeProductsNaamSelect4" id="changeProductsNaamSelect4">
                  <?php
                    foreach ($GLOBALS['productNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsTypeSelect4" id="changeProductsTypeSelect4">
                  <?php
                    foreach ($GLOBALS['productType'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsFabriekSelect4" id="changeProductsFabriekSelect4">
                  <?php
                    foreach ($GLOBALS['productFabriek'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="number" name="changeProductsMinimumVoorraad" value="" min="0" placeholder="type hier het nieuwe getal van het minimum voorraad van dit product" id="changeProductsMinimumVoorraad">
                <input type="submit" name="changeProductMinimumSubmit" value="Wijziging opslaan" id="changeProductMinimumSubmit">
              </form>

              <span id="productInfo10">- artikel verkoopprijs wijzigen.</span>
              <form method="GET" id="changeProductVerkoopprijsForm">
                <select name="changeProductsNaamSelect5" id="changeProductsNaamSelect5">
                  <?php
                    foreach ($GLOBALS['productNaam'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsTypeSelect5" id="changeProductsTypeSelect5">
                  <?php
                    foreach ($GLOBALS['productType'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <select name="changeProductsFabriekSelect5" id="changeProductsFabriekSelect5">
                  <?php
                    foreach ($GLOBALS['productFabriek'] as $val) {
                      echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                    }
                  ?>
                </select>
                <input type="number" name="changeProductsVerkoopprijs" value="" min="0" step=".01" placeholder="type hier wat de nieuwe verkoopprijs is van dit product" id="changeProductsVerkoopprijs">
                <input type="submit" name="changeProductVerkoopprijsSubmit" value="Wijziging opslaan" id="changeProductVerkoopprijsSubmit">
              </form>
          </div>
          <div id="productRemoveDiv">
            <span id="productInfo11">- artikel verwijderen van een bepaalde type en fabriek.</span>
            <form method="GET" id="removeProductForm">
              <select name="removeProductsNaamSelect" id="removeProductsNaamSelect">
                <?php
                  foreach ($GLOBALS['productNaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="removeProductsTypeSelect" id="removeProductsTypeSelect">
                <?php
                  foreach ($GLOBALS['productType'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="removeProductsFabriekSelect" id="removeProductsFabriekSelect">
                <?php
                  foreach ($GLOBALS['productFabriek'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <input type="submit" name="removeProductSubmit" value="Verwijder" id="removeProductSubmit">
            </form>
          </div>
        </div>
        <div id="medewerkerDiv">
          <span id="medewerkerInfo">Hier kan je medewerkers informatie toevoegen, wijzigen of verwijderen</span>
          <div id="medewerkerAddDiv">
            <span id="medewerkerInfo1">- medewerker naam, wachtwoord en rol toevoegen.</span>
            <form method="POST" id="addMedewerkerForm">
              <input type="text" name="addMedewerkersVoornaam" value="" placeholder="type hier de voornaam van de nieuwe medewerker" required id="addMedewerkersVoornaam">
              <input type="text" name="addMedewerkersTussenvoegsel" value="" placeholder="type hier de tussenvoegsel van de nieuwe medewerker" id="addMedewerkersTussenvoegsel">
              <input type="text" name="addMedewerkersAchternaam" value="" placeholder="type hier de achternaam van de nieuwe medewerker" required id="addMedewerkersAchternaam">
              <input type="password" name="addMedewerkersWachtwoord" value="" placeholder="type hier een wachtwoord van de nieuwe medewerker" required id="addMedewerkersWachtwoord">
              <select name="addMedewerkersRolSelect" id="addMedewerkersRolSelect">
                <option value="0">Medewerker</option>
                <option value="1">Manager</option>
              </select>
              <input type="submit" name="addMedewerkerSubmit" value="Toevoegen" id="addMedewerkerSubmit">
            </form>
          </div>
          <div id="medewerkerChangeDiv">
            <span id="medewerkerInfo2">- medewerker voornaam wijzigen.</span>
            <form method="POST" id="changeMedewerkerVoornaamForm">
              <input type="text" readonly name="readVoornaam" value="Voornaam:" class="readVoornaam">
              <input type="text" readonly name="readTussenvoegsel" value="Tussenvoegsel:" class="readTussenvoegsel">
              <input type="text" readonly name="readAchternaam" value="Achternaam:" class="readAchternaam">
              <select name="changeMedewerkerVoornaamSelect1" class="changeMedewerkerVoornaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerVoornaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerTussenvoegselSelect1" class="changeMedewerkerTussenvoegselSelect">
                <?php
                  foreach ($GLOBALS['medewerkerTussenvoegsel'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerAchternaamSelect1" class="changeMedewerkerAchternaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerAchternaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <input type="text" name="changeMedewerkersVoornaam" value="" placeholder="type hier de nieuwe gewijzigde voornaam" id="changeMedewerkersVoornaam">
              <input type="submit" name="changeMedewerkerVoornaamSubmit" value="Wijziging opslaan" class="medewerkerSubmit">
            </form>
            <span id="medewerkerInfo3">- medewerker tussenvoegsel wijzigen.</span>
            <form method="POST" id="changeMedewerkerTussenvoegselForm">
              <input type="text" readonly name="readVoornaam" value="Voornaam:" class="readVoornaam">
              <input type="text" readonly name="readTussenvoegsel" value="Tussenvoegsel:" class="readTussenvoegsel">
              <input type="text" readonly name="readAchternaam" value="Achternaam:" class="readAchternaam">
              <select name="changeMedewerkerVoornaamSelect2" class="changeMedewerkerVoornaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerVoornaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerTussenvoegselSelect2" class="changeMedewerkerTussenvoegselSelect">
                <?php
                  foreach ($GLOBALS['medewerkerTussenvoegsel'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerAchternaamSelect2" class="changeMedewerkerAchternaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerAchternaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <input type="text" name="changeMedewerkersTussenvoegsel" value="" placeholder="type hier de nieuwe gewijzigde tussenvoegsel" id="changeMedewerkersTussenvoegsel">
              <input type="submit" name="changeMedewerkerTussenvoegselSubmit" value="Wijziging opslaan" class="medewerkerSubmit">
            </form>
            <span id="medewerkerInfo4">- medewerker achternaam wijzigen.</span>
            <form method="POST" id="changeMedewerkerAchternaamForm">
              <input type="text" readonly name="readVoornaam" value="Voornaam:" class="readVoornaam">
              <input type="text" readonly name="readTussenvoegsel" value="Tussenvoegsel:" class="readTussenvoegsel">
              <input type="text" readonly name="readAchternaam" value="Achternaam:" class="readAchternaam">
              <select name="changeMedewerkerVoornaamSelect3" class="changeMedewerkerVoornaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerVoornaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerTussenvoegselSelect3" class="changeMedewerkerTussenvoegselSelect">
                <?php
                  foreach ($GLOBALS['medewerkerTussenvoegsel'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerAchternaamSelect3" class="changeMedewerkerAchternaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerAchternaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <input type="text" name="changeMedewerkersAchternaam" value="" placeholder="type hier de nieuwe gewijzigde achternaam" id="changeMedewerkersAchternaam">
              <input type="submit" name="changeMedewerkerAchternaamSubmit" value="Wijziging opslaan" class="medewerkerSubmit">
            </form>
            <span id="medewerkerInfo5">- medewerker rol wijzigen.</span>
            <form method="POST" id="changeMedewerkerRolForm">
              <input type="text" readonly name="readVoornaam" value="Voornaam:" class="readVoornaam">
              <input type="text" readonly name="readTussenvoegsel" value="Tussenvoegsel:" class="readTussenvoegsel">
              <input type="text" readonly name="readAchternaam" value="Achternaam:" class="readAchternaam">
              <input type="text" readonly name="readRol" value="Rol:" id="readRol">
              <select name="changeMedewerkerVoornaamSelect4" class="changeMedewerkerVoornaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerVoornaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerTussenvoegselSelect4" class="changeMedewerkerTussenvoegselSelect">
                <?php
                  foreach ($GLOBALS['medewerkerTussenvoegsel'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerAchternaamSelect4" class="changeMedewerkerAchternaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerAchternaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerRolSelect" id="changeMedewerkerRolSelect">
                <option value="0">Medewerker</option>
                <option value="1">Manager</option>
              </select>
              <input type="submit" name="changeMedewerkerRolSubmit" value="Wijziging opslaan" class="medewerkerSubmit">
            </form>
            <span id="medewerkerInfo6">- medewerker wachtwoord wijzigen.</span>
            <form method="POST" id="changeMedewerkerWachtwoordForm">
              <input type="text" readonly name="readVoornaam" value="Voornaam:" class="readVoornaam">
              <input type="text" readonly name="readTussenvoegsel" value="Tussenvoegsel:" class="readTussenvoegsel">
              <input type="text" readonly name="readAchternaam" value="Achternaam:" class="readAchternaam">
              <select name="changeMedewerkerVoornaamSelect5" class="changeMedewerkerVoornaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerVoornaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerTussenvoegselSelect5" class="changeMedewerkerTussenvoegselSelect">
                <?php
                  foreach ($GLOBALS['medewerkerTussenvoegsel'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="changeMedewerkerAchternaamSelect5" class="changeMedewerkerAchternaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerAchternaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <input type="password" name="changePassW" value="" placeholder="type hier een nieuwe gewijzigde sterke wachtwoord voor deze medewerker" id="changePassword">
              <input type="submit" name="changeMedewerkerWachtwoordSubmit" value="Wijziging opslaan" class="medewerkerSubmit">
            </form>
          </div>
          <div id="medewerkerRemoveDiv">
            <span id="medewerkerInfo7">- medewerker verwijderen.</span>
            <form method="POST" id="removeMedewerkerForm">
              <input type="text" readonly name="readVoornaam" value="Voornaam:" class="readVoornaam">
              <input type="text" readonly name="readTussenvoegsel" value="Tussenvoegsel:" class="readTussenvoegsel">
              <input type="text" readonly name="readAchternaam" value="Achternaam:" class="readAchternaam">
              <select name="removeMedewerkerVoornaamSelect" id="removeMedewerkerVoornaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerVoornaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="removeMedewerkerTussenvoegselSelect" id="removeMedewerkerTussenvoegselSelect">
                <?php
                  foreach ($GLOBALS['medewerkerTussenvoegsel'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <select name="removeMedewerkerAchternaamSelect" id="removeMedewerkerAchternaamSelect">
                <?php
                  foreach ($GLOBALS['medewerkerAchternaam'] as $val) {
                    echo "<option value=\"".utf8_encode($val)."\">".utf8_encode($val)."</option>";
                  }
                ?>
              </select>
              <input type="submit" name="removeMedewerkerSubmit" value="Verwijder" id="removeMedewerkerSubmit">
            </form>
          </div>
        </div>
        <div id="lastDiv_overzichtVenster">
          <form method="GET" id="overzichtForm">
            <input type="submit" name="overzichtVenster" value="Naar overzicht venster gaan" id="overzichtVenster">
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
