<aside>
  <h5 style="max-width:200pt;padding-left:10pt;">Megjegyzés: Hétvégeken nem található adat!</h5>
</aside>
<h1 style="margin: auto; margin-top: 20px; text-align: center;">Árfolyamgrafikon</h1>
<h2 style="margin: auto; margin-top: 20px; margin-bottom: 40px; text-align: center;">Grafikon paraméterek</h2>
<?php
//Globális változók létrehozása.
$today = date("m/d/Y");
$eredmeny = "sss";
$eredmeny2 = "aaa";
$rdate = "fff";
$ddate = "ddd";
$rdate2 = "fff";
$ddate2 = "ddd";
$currency1 = "ooo";
$currency2 = "www";
$dev = "";
$dev2;
$days = array();
$values = array();
$foo = array();
$error = "A kiválasztott devizákra az adott napon nem található adat!";
$er = 0;

?>
<!--Használt scriptek meghívása.-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--Minimális táblázat formázási stílus.-->

<!--A daterangepicker script meghívása, és paraméterként megadjuk a mai dátumot korlátozó tényezőként, hogy a jövőbeni napokat ne tudjuk kijelölni a lekérdezéshez.-->
<script>
  $(function() {

    $("#daterangepicker").daterangepicker({
      "maxDate": "<?php echo $today; ?>"
    });
  });
</script>

<body>
  <!--form létrehozása, egy inputtal ellátva, amibe belekattintva egy dátum intervallum választó jelenik meg.-->
  <form name="tableselect" text="Tábla választás" method="POST">
    <div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto;">
      <label for="daterangepicker">A dátum intervallum:</label>
      <input class="form-control" type="text" autocomplete="off" name="datum" id="daterangepicker" required="required">
    </div>
    <!--Két legördülő menü létrehozása, amiben az MNB weboldaláról lekért pénznemek jelennek meg.-->
    <!--Az első legördülő menü, ahol a második pénznemet kiválaszthatjuk az MNB szolgáltatástól visszakapott értékekből.-->
    <div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto;">
      <label for="daterangepicker">Az első pénznem:</label>
      <select id="center" name="penznem" required="required">
        <option value="">Válasszon Devizát!</option>
        <?php
        print_r($_POST['datum']);
        $curr = currencies();
        for (
          $i = 0;
          $i < count($curr);
          $i++
        ) { ?>
          <option value="<?php echo ($curr[$i][0])->__toString(); ?>"><?php echo ($curr[$i][0])->__toString();
                                                                    } ?></option>
      </select>
    </div>
    <!--A második legördülő menü, ahol a második pénznemet kiválaszthatjuk az MNB szolgáltatástól visszakapott értékekből.-->
    <div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto;">
      <label for="daterangepicker">Az első pénznem:</label>
      <select id="center" name="penznem2" required="required">
        <option value="">Válasszon Devizát!</option>
        <?php
        $curr1 = currencies();
        for (
          $i = 0;
          $i < count($curr1);
          $i++
        ) { ?>
          <option value="<?php echo ($curr1[$i][0])->__toString(); ?>"><?php echo ($curr1[$i][0])->__toString();
                                                                      } ?></option>
      </select>
    </div>
    <?php
    //Amennyiben választottunk egy intervallumot és két pénznemet, szétbontjuk a dátumokat és a megfelelő formátummá alakítjuk, amit elfogad az MNB szolgáltatása.
    //A menükben kiválasztott pénznemeket és az átalakított dátumokat egy - egy változóba mentjük.
    if (isset($_POST['datum']) && isset($_POST['penznem']) && isset($_POST['kuld']) && $_POST['datum'] != "" && $_POST['penznem'] != "" && $_POST['penznem2'] != "") {
      $sdate = explode("-", $_POST['datum']);
      $ddate = explode("/", $sdate[0]);
      $ddate2 = explode("/", $sdate[1]);
      $rdate = $ddate[2] . "-" . $ddate[0] . "-" . $ddate[1];
      $rdate2 = $ddate2[2] . "-" . $ddate2[0] . "-" . $ddate2[1];
      $currency1 = $_POST['penznem'];
      $currency2 = $_POST['penznem2'];
    }
    //Az MNB szolgáltatását használva lekérjük a megadott devizák árfolyamát a megadott intervallumban, majd xml-ként egy változóba mentjük.
    if (isset($_POST['penznem']) && isset($_POST['datum']) && isset($_POST['kuld']) && $currency1 != $currency2) {
      $eredmeny = simplexml_load_string(exc_rates($rdate, $rdate2, $currency1));
      $eredmeny2 = simplexml_load_string(exc_rates($rdate, $rdate2, $currency2));
      if ($eredmeny->count() != 0 || $eredmeny2->count() != 0) {
        //Az szolgáltatástól kapott adatokat először json formátumba kódoljuk, majd dekódoljuk és tömbökké alakítjuk.
        $json = json_encode($eredmeny);
        $array = json_decode($json, TRUE);
        $dev = ($array["Day"]);
        $eredmeny = "";
        $_POST['penznem'] = '';
        $_POST['penznem2'] = '';
        $_POST['datum'] = '';
        $json2 = json_encode($eredmeny2);
        $array2 = json_decode($json2, TRUE);
        $dev2 = ($array2["Day"]);
        $eredmeny2 = "";
        $er = 0;
      } else {
        $er = 1;
      }
    }
    //Mivel a HUF nem szerepel alekérdezhető pénznemek között, mert minden devizát forintban adnak meg.
    //Hogyha a felhasználó mindkét helyre forintot adna meg, akkor is egyet kapnánk, akárcsak az eurónál és minden más pénznemnél.
    //Egyserűsítésként abban az esetben, ha mindkét pénznem ugyanaz, akkor alapból EUR-t küldünk és nem fog hibát jelezni a lekérdezés.
    if (isset($_POST['penznem']) && isset($_POST['datum']) && isset($_POST['kuld']) && $currency1 == $currency2) {
      $eredmeny = simplexml_load_string(exc_rates($rdate, $rdate2, "EUR"));
      $eredmeny2 = simplexml_load_string(exc_rates($rdate, $rdate2, "EUR"));
      if ($eredmeny->count() != 0 || $eredmeny2->count() != 0) {
        $json = json_encode($eredmeny);
        $array = json_decode($json, TRUE);
        $dev = ($array["Day"]);
        $eredmeny = "";
        $_POST['penznem'] = '';
        $_POST['penznem2'] = '';
        $_POST['datum'] = '';
        $json2 = json_encode($eredmeny2);
        $array2 = json_decode($json2, TRUE);
        $dev2 = ($array2["Day"]);
        $eredmeny2 = "";
        $er = 0;
      } else {
        $er = 1;
      }
    }

    ?>
    <!--A küld gomb létrehozása.-->
    <div class="form-group" style="width: 400px; margin-left:auto; margin-right: auto;">
      <input class="btn btn-outline-primary btn-lg btn-block" type="submit" name="kuld" value="Küld">
    </div>
    <?php
    if ($dev == "" && isset($_POST["kuld"])) { ?>
      <h3 style="margin: auto; margin-top: 40px; padding-bottom:50px; text-align: center;">A megadott időintervallumban (<?php echo $rdate . " és " . $rdate2; ?>) nem található adat!</h3>
    <?php ;} ?>
    <!--Ellenőrizzük, hogy az elején megadott változók adatai cserélődtek-e, hogyha igen, akkor kiírja a áblázatot és a grafikont megrajzolja.-->
    <?php if ($currency1 != "ooo" && $currency2 != "www" && $rdate != "fff" && $rdate2 != "fff" && $dev != "") { ?>
      <h3 style="margin: auto; margin-top: 30px; margin-bottom: 20px; text-align: center;">A megadott devizák atváltási aránya az adott napon:</h3>

      <!--Elkészítjük a táblázat fejlécét, ahol az első oszlopban a dátum, a másodikban pedig a pénznemek találhatóak.-->
      <div class="table-responsive-sm " style="max-width: 60%; margin: auto;">
        <table class="table table-sm text-center table-hover">
          <tr>
            <th scope="col">Dátum</th>
            <th scope="col">Árfolyam ( <?php echo ($currency1 . " -> " . $currency2); ?> )</th>
          </tr>
          <?php
          $cnt = 0; ?>

          <?php
          //Ellenőrizzuk, hogy a kapott tömb nem üres-e.
          if ($dev != "") {
            //Amennyiben található benne adat, egy foreach ciklus végigmegy rajta és minden ciklusban egy-egy elemét a $fooo változóba teszi. 
            foreach ($dev as $fooo) {
              //Ellenőrizzük, hogy a kapott változóban találhatóak-e, hogy nem forintot választottak egyik pénznemhez sem, valamint hogy a küldés gomb megnyomásra került-e.
              //Ha minden megfelel, akkor az első pénznem értékét elosztjuk a második értékével, így megkapjuk, hogy mennyit ér az adott napon az első a másodikban kifejezve.
              if (isset($_POST["kuld"]) && $currency1 != "HUF" && $currency2 != "HUF" && $fooo != "") { ?>
                <!--Minden kapott napot a táblázat egy soraként írjuk ki. Első szlopban a dátum, másodikban az érték.-->
                <tr>
                  <td> <?php echo $fooo["@attributes"]["date"];
                        array_push($days, $fooo["@attributes"]["date"]); ?></td>
                  <td><?php echo (number_format(floatval(str_replace(',', '.', trim($fooo["Rate"]))) / floatval(str_replace(',', '.', trim($dev2[$cnt]["Rate"]))), 5));
                      array_push($values, (number_format(floatval(str_replace(',', '.', trim($fooo["Rate"]))) / floatval(str_replace(',', '.', trim($dev2[$cnt]["Rate"]))), 5))); ?></td>
                </tr>
              <?php $cnt += 1;
              }
              //Ellenőrizzük, hogy a kapott változóban találhatóak-e, hogy mindkét pénznemhez forintot adtak-e meg, valamint hogy a küldés gomb megnyomásra került-e.
              //Mivel a HUF nem alálható külöm az MNB adatbázisában ezért helyette EUR adatai vannak lekérve. Ezeket elosztjuk egymással és 1-et kapunk minden napra.
              if (isset($_POST["kuld"]) && $currency1 == "HUF" && $currency2 == "HUF" && $fooo != "") { ?>
              <!--Minden kapott napot a táblázat egy soraként írjuk ki. Első szlopban a dátum, másodikban az érték.-->
                <tr>
                  <td> <?php echo $fooo["@attributes"]["date"];
                        array_push($days, $fooo["@attributes"]["date"]); ?></td>
                  <td><?php echo (number_format(floatval(str_replace(',', '.', trim($fooo["Rate"]))) / floatval(str_replace(',', '.', trim($dev2[$cnt]["Rate"]))), 5));
                      array_push($values, (number_format(floatval(str_replace(',', '.', trim($fooo["Rate"]))) / floatval(str_replace(',', '.', trim($dev2[$cnt]["Rate"]))), 5))); ?></td>
                </tr>
              <?php $cnt += 1;
              }
              // Ha az első pénznem HUF volt, a második pedig egy másik, akkor 1-et elosztunk a második értékével és megkapjuk, hogy aznap 1 forint mennyit ért a másikhoz képest.
              if (isset($_POST["kuld"]) && $currency1 == "HUF" && $currency2 != "HUF" && $fooo != "") { ?>
              <!--Minden kapott napot a táblázat egy soraként írjuk ki. Első szlopban a dátum, másodikban az érték.-->
                <tr>
                  <td> <?php echo $fooo["@attributes"]["date"];
                        array_push($days, $fooo["@attributes"]["date"]); ?></td>
                  <td><?php echo (number_format(1 / floatval(str_replace(',', '.', trim($dev2[$cnt]["Rate"]))), 5));
                      array_push($values, (number_format(1 / floatval(str_replace(',', '.', trim($dev2[$cnt]["Rate"]))), 5))); ?></td>
                </tr>
              <?php $cnt += 1;
              }
              //Ha csak a második pénznem volt HUF, akkor az első pénznem értékét írjuk ki, mivel az MNB szolgáltatása alapértelmezetten forintban adja meg a devizák értékét.
              if (isset($_POST["kuld"]) && $currency1 != "HUF" && $currency2 == "HUF" && $fooo != "") { ?>
              <!--Minden kapott napot a táblázat egy soraként írjuk ki. Első szlopban a dátum, másodikban az érték.-->
                <tr>
                  <td> <?php echo $fooo["@attributes"]["date"];
                        array_push($days, $fooo["@attributes"]["date"]); ?></td>
                  <td><?php echo (number_format(floatval(str_replace(',', '.', trim($fooo["Rate"]))), 5));
                      array_push($values, (number_format(floatval(str_replace(',', '.', trim($fooo["Rate"]))), 5))); ?></td>
                </tr>
          <?php $cnt += 1;
              }
            }
          } ?>
        </table>
      </div>

      <!--Grafikon adateinak megadása, valamint a megrajzolása chart.js hsználatával.-->
      <div style="max-width: 60%; margin: auto;">
        <canvas id="myChart"></canvas>
      </div>

      <script>
        var dat = <?php echo json_encode($days); ?>;
        var val = <?php echo json_encode($values); ?>;


        const data = {
          labels: dat,
          datasets: [{
            label: 'Árfolyamok',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: val,
          }]
        };

        const config = {
          type: 'line',
          data: data,
          options: {}
        };
      </script>
      <script>
        if (<?php echo (count($values)); ?> != 0) {
          const myChart = new Chart(
            document.getElementById('myChart'),
            config
          );
        }
      </script>
    <?php } ?>
    <!--A daterangepicker megjelenését(stílusát) meghatározó paraméterek.-->
    <style>
      .daterangepicker {
        position: absolute;
        color: inherit;
        background-color: #fff;
        border-radius: 4px;
        border: 1px solid #ddd;
        width: 278px;
        max-width: none;
        padding: 0;
        margin-top: 7px;
        top: 100px;
        left: 20px;
        z-index: 3001;
        display: none;
        font-family: arial;
        font-size: 15px;
        line-height: 1em;
      }

      .daterangepicker:before,
      .daterangepicker:after {
        position: absolute;
        display: inline-block;
        border-bottom-color: rgba(0, 0, 0, 0.2);
        content: '';
      }

      .daterangepicker:before {
        top: -7px;
        border-right: 7px solid transparent;
        border-left: 7px solid transparent;
        border-bottom: 7px solid #ccc;
      }

      .daterangepicker:after {
        top: -6px;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #fff;
        border-left: 6px solid transparent;
      }

      .daterangepicker.opensleft:before {
        right: 9px;
      }

      .daterangepicker.opensleft:after {
        right: 10px;
      }

      .daterangepicker.openscenter:before {
        left: 0;
        right: 0;
        width: 0;
        margin-left: auto;
        margin-right: auto;
      }

      .daterangepicker.openscenter:after {
        left: 0;
        right: 0;
        width: 0;
        margin-left: auto;
        margin-right: auto;
      }

      .daterangepicker.opensright:before {
        left: 9px;
      }

      .daterangepicker.opensright:after {
        left: 10px;
      }

      .daterangepicker.drop-up {
        margin-top: -7px;
      }

      .daterangepicker.drop-up:before {
        top: initial;
        bottom: -7px;
        border-bottom: initial;
        border-top: 7px solid #ccc;
      }

      .daterangepicker.drop-up:after {
        top: initial;
        bottom: -6px;
        border-bottom: initial;
        border-top: 6px solid #fff;
      }

      .daterangepicker.single .daterangepicker .ranges,
      .daterangepicker.single .drp-calendar {
        float: none;
      }

      .daterangepicker.single .drp-selected {
        display: none;
      }

      .daterangepicker.show-calendar .drp-calendar {
        display: block;
      }

      .daterangepicker.show-calendar .drp-buttons {
        display: block;
      }

      .daterangepicker.auto-apply .drp-buttons {
        display: none;
      }

      .daterangepicker .drp-calendar {
        display: none;
        max-width: 270px;
      }

      .daterangepicker .drp-calendar.left {
        padding: 8px 0 8px 8px;
      }

      .daterangepicker .drp-calendar.right {
        padding: 8px;
      }

      .daterangepicker .drp-calendar.single .calendar-table {
        border: none;
      }

      .daterangepicker .calendar-table .next span,
      .daterangepicker .calendar-table .prev span {
        color: #fff;
        border: solid black;
        border-width: 0 2px 2px 0;
        border-radius: 0;
        display: inline-block;
        padding: 3px;
      }

      .daterangepicker .calendar-table .next span {
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
      }

      .daterangepicker .calendar-table .prev span {
        transform: rotate(135deg);
        -webkit-transform: rotate(135deg);
      }

      .daterangepicker .calendar-table th,
      .daterangepicker .calendar-table td {
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
        min-width: 32px;
        width: 32px;
        height: 24px;
        line-height: 24px;
        font-size: 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        white-space: nowrap;
        cursor: pointer;
      }

      .daterangepicker .calendar-table {
        border: 1px solid #fff;
        border-radius: 4px;
        background-color: #fff;
      }

      .daterangepicker .calendar-table table {
        width: 100%;
        margin: 0;
        border-spacing: 0;
        border-collapse: collapse;
      }

      .daterangepicker td.available:hover,
      .daterangepicker th.available:hover {
        background-color: #eee;
        border-color: transparent;
        color: inherit;
      }

      .daterangepicker td.week,
      .daterangepicker th.week {
        font-size: 80%;
        color: #ccc;
      }

      .daterangepicker td.off,
      .daterangepicker td.off.in-range,
      .daterangepicker td.off.start-date,
      .daterangepicker td.off.end-date {
        background-color: #fff;
        border-color: transparent;
        color: #999;
      }

      .daterangepicker td.in-range {
        background-color: #ebf4f8;
        border-color: transparent;
        color: #000;
        border-radius: 0;
      }

      .daterangepicker td.start-date {
        border-radius: 4px 0 0 4px;
      }

      .daterangepicker td.end-date {
        border-radius: 0 4px 4px 0;
      }

      .daterangepicker td.start-date.end-date {
        border-radius: 4px;
      }

      .daterangepicker td.active,
      .daterangepicker td.active:hover {
        background-color: #357ebd;
        border-color: transparent;
        color: #fff;
      }

      .daterangepicker th.month {
        width: auto;
      }

      .daterangepicker td.disabled,
      .daterangepicker option.disabled {
        color: #999;
        cursor: not-allowed;
        text-decoration: line-through;
      }

      .daterangepicker select.monthselect,
      .daterangepicker select.yearselect {
        font-size: 12px;
        padding: 1px;
        height: auto;
        margin: 0;
        cursor: default;
      }

      .daterangepicker select.monthselect {
        margin-right: 2%;
        width: 56%;
      }

      .daterangepicker select.yearselect {
        width: 40%;
      }

      .daterangepicker select.hourselect,
      .daterangepicker select.minuteselect,
      .daterangepicker select.secondselect,
      .daterangepicker select.ampmselect {
        width: 50px;
        margin: 0 auto;
        background: #eee;
        border: 1px solid #eee;
        padding: 2px;
        outline: 0;
        font-size: 12px;
      }

      .daterangepicker .calendar-time {
        text-align: center;
        margin: 4px auto 0 auto;
        line-height: 30px;
        position: relative;
      }

      .daterangepicker .calendar-time select.disabled {
        color: #ccc;
        cursor: not-allowed;
      }

      .daterangepicker .drp-buttons {
        clear: both;
        text-align: right;
        padding: 8px;
        border-top: 1px solid #ddd;
        display: none;
        line-height: 12px;
        vertical-align: middle;
      }

      .daterangepicker .drp-selected {
        display: inline-block;
        font-size: 12px;
        padding-right: 8px;
      }

      .daterangepicker .drp-buttons .btn {
        margin-left: 8px;
        font-size: 12px;
        font-weight: bold;
        padding: 4px 8px;
      }

      .daterangepicker.show-ranges.single.rtl .drp-calendar.left {
        border-right: 1px solid #ddd;
      }

      .daterangepicker.show-ranges.single.ltr .drp-calendar.left {
        border-left: 1px solid #ddd;
      }

      .daterangepicker.show-ranges.rtl .drp-calendar.right {
        border-right: 1px solid #ddd;
      }

      .daterangepicker.show-ranges.ltr .drp-calendar.left {
        border-left: 1px solid #ddd;
      }

      .daterangepicker .ranges {
        float: none;
        text-align: left;
        margin: 0;
      }

      .daterangepicker.show-calendar .ranges {
        margin-top: 8px;
      }

      .daterangepicker .ranges ul {
        list-style: none;
        margin: 0 auto;
        padding: 0;
        width: 100%;
      }

      .daterangepicker .ranges li {
        font-size: 12px;
        padding: 8px 12px;
        cursor: pointer;
      }

      .daterangepicker .ranges li:hover {
        background-color: #eee;
      }

      .daterangepicker .ranges li.active {
        background-color: #08c;
        color: #fff;
      }

      /*  Larger Screen Styling */
      @media (min-width: 564px) {
        .daterangepicker {
          width: auto;
        }

        .daterangepicker .ranges ul {
          width: 140px;
        }

        .daterangepicker.single .ranges ul {
          width: 100%;
        }

        .daterangepicker.single .drp-calendar.left {
          clear: none;
        }

        .daterangepicker.single .ranges,
        .daterangepicker.single .drp-calendar {
          float: left;
        }

        .daterangepicker {
          direction: ltr;
          text-align: left;
        }

        .daterangepicker .drp-calendar.left {
          clear: left;
          margin-right: 0;
        }

        .daterangepicker .drp-calendar.left .calendar-table {
          border-right: none;
          border-top-right-radius: 0;
          border-bottom-right-radius: 0;
        }

        .daterangepicker .drp-calendar.right {
          margin-left: 0;
        }

        .daterangepicker .drp-calendar.right .calendar-table {
          border-left: none;
          border-top-left-radius: 0;
          border-bottom-left-radius: 0;
        }

        .daterangepicker .drp-calendar.left .calendar-table {
          padding-right: 8px;
        }

        .daterangepicker .ranges,
        .daterangepicker .drp-calendar {
          float: left;
        }
      }

      @media (min-width: 730px) {
        .daterangepicker .ranges {
          width: auto;
        }

        .daterangepicker .ranges {
          float: left;
        }

        .daterangepicker.rtl .ranges {
          float: right;
        }

        .daterangepicker .drp-calendar.left {
          clear: none !important;
        }
      }
    </style>

</body>