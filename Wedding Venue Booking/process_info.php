<?php
/*ALL IMAGES USED ARE REFERENCED*/
		$date = $_GET["date"];
		$date2 = $date;
		$date2 = $_GET["date2"];
		$capacity = $_GET["capacity"];
		$catering_grade = $_GET["catering_grade"];
		
		$license_text = "";
		
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
		
		$dateStart = $date;
		$dateEnd = $date2;
		$current_date = $dateStart;

		/* all venue ids are instantiated as an array within our main array $valid_venue_ids
		 it's an associative array, so we can use the venue id as a key to access the array containing the dates where its unbooked 
		 if the venue is available on the current date the sql statement is ran on, then the current date is appended to the array for that venue id*/
		$valid_venue_ids = array(
			"1" => array(),
			"2" => array(),
			"3" => array(),
			"4" => array(),
			"5" => array(),
			"6" => array(),
			"7" => array(),
			"8" => array(),
			"9" => array(),
			"10" => array(),
		);
		// Check for unbooked venues every day between start date and end date, each time you find an availability add to array
		$i = 0;
		while(strtotime($current_date) <= strtotime($dateEnd))
		{
			// get venue id of all the venues that are booked on a given date
			$sql = "SELECT venue_id FROM venue_booking WHERE venue_id NOT IN (SELECT venue_id FROM `venue_booking` WHERE booking_date = '".$current_date."') GROUP BY venue_id";
			$unbooked_venues = mysqli_query($conn, $sql);			
			
			// Add all the venues that are available/unbooked to an array
			
			while ($row = mysqli_fetch_array($unbooked_venues)) {
				array_push($valid_venue_ids[$row[0]], $current_date);
				$i++;
			}
		  $current_date= date("Y-m-d",strtotime("+1 day",strtotime($current_date)));
		}
		// print_r($valid_venue_ids);
		
		// Return the catering grades available for a particular venue
		function return_catering_grades($venue_id, $conn) {
			// Get array of grades available 
			$grades = array();
			$get_catering_grades = "SELECT grade FROM `catering` WHERE venue_id = '".$venue_id."'";
			$catering_result = mysqli_query($conn, $get_catering_grades);
			while ($row = mysqli_fetch_array($catering_result)) {
				array_push($grades, $row[0]);
			}
			return $grades;
		}			
		
		// Check that a venue provides a given grade of catering
		function provides_catering_grade($catering_grade, $venue_id, $conn) {
			$grades = return_catering_grades($venue_id, $conn);
			foreach ($grades as $key => $value) {
				if ($value == $catering_grade) {
					return true;
				}
			}
			return false;
		}
		
		function return_catering_costs($venue_id, $capacity, $catering_grade, $conn) {
			// Get grades available 
			$grades = return_catering_grades($venue_id, $conn);
			
			// return cost per person for a given venue and catering grade
			// Unique identifier/primary key for cost so should only return one value 
			$costs = array();
			$get_catering_cost = "SELECT cost FROM `catering` WHERE venue_id = '".$venue_id."' and grade = '".$catering_grade."';";
			$cost_result = mysqli_query($conn, $get_catering_cost);
			while ($row = mysqli_fetch_array($cost_result)) {
				array_push($costs, $row[0]);
			}
			return $capacity * $costs[0];
		}
		
		function return_cost_per_person($venue_id, $capacity, $catering_grade, $conn) {
			// return cost per person for a given venue and catering grade
			// Unique identifier/primary key for cost so should only return one value 
			$costs = array();
			$get_catering_cost = "SELECT cost FROM `catering` WHERE venue_id = '".$venue_id."' and grade = '".$catering_grade."';";
			$cost_result = mysqli_query($conn, $get_catering_cost);
			while ($row = mysqli_fetch_array($cost_result)) {
				array_push($costs, $row[0]);
			}
			return  $costs[0];
		}		
		
		// Will be appended to a <p> tag to show whethera venue is licensed or not
		function return_licensing($licensing) {
			if ($licensing == 0) {
				return " not";
			} elseif ($licensing == 1) {
				return "";
			}
		}
		
		// will return monthly bookings, only using the first date (if user enters date range that crosses two months)
		function return_total_bookings($venue_id, $conn) {
			$total_bookings = array ();
			
			$get_total_bookings = "SELECT COUNT(venue_id) FROM `venue_booking` WHERE venue_id = '".$venue_id."'";
			$total_bookings_result = mysqli_query($conn, $get_total_bookings);
			while ($row = mysqli_fetch_array($total_bookings_result)) {
				array_push($total_bookings, $row[0]);
			}
			
			return $total_bookings[0];
		}

		function return_days_available($venue_id, $conn, $valid_venue_ids) {
			$output = "";
			foreach($valid_venue_ids[$venue_id] as $key => $value){
				$timestamp = strtotime($value);
				$day = date('D', $timestamp);

				$output = $output.$day.", ";
			}

			return $output;
		}

		$venue_images = array(
			// The Plaza New York, theplazany.com, https://www.theplazany.com/wp-content/uploads/2016/02/Events_Venues_TheGrandBallroom.jpg
			"Central Plaza" => "centralplaza.jpg",
			// Sino Hotels, sino-hotels.com, royal pacific hotel, https://sino-hotels-prod.azureedge.net/cmsstorage/sinohotels/media/rph/wedding-and-celebrations/royal-pacific-wedding-wedding-mobile.jpg?ext=.jpg
			"Pacific Towers Hotel" => "pacifictowershotel.jpg",
			// Hitched, hitched.co.uk,  The Venue At Sandy Cove, https://cdn0.hitched.co.uk/vendor/3991/3_2/960/jpg/the-venue-at-20190301051350509.jpeg
			"Sky Center Complex" => "skycentercomplex.jpg",
			// Hitched, hitched.co.uk, Tunnels Beaches, https://cdn0.hitched.co.uk/vendor/2895/3_2/960/jpg/tunnels-beac-20180604014719109.jpeg
			"Sea View Tavern" => "seaviewtavern.jpg",
			// Real Weddings, realweddings.co.uk, The Walled Garden at Castle Ashby, https://www.realweddings.co.uk/media/showcases/the-walled-garden-at-castle-ashby/b2dd0eae8df94fc7b1c68e60a19a76e0.jpg
			"Ashby Castle" => "ashbycastle.jpg",
			// TripAdvisor, tripadvisor.co.uk, Stonehouse Court Hotel, https://media-cdn.tripadvisor.com/media/photo-s/02/37/e9/b6/stonehouse-court.jpg
			"Fawlty Towers" => "fawltytowers.jpg",
			// Exclusive Italy Weddings, exclusiveitalyweddings.com, Villa Centinale, https://www.exclusiveitalyweddings.com/DB_files/venues_thumb/tuscany-villa-cetinale-preview.jpg
			"Hilltop Mansion" => "hilltopmansion.jpg",
			// ukbride, ukbride.com, Bothwell Bridge Hotel, https://www.ukbride.co.uk/images/%24scICyWYFbHB%24TdqCo%24%3AcWunQ/normal/1200x800/unknown.jpeg
			"Haslegrave Hotel" => "haslegravehotel.jpg",
			// New Forest Wedding, newforestwedding.co.uk, Kimbridge Barn, https://newforestwedding.co.uk/wp-content/uploads/2020/02/kimbridge1-1024x685.jpg
			"Forest Inn" => "forestinn.jpg",
			// Hitched, hitched.co.uk, Owlpen Manor, https://cdn0.hitched.co.uk/vendor/4143/3_2/960/jpg/pole-barn-evening-4x3-img-1797-low_4_194143-161115319473334.jpeg
			"Southwestern Estate" => "southwesternestate.jpg",
		);
		
		function return_venue_url($name, $venue_images) {
			foreach ($venue_images as $key => $value) {
				if ($key == $name) {
					return $value;
				}
			}
		}
	
		//output all Venue details for all venues that aren't booked that day
		$get_all_venues = "SELECT venue_id, name, capacity, weekend_price, weekday_price, licensed FROM `venue`";
		$all_venues = mysqli_query($conn, $get_all_venues);

		//output data of each row
		$output = "";
		while ($row = mysqli_fetch_array($all_venues)) {
			// Check venue is unbooked, Check that venue provides large enough capacity, Check that venue offers required catering grade
			if ((count($valid_venue_ids[$row["venue_id"]]) > 0) and ($capacity <= $row["capacity"]) and (provides_catering_grade($catering_grade, $row["venue_id"], $conn))) {
				$total_costwkd = $row["weekday_price"]+return_catering_costs($row["venue_id"], $capacity, $catering_grade, $conn);
				$total_costwknd = $row["weekend_price"]+return_catering_costs($row["venue_id"], $capacity, $catering_grade, $conn);
				$output = $output."<div class='venue_card'>
						<img src='".return_venue_url($row["name"], $venue_images)."' class = 'venue_images' alt='Venue' style='width:100%'>
						<div class='container'>
							<h4><b>".$row["name"]."</b></h4>
							<p>This venue is".return_licensing($row["licensed"])." licensed<br>
							- Maximum capacity: ".$row["capacity"]."<br>
							- Weekday price: £".$row["weekday_price"]."<br>
							- Weekend Price: £".$row["weekend_price"]."<br>
							- Catering per person: £".return_cost_per_person($row["venue_id"], $capacity, $catering_grade, $conn)."<br>
							- Total cost (WEEKDAY) £".$total_costwkd."<br>
							- Total Cost (WEEKEND): £".$total_costwknd."<br>
							- Total bookings: ".return_total_bookings($row["venue_id"], $conn)."<br>
							- Days available: ".return_days_available($row["venue_id"], $conn, $valid_venue_ids)."</p>
						</div>
					</div>  ";					
			}
		};

		mysqli_close($conn);
		echo $output;
		?>
