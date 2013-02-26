<?php
/*
 * formSubmit.php
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
	<title>Καταχώρηση παραγγελίας</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
</head>

<body>
	<?php
		define("PATATESVALUEPERKILO",0.4);
		define("ALEVRIVALUEPERKILO",1);
			$mysqli = new mysqli("localhost", "xoris", "123456", "xoris");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

	
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$mobile = $_POST['mobile'];
		
		
		// products
		$patates =  $_POST['patates'];
		$alevri =   $_POST['alevri'];
		
		$clientid=0;
		$orderid=0;
		$orderDate= date('Y-m-d H:i:s',strtotime("now"));


		  $mysqli->query("SET NAMES utf8");
		  if (!$mysqli->query("INSERT INTO client (name, lastname, mobile) VALUES ('$firstname','$lastname','$mobile')")) {
				echo "Η εισαγωγή δεδομένων στον πίνακα απέτυχε (" . $mysqli->errno . ") " . $mysqli->error;
		  }
		 // τέλος καταχώρηση στοιχείων χρήστη στην βάση δεδομένων
		// δημιουργία παραγγελίας
		// λήψη κωδικού νέου χρήστη από την βάση δεδομένων
		 $queryClientSelect = "SELECT id FROM client where lastname='$lastname' AND mobile='$mobile'";
		 if ($result = $mysqli->query($queryClientSelect)) {
			while ($row = $result->fetch_assoc()) {
				 $clientid=$row["id"];
	//			 printf ("%s \n", $row["id"]);
			}
			$result->free();
		 }
		 echo $clientid;
			// καταχώρηση στον πίνακα παραγγελία
		 $queryOrderIns = "insert into productOrder (clientid,orderDate) values ('$clientid','$orderDate')";
		
		  if (!$mysqli->query($queryOrderIns)) {
				echo "Η εισαγωγή δεδομένων στον πίνακα απέτυχε (" . $mysqli->errno . ") " . $mysqli->error;
		  }
		 // λήψη κωδικού παραγγελίας
		 $queryOrderSelect = "SELECT id FROM productOrder where clientid='$clientid' AND orderDate='$orderDate'";
		 if ($result = $mysqli->query($queryOrderSelect)) {
			while ($row = $result->fetch_assoc()) {
				 $orderid=$row["id"];
			}
			$result->free();
		 }
		
		if ($patates>0) {
		  $patatesPack =  $_POST['patatesPack'];
		  if($patatesPack=='20') 
			$idPackType=1;
		  else if($patatesPack=='30')
			$idPackType=2;
			  
		  $patatesTotal = $patates*$patatesPack*PATATESVALUEPERKILO;
  		  echo $patatesTotal;
  		  $queryOrderPackIns = "insert into orderPack (idProduct, idOrder, idPackType, cost, packnum) values ('1','$orderid', '$idPackType', '$patatesTotal', '$patates')";
		  if (!$mysqli->query($queryOrderPackIns)) {
				echo "Η εισαγωγή δεδομένων στον πίνακα απέτυχε (" . $mysqli->errno . ") " . $mysqli->error;
		  }	 
		}

		if ($alevri>0) {
		  $alevriPack =  $_POST['alevriPack'];
		  if($alevriPack=='3') 
			$idPackType=3;
		  else if($alevriPack=='5')
			$idPackType=4;
			  
		  $alevriTotal = $alevri*$alevriPack*ALEVRIVALUEPERKILO;
  		  $queryOrderPackIns = "insert into orderPack (idProduct, idOrder, idPackType, cost, packnum) values ('2','$orderid', '$idPackType', '$alevriTotal', '$alevri')";
		  if (!$mysqli->query($queryOrderPackIns)) {
				echo "Η εισαγωγή δεδομένων στον πίνακα απέτυχε (" . $mysqli->errno . ") " . $mysqli->error;
		  }	 
		}
		
		echo "Η παραγγελία σας καταχωρήθηκε";
		$mysqli->close();
	?> 
</body>

</html>
