<html>
<head>
<link rel="stylesheet" href="stylesheet.css">
</head>

<body>
	<div class="header"> 
		<h1> Monthly bookings </h1>
		<p> View the monthly bookings for each venue</p>
	</div>

	<?php
		$month = $_GET['month'];
		
		function alert_function($message) {
			echo "<script>alert('$message');</script>";
		}

		if ( ($month < 1) or ($month > 12)) {
			alert_function("Month input must lie between 1 and 12");
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
	<div class="content">
	<table>
		<thead>
			<tr>
				<th>Venue</th>
				<th>Monthly Bookings</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$table_name = "venue";
			$sql = "SELECT venue.name, COUNT(venue_booking.booking_date) FROM venue INNER JOIN venue_booking ON venue.venue_id = venue_booking.venue_id WHERE MONTH(venue_booking.booking_date) =".$month."  GROUP BY venue.venue_id ORDER BY COUNT(venue_booking.booking_date) DESC";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				//output data of each row
				while ($row = mysqli_fetch_array($result)) {
					echo "<tr><td>".$row[0]."</td><td>".$row[1]."<br>"."</td></tr>";
				};
			}
			mysqli_close($conn);
	?>
		</tbody>

	</div>
</body>
</html>