<?php
/*
 * getOrder.php
 * 
 * Copyright 2013 giannis <giannis@giannis64>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Αναζήτηση παραγγελίας</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" type="text/css" href="mystyle.css">
	 <script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>

<body style="padding:40px">
	<div class="container">
		<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
		<a class="brand" href="#">Χωρίς μεσάζοντες ΕΠΑ.Λ. Ελευθερούπολης</a>
		<ul class="nav">
		<li><a href="index.html">Αρχική</a></li>
		<li class="active"><a href="getOrder.php">Αναζήτηση Παραγγελίας</a></li>
		</ul>
		</div>
		</div>
		<form method="post" action="" class="form-horizontal">
	    <fieldset>
			   <label>Δώσε τον κωδικό πελάτη</label> <input type="text" name="clientid">
				<INPUT TYPE="SUBMIT" class="btn btn-success btn-large" VALUE="Προβολή παραγγελιών">
	    </fieldset>
		</form>
	</div>
<?php
		$clientid = $_POST['clientid'];
		$mysqli = new mysqli("localhost", "xoris", "123456", "xoris");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		  $mysqli->query("SET NAMES utf8");
		
//		 $queryOrdersSelect = "SELECT cl.lastname, t1.id, t3.name FROM client as cl, orderPack AS t1 join (productOrder AS t2, product as t3) on (t1.idorder=t2.id AND t1.idproduct=t3.id)";
//		 $queryOrdersSelect = "SELECT cl.lastname, t1.id FROM client as cl, orderPack AS t1 inner join productOrder AS t2 on (t1.idorder=t2.id) inner join cl on (t2.clientid=cl.id) where t2.clientid='$clientid'";
		 $queryOrdersSelect = "SELECT client.lastname, t3.name, t4.type, t1.id, t1.cost, t1.packnum from orderPack AS t1 inner join (productOrder AS t2, product as t3, packtype as t4) on (t1.idorder=t2.id AND t1.idproduct=t3.id AND t1.idproduct=t4.idproduct AND t1.idPackType=t4.id) inner join client on (t2.clientid=client.id) where t2.clientid='$clientid'";


		 if ($result = $mysqli->query($queryOrdersSelect)) {
			echo '<table class="table"><tr><th>Κωδικός</th><th>Επώνυμο</th><th>Προϊόν</th><th>Αριθμός πακέτων</th><th>Ποσότητα/πακέτο</th><th>Τιμή</th></tr>';
			while ($row = $result->fetch_assoc()) {
				echo '<tr><td>'. $row["id"] . '</td><td>' . $row["lastname"] . '</td><td>' . $row["name"] . '</td><td>' . $row["packnum"] . '</td><td>' . $row["type"] . '</td><td>' . $row["cost"] .   '</td></tr>';
//				 printf ("%s \n", $row["lastname"]);
//				 printf ("προϊόν %s \n", $row["name"]);
//				 printf ("κωδικός πακέτου παραγγελίας %s \n", $row["id"]);
			}
			$result->free();
			echo '</table>';

		 }


		$mysqli->close();
?>
</body>

</html>
