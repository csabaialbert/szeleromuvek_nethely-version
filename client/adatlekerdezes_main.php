<?php
//Globális változók megadása és az adatbázisból kiolvasó függvények meghívása.
try{
$client = new SoapClient(SITE_ROOT . 'server/tables.wsdl');
$locations = $client->getlocations();
$counties = $client->getcounties();
$towers = $client->gettowers();
$len = count($counties->counties);
}
catch(SoapFault $e ){echo $e; echo SERVER_ROOT;}
?>


<h2 style="width: fit-content; margin-left:auto; margin-right:auto;">Üdvözöljük!</h2>
<h3 style="width: fit-content; margin-left:auto; margin-right:auto;">Ezen az oldalon megtekintheti az adatbázis három táblájának adatait, SOAP API segítségével.</h3>

<form style="background-color:transparent; width: fit-content; margin-left:auto; margin-right:auto;" name="tableselect" text="Tábla választás" method="POST">
    <select style="margin-left:auto; margin-right:auto;" name="megye" required="required" onchange="javascript:tableselect.submit();">
        <!--Legördülő menü, amiben kiválaszthatjuk a lekérdezni kívánt megyét--->
        <option value="">Válasszon megyét!</option>
        <?php
        foreach ($counties->counties as $county) { ?>

            <option value="<?php echo $county['id']; ?>" <?php if (isset($_POST['megye']) && $_POST['megye'] == $county['id']) {
                                                                echo "selected=selected";
                                                            } ?>><?php echo $county['nev']; ?></option>
        <?php } ?>
    </select>
    <?php
    if (isset($_POST['megye']) && trim($_POST['megye']) != "") { ?>
        <!--Amennyiben kiválasztottunk, megjelenik még egy legördülő menü amiben a hozzá tartozó helységek közül választhatunk egyet, amennyiben van az adatbázisban.-->
        <select style="margin-left:auto; margin-right:auto;" name="hely" required="required" onchange="javascript:tableselect.submit();">
            <option value="">Válasszon helységet!</option>
            <?php
            foreach ($locations->locations as $loc) {
                if ($loc['megyeid'] == $_POST['megye']) { ?>
                    <option value="<?php echo $loc['id']; ?>" <?php if (isset($_POST['hely']) && $_POST['hely'] == $loc['id']) {
                                                                    echo "selected=selected";
                                                                } ?>><?php echo $loc['nev']; ?></option>
        <?php }
            }
        } ?>
        </select>
        <!--A küldés gomb, amivel liírjuk egy táblázatba a kiválasztásokhoz kapcsolható szílerőműveket és tulajdonságaikat.-->
        <input style="margin-top:20px;" class="btn btn-outline-primary btn-sm btn-block" type="submit" name="kiir" value="Kiírás">
        <?php
        //Ellenőrizzük, hogy választottunk-e értékeket a listákból és hogy a kiir gombot megnyomtuk-e.
        if (isset($_POST['hely']) && trim($_POST['hely']) != "" && trim($_POST['megye']) != "" && isset($_POST['megye']) && isset($_POST["kiir"])) {
        ?>

            <div div class="table-responsive text-nowrap table-hover">
                <table class="table table-striped">
                    <!--Az erőművek kiírása táblázatba.-->
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Darab</th>
                            <th scope="col">Teljesítmény</th>
                            <th scope="col">Kezdeti év</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($towers->towers as $tower) {
                            if ($tower['helyszinid'] == $_POST['hely']) { ?>

                                <tr>
                                    <td scope="col"><?php echo $tower['id'] ?></td>
                                    <td><?php echo $tower['darab'] ?></td>
                                    <td><?php echo $tower['teljesitmeny'] ?></td>
                                    <td><?php echo $tower['kezdev'] ?></td>
                                </tr>

                            <?php } ?>


                    <?php }
                    }
                    unset($_POST['megye']);
                    unset($_POST['hely']); ?>
                    </tbody>
                </table>
            </div>

</form>