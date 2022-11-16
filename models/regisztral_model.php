<?php

class Regisztral_Model {
	public function get_data($vars): array {
		$retData['eredmeny'] = "";
		if (isset($vars['csaladi_nev'], $vars['utonev'], $vars['reg_login'], $vars['reg_pw'], $vars['reg_pw_confirm'])) {
			if (!$vars['reg_pw'] = $vars['reg_pw_confirm']) {
				$retData['eredmeny'] = "ERROR";
				$retData['uzenet'] = "A jelszó és a jelszó megerősítése nem egyeznek!";
			} else {
				try {
					$connection = Database::getConnection();
					$sqlSelect = "select id from felhasznalok where bejelentkezes = :bejelentkezes";
					$stmt = $connection->prepare($sqlSelect);
					$stmt->execute(array(':bejelentkezes' => $vars['reg_login']));
					if ($letezik = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$retData['eredmeny'] = "ERROR";
						$retData['uzenet'] = "Ilyen felhasználó már létezik. Próbáljon másikat!";
					} else {
						$sqlInsert = "insert into felhasznalok(id, csaladi_nev, utonev, bejelentkezes, jelszo, jogosultsag) 
										values (0, :csaladi_nev, :utonev, :bejelentkezes, :jelszo, :jogosultsag)";
						$stmt = $connection->prepare($sqlInsert);
						$stmt->execute(array(
										   ':csaladi_nev' => $vars['csaladi_nev'],
										   ':utonev' => $vars['utonev'],
										   ':bejelentkezes' => $vars['reg_login'],
										   ':jelszo' => sha1($vars['reg_pw']),
										   ':jogosultsag' => '_1_'
									   ));
						if ($darab = $stmt->rowCount()) {
							$uj_id = $connection->lastInsertId();
							$retData['uzenet'] = "Sikeres regisztráció.<br>Azonosítója: $uj_id<br>
								Felhasználónevével és jelszavával beléphet a \"Belépés\" menüpont alatt";
						} else {
							$retData['eredmeny'] = "ERROR";
							$retData['uzenet'] = "Sikertelen regisztráció";
						}
					}
				} catch (PDOException $e) {
					$retData['eredmeny'] = "ERROR";
					$retData['uzenet'] = "Hiba: " . $e->getMessage();
				}
			}
		}
		return $retData;
	}
}
