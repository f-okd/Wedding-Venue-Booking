<html>

<?php

	$min = $_GET['month_to_check'];
	$max = $min->add;
	
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
		<tr>
			<th>Venue Name</th>
			<th>Bookings this month</th>
		</tr>
		
		<?php
		$table_name = "venue_booking";
		$sql = "SELECT venue.name, COUNT(venue_booking.booking_date) FROM venue INNER JOIN venue_booking ON venue.venue_id = venue_booking.venue_id WHERE venue_booking.booking_date >" $min "and venue_booking.booking_date GROUP BY venue.venue_id";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			//output data of each row
			while ($row = mysqli_fetch_array($result)) {
				echo "<tr><td>".$row[0]."</td><td>".$row[1]"<br>"."</td></tr>";
			};
		}
		mysqli_close($conn);
		?>
	</table>
	
</html>
