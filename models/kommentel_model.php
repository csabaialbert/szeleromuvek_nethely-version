<?php

class Kommentel_Model {
	public function get_data($vars): array {
		$retData['eredmeny'] = "";
		if ($_SESSION['userid'] == 0 || !isset($_SESSION['userid'])) {
			$retData['eredmeny'] = "ERROR";
			$retData['uzenet'] = "Kommentelés csak regisztrált felhasználók számára lehetséges!";
		} else {
			if (isset($vars['ujkomment'])) {
				try {
					$connection = Database::getConnection();
					$sqlInsert = "insert into kommentek(userid, hirid, komment) values (:userid, :hirid, :komment)";
					$stmt = $connection->prepare($sqlInsert);
					$stmt->execute(array(
									   'userid' => $_SESSION['userid'],
									   'hirid' => $vars['hirid'],
									   'komment' => $vars['ujkomment']
								   ));
					if ($stmt->rowCount()) {
						$retData['uzenet'] = "Komment beküldve";
					} else {
						$retData['eredmeny'] = "ERROR";
						$retData['uzenet'] = "Sikertelen kommentbeküldés";
					}
				} catch (PDOException $e) {
					$retData['eredmeny'] = "ERROR";
					$retData['uzenet'] = "Hiba: " . $e->getMessage() . "<br>Hibás sor: " . $e->getLine();
				}
			}
		}
		return $retData;
	}
}
