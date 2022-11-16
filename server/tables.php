<?php
//Konstans változól létrehozása, amelyek az adatbázishoz való csazlakozáshoz használatosak.
const HOST = 'localhost';
const DATABASE = 'windmills';
const USER = 'windmills';
const PASSWORD = 'passw0rd';
//Tables osztály létrehozása.
class Tables {

	//Az adatbázisból a helyszínek lekérdezésére szolgáló függvény.
	/**
	 * @return Locations
	 */
	public function getlocations() {
		//eredmeny tömb, ami tartalmaz egy hibakódot, ami alapesetben 0, amikor nincs hiba. Egy üzenetet, ami üres string ha nincs hiba és egy tömböt, amiben a kiolvasott adatok találhatóak.
		$eredmeny = array("errorcode" => 0,
			"msg" => "",
			"locations" => array());

		try {
			//Csatlakozás az adatbázishoz és az adatok lekérdezése, majd egy tömbbe helyezése, amit a függvény visszaad visszatérési értékként.
			$connection = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE, USER, PASSWORD,
								  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

			$connection->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

			$sql = "select id, nev, megyeid from helyszin order by id";
			$sth = $connection->prepare($sql);
			$sth->execute(array());
			$eredmeny['locations'] = $sth->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$eredmeny["errorcode"] = 1;
			$eredmeny["msg"] = $e->getMessage();
		}

		return $eredmeny;
	}

	//Az adatbázisból a megyék lekérdezésére szolgáló függvény. Működése azonos a helyszínek lekérdezésére szolgálóval.
	/**
	 * @return Counties
	 */
	public function getcounties() {

		$eredmeny = array("errorcode" => 0,
			"msg" => "",
			"counties" => array());

		try {
			$connection = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE, USER, PASSWORD,
								  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

			$connection->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

			$sql = "select id, nev, regio from megye order by id";
			$sth = $connection->prepare($sql);
			$sth->execute(array());
			$eredmeny['counties'] = $sth->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$eredmeny["errorcode"] = 1;
			$eredmeny["msg"] = $e->getMessage();
		}

		return $eredmeny;
	}

	//Az adatbázisból az erőművek lekérdezésére szolgáló függvény. Működése azonos a helyszínek lekérdezésére szolgálóval.
	/**
	 * @return Towers
	 */
	public function gettowers() {

		$eredmeny = array("errorcode" => 0,
			"msg" => "",
			"towers" => array());

		try {
			$connection = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE, USER, PASSWORD,
								  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

			$connection->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

			$sql = "select id, darab, teljesitmeny, kezdev, helyszinid from torony order by id";
			$sth = $connection->prepare($sql);
			$sth->execute(array());
			$eredmeny['towers'] = $sth->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$eredmeny["errorcode"] = 1;
			$eredmeny["msg"] = $e->getMessage();
		}

		return $eredmeny;
	}

}


//osztályok, melyek szükségesek a WSDL fájl létrehozásához.
//Location osztály, amely a helyszín adataihoz tartalmaz nyilvános változókat.
class Location {
	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $nev;

	/**
	 * @var int
	 */
	public $megyeid;

}
//Locations osztály, egy hibakódhoz, egy hibaüzenethez és egy Location osztályhoz tartalmasz nyilvános változókat.
class Locations {
	/**
	 * @var integer
	 */
	public $errorcode;

	/**
	 * @var string
	 */
	public $msg;

	/**
	 * @var Location[]
	 */
	public $locations;
}

//Az alábbi osztályok a fentihez hasonló szerepet töltenek be.
class County {
	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $nev;

	/**
	 * @var int
	 */
	public $regio;

}

class Counties {
	/**
	 * @var integer
	 */
	public $errorcode;

	/**
	 * @var string
	 */
	public $msg;

	/**
	 * @var County[]
	 */
	public $counties;
}


class Tower {
	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var int
	 */
	public $darab;

	/**
	 * @var int
	 */
	public $teljesitmeny;

	/**
	 * @var int
	 */
	public $kezdev;

	/**
	 * @var int
	 */
	public $helyszinid;

}

class Towers {
	/**
	 * @var integer
	 */
	public $errorcode;

	/**
	 * @var string
	 */
	public $msg;

	/**
	 * @var Tower[]
	 */
	public $towers;
}
