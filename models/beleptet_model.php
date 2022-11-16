<?php
//Osztály létrehozása, amely a bejelentkeztetést végzi.
class Beleptet_Model {
	public function get_data($vars): array {
		$retData['eredmeny'] = "";
		try {
			//Csatlakozunk az adatbátishoz, maj beolvassuk a bejelentkezéshez szükséges adatokat a felhasznalok tablabol.
			$connection = Database::getConnection();
			$sqlSelect = "select id, csaladi_nev, utonev, bejelentkezes, jogosultsag from felhasznalok where bejelentkezes=:bejelentkezes and jelszo=:jelszo";
			$stmt = $connection->prepare($sqlSelect);
			$stmt->execute(array(
							   ':bejelentkezes' => $vars['login'],
							   ':jelszo' => sha1($vars['password'])
						   ));
			$felhasznalo = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//Ellenőrizzük, hogy a megadott adatok megtalálhatóak-e az adatbázisban, hogyha nem vagy több felhasználó is azonos néven található, akkor hibaüzenetet ad vissza.
			///Amennyiben viszont sikeres volt a bejelentkezés, a felhasználó adatait elmentjük  változókba.
			switch (count($felhasznalo)) {
				case 0:
					$retData['eredmeny'] = "ERROR";
					$retData['uzenet'] = "Helytelen felhasználói név-jelszó pár!";
					break;
				case 1:
					$retData['eredmény'] = "OK";
					$retData['uzenet'] = "Kedves " . $felhasznalo[0]['csaladi_nev'] . " " . $felhasznalo[0]['utonev'] . "!<br><br>
					                      Jó munkát kívánunk rendszerünkkel.<br><br>
										  Az üzemeltetők";
					$_SESSION['userid'] = $felhasznalo[0]['id'];
					$_SESSION['username'] = $felhasznalo[0]['bejelentkezes'];
					$_SESSION['userlastname'] = $felhasznalo[0]['csaladi_nev'];
					$_SESSION['userfirstname'] = $felhasznalo[0]['utonev'];
					$_SESSION['userlevel'] = $felhasznalo[0]['jogosultsag'];
					Menu::setMenu();
					break;
				default:
					$retData['eredmény'] = "ERROR";
					$retData['uzenet'] = "Több felhasználót találtunk a megadott felhasználói név -jelszó párral!";
			}
		} catch (PDOException $e) {
			$retData['eredmény'] = "ERROR";
			$retData['uzenet'] = "Adatbázis hiba: " . $e->getMessage() . "!";
		}
		return $retData;
	}
}
