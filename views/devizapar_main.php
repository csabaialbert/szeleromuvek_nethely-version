<aside>	
<h5 style="max-width:200pt;padding-left:10pt;">Megjegyzés: Hétvégeken nem található adat!</h5>
</aside>	
<h1 style="margin: auto;  padding-bottom: 40px; text-align: center; margin-top:0; padding-top:  20px; margin-bottom:0;">Deviza árfolyam megtekintés egy adott napra</h1>
<?php
//Globális változók létrehozása.
$today = date("m/d/Y");
$eredmeny = "sss";
$eredmeny2 = "aaa";
$rdate = "fff";
$currency1 = "ooo";
$currency2 = "www";
$dev;
$dev2;
$foo = 0.0;
$error = "A kiválasztott devizákra az adott napon nem található adat!";
$er = 0;
?>
<!--Használt scriptek meghívása.-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>jQuery UI Datepicker - Default functionality</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js">
</script>
<!--A datepicker script meghívása, és paraméterként megadjuk a mai dátumot korlátozó tényezőként, hogy a jövőbeni napokat ne tudjuk kijelölni a lekérdezéshez.-->
<script>
	$(function() {
		$("#datepicker").datepicker({
			"maxDate": "<?php echo $today; ?>"
		});
	});
</script>

<body>


	<!--form létrehozása, egy inputtal ellátva, amibe belekattintva egy dátum választó jelenik meg.-->
	<form id="center" name="tableselect" text="Tábla választás" method="POST">
		<div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto; ">
			<label for="daterangepicker">Dátum:</label>
			<input type="text" name="datum" id="datepicker" required="required">
		</div>
		<!--Két legördülő menü létrehozása, amiben az MNB weboldaláról lekért pénznemek jelennek meg.-->
		<div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto;">
			<label for="daterangepicker">Az első pénznem:</label>
			<select id="center" name="penznem" required="required">
				<option value=" ">Válasszon Devizát!</option>
				<?php
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
		<div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto;">
			<label for="daterangepicker">Az első pénznem:</label>
			<select id="center" name="penznem2" required="required">
				<option value=" ">Válasszon Devizát!</option>
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
		//Amennyiben választottunk egy dátumot és két pénznemet, a dátumot és a megfelelő formátummá alakítjuk, amit elfogad az MNB szolgáltatása.
		//A menükben kiválasztott pénznemeket és az átalakított dátumot egy - egy változóba mentjük.

		if (isset($_POST['datum']) && isset($_POST['penznem']) && isset($_POST['kuld']) && $_POST['datum'] != "" && $_POST['penznem'] != "" && $_POST['penznem2'] != "") {
			$sdate = explode("/", $_POST['datum']);
			$rdate = $sdate[2] . "-" . $sdate[0] . "-" . $sdate[1];
			$currency1 = $_POST['penznem'];
			$currency2 = $_POST['penznem2'];
		}
		//Az MNB szolgáltatását használva lekérjük a megadott devizák árfolyamát a megadott intervallumban, majd xml-ként egy változóba mentjük.
		if (isset($_POST['penznem']) && isset($_POST['datum']) && $_POST['penznem'] != "HUF" && $_POST['penznem2'] != "HUF" && isset($_POST['kuld'])) {
			$eredmeny = simplexml_load_string(exc_rates($rdate, $rdate, $currency1));
			$eredmeny2 = simplexml_load_string(exc_rates($rdate, $rdate, $currency2));
			if ($eredmeny->count() != 0 || $eredmeny2->count() != 0) {
				//Az szolgáltatástól kapott adatokat először json formátumba kódoljuk, majd dekódoljuk és tömbökké alakítjuk.
				$json = json_encode($eredmeny);
				$array = json_decode($json, TRUE);
				$dev = floatval(str_replace(',', '.', trim($array["Day"]["Rate"])));
				$eredmeny = "";
				$_POST['penznem'] = '';
				$_POST['penznem2'] = '';
				$_POST['datum'] = '';
				$json2 = json_encode($eredmeny2);
				$array2 = json_decode($json2, TRUE);
				$dev2 = floatval(str_replace(',', '.', trim($array2["Day"]["Rate"])));
				$foo = $dev / $dev2;
				$eredmeny2 = "";
				$er = 0;
			} else {
				$er = 1;
			}
		}
		//Mivel a HUF nem szerepel alekérdezhető pénznemek között, mert minden devizát forintban adnak meg.
		//Hogyha a felhasználó mindkét helyre forintot adna meg, akkor is egyet kapnánk, akárcsak az eurónál és minden más pénznemnél.
		//Egyserűsítésként abban az esetben, ha mindkét pénznem ugyanaz, akkor alapból EUR-t küldünk és nem fog hibát jelezni a lekérdezés.
		if (isset($_POST['penznem']) && isset($_POST['datum']) && $_POST['penznem'] == "HUF" && $_POST['penznem2'] != "HUF" && isset($_POST['kuld'])) {

			$eredmeny2 = simplexml_load_string(exc_rates($rdate, $rdate, $currency2));
			if ($eredmeny2->count() != 0) {

				$_POST['penznem'] = '';
				$_POST['penznem2'] = '';
				$_POST['datum'] = '';
				$json2 = json_encode($eredmeny2);
				$array2 = json_decode($json2, TRUE);
				$dev2 = floatval(str_replace(',', '.', trim($array2["Day"]["Rate"])));
				$foo = 1 / $dev2;
				$eredmeny2 = "";
				$er = 0;
			} else {
				$er = 1;
			}
		}
		if (isset($_POST['penznem']) && isset($_POST['datum']) && $_POST['penznem'] != "HUF" && $_POST['penznem2'] == "HUF" && isset($_POST['kuld'])) {
			$eredmeny = simplexml_load_string(exc_rates($rdate, $rdate, $currency1));
			if ($eredmeny->count() != 0) {

				$_POST['penznem'] = '';
				$_POST['penznem2'] = '';
				$_POST['datum'] = '';
				$json = json_encode($eredmeny);
				$array = json_decode($json, TRUE);
				$dev = floatval(str_replace(',', '.', trim($array["Day"]["Rate"])));
				$foo = $dev;
				$eredmeny = "";
				$er = 0;
			} else {
				$er = 1;
			}
		}
		if (isset($_POST['penznem']) && isset($_POST['datum']) && $_POST['penznem'] == "HUF" && $_POST['penznem2'] == "HUF" && isset($_POST['kuld'])) {


			$foo = 1;
			$er = 0;
		}
		?>
		<div class="form-group" style="width: 400px; margin-left:auto; margin-right: auto; margin-bottom: 0 px; padding-bottom:  120px;">
			<input class="btn btn-outline-primary btn-lg btn-block" type="submit" name="kuld" value="Küld">
		</div>
	</form>
		<?php if ($currency1 != "ooo" && $currency2 != "www" && $rdate != "fff") { ?>

			<!--A küld gomb megnyomása után kiírja az megadott napra a devizapár értékét.-->
			<h3 style="margin: auto; margin-top: 20px;  text-align: center;">A megadott devizák atváltási aránya a megadott napon (<?php echo $rdate; ?>):</h3>
			<h3 style="margin: auto; margin-top: 20px;  text-align: center;"> <?php echo $currency1 . " => " . $currency2; ?></h3>
			<h4 style="margin: auto; margin-top: 20px; margin-bottom: 0 px; padding-bottom:  120px; text-align: center;"><?php if (isset($_POST["kuld"]) && $foo != 0) {
																																echo $foo;
																															}
																															if ($er == 1) {
																																echo $error;
																															}
																														} ?></h4>

</body>