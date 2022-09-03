<html>

<link rel="stylesheet" href="stylesheet.css">

<title> Capacity </title>

<div class="header"> 
	<h1> Venue Capacity </h1>
	<p> Here are the available venues and the capacities </p>
</div>


<?php

	$min = $_GET['minCapacity'];
	$max = $_GET['maxCapacity'];
	
	function alert_function($message) {

		echo "<script>alert('$message');</script>";
	}
	
	if((!is_numeric($min)) or (!is_numeric($max))) {
		alert_function("Capacity values must be numeric");
	} else if ($min > $max) {
		alert_function("Minimum capacity must be less than maximum capacity ");
	} else if ( ($min < 0) or ($max < 0)) {
		alert_function("You must enter a positive capacity value");
	}
	  
	$servername ="sci-mysql";
	$username = "coa123wuser";
	$password = "grt64dkh!@2FD";
	$dbname = "coa123wdb";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
?>

<div class = "content"> 
	<table>
		<thead>
			<tr>
				<th>Venue Name</th>
				<th>Venue Capacity</th>
				<th>Weekday Price</th>
				<th>Weekend Price</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$table_name = "venue";
			$sql = "SELECT name, capacity, weekend_price, weekday_price FROM `venue`";
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
				//output data of each row
				while ($row = mysqli_fetch_array($result)) {
					// if the capacity in that row falls within the user defined min and max, then display that row
					if (($row["capacity"] <= $max) and ($row["capacity"] >= $min)) {
						echo "<tr><td>".$row["name"]."</td><td>".$row["capacity"]."</td><td>".$row["weekend_price"]."</td><td>".$row["weekday_price"]."<br>"."</td></tr>";
					}
				};
			}

			mysqli_close($conn);
			?>
		</tbody>
	</table>
</div> 

</html>