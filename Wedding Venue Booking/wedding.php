<html>
<!--ALL IMAGES USED ARE REFERENCED -->
<head>

	<title> Wedding </title>
	<link rel="stylesheet" href="weddingstylesheet.css">
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- Bootstrap Bundle with Popper -->	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	
	<script>
		function open_page(pageName, element) {
			var i, tabcontent, tablinks;
			// hide all tabs 
			tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
				tabcontent[i].style.display = "none";
			}
			//set tab passed in argument to active
			document.getElementById(pageName).style.display = "block";
		}
		
		function hide_date2() {
			var date2_input = document.getElementById("date2");
			date2_input.type = "hidden";
			var date2_label = document.getElementById("date_label2");
			date2_label.style.display = "none";
			var date1_label = document.getElementById("date_label");
			date1_label.innerHTML = "Select the date you want to have the wedding";
		}
		
		function show_date2() {
			var date2_input = document.getElementById("date2");
			date2_input.type = "date";
			var date2_label = document.getElementById("date_label2");
			date2_label.style.display = "inline-block";
			var date1_label = document.getElementById("date_label");
			date1_label.innerHTML = "Start date: ";
		}
		
		function show_booking_results() {
			const xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
				console.log(this.responseText);
				document.getElementById("bookingtab").innerHTML = "<form action='wedding.php'><input type='submit' class = 'button new_booking' value='New booking' /></form>"+this.responseText;
			}
			var date = String(document.getElementById("date").value);
			var date2 = String(document.getElementById("date2").value);
			var capacity = String(document.getElementById("capacity").value);
			var catering_grade = String(document.getElementById("catering_grade").value);
			var parameters = "date="+date+"&date2="+date2+"&capacity="+capacity+"&catering_grade="+catering_grade;
			console.log(parameters);
			xhttp.open("GET", "process_info.php?"+parameters, true);
			xhttp.send();
			return false;
		}

		
	</script>
	
	

</head>

<!-- Create connection -->
<?php
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

<body>
	<div style ="background: #D8BFD8;">
		<div class = "container header">
			<br>
			<h1> MAJKL Events </h1>
			<p> View our venues and availabilities </p>
		</div>
	</div>
	
	<div>
		<button class="tablink" onclick="open_page('Venues', this)">Venues</button>
		<button class="tablink" id="defaultOpen" onclick="open_page('bookingtab', this)">Make a booking</button>
		<button class="tablink" onclick="open_page('About', this)">About</button>
	</div>

	<div class="container" style="display: flex;">
		
		<div id="Venues" class="flex-container tabcontent">
			<?php
			$table_name = "venue";
			$sql = "SELECT venue_id, name, capacity, weekend_price, weekday_price, licensed FROM `venue`";
			$result = mysqli_query($conn, $sql);
			
			function return_licensing($licensed_binary){
				if($licensed_binary == "1") {
					return "This venue is licensed";
				} elseif ($licensed_binary == "0"){
					return "This venue is not licensed";
				} else {
					// this is for testing
					return "An Error has occured with converting licensing from binary to string";
				}
			}
			
			function return_catering_grades($venue_id, $conn) {
				$get_catering = "SELECT venue_id, grade FROM `catering`";
				$catering_result = mysqli_query($conn, $get_catering);
				
				$grades = "";
				while ($catering_row = mysqli_fetch_array($catering_result)) {
					if ($catering_row["venue_id"] == $venue_id) {
						$grades = $grades."Level ".$catering_row["grade"].", ";
					}
				}
				return $grades;
			}
			//googled all the venues and chose images that looked like the name, then added to this array in order
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

			if (mysqli_num_rows($result) > 0) {
				//output data of each row
				while ($row = mysqli_fetch_array($result)) {
					$licensing_string = return_licensing($row["name"]);
					echo 	"<div class='venue_card'>
							  <img src='".return_venue_url($row["name"], $venue_images)."' class = 'venue_images' alt='Venue' style='width:100%'>
							  <div class='container'>
								<h4><b>".$row["name"]."</b></h4>
								<p>".return_licensing($row["licensed"])."</p>
								<p>The following grades of catering are available:<br>".return_catering_grades($row["venue_id"], $conn)."</p>
							  </div>
							</div>  ";
				};
			}

			mysqli_close($conn);
			?>
		</div>
		
		<div id ="bookingtab"class = "tabcontent">
			<p>Would you like to check availability for one day or a range of dates?</p>
			<button class="button" onclick="hide_date2()">Single date</button>	<button class="button" onclick="show_date2()"> Multiple dates</button>
			<form onsubmit="return show_booking_results()" id="form">
				<label for="date" id="date_label" >Select the date you want to have the wedding</label>
				<!-- We use php to prevent user from selecting date in the past -->
				<input required name="date" type="date" id="date" min="<?php echo date("Y-m-d"); ?>" onchange="document.getElementById('date2').min=this.value; document.getElementById('date2').value=this.value;">
				<label for="date" id="date_label2" style="display:none;">End date:</label>
				<input required name="date2" type="hidden" id="date2"><br><br>
				<label for="capacity">How many guests are you expecting? </label>
				<input required name="capacity" id="capacity" type="number" min="2"><br><br>
				<label for="catering_grade">What grade of catering would you like? </label>
				<select name="catering_grade" id="catering_grade">
				  <option value="1">Grade 1</option>
				  <option value="2">Grade 2</option>
				  <option value="3">Grade 3</option>
				  <option value="4">Grade 4</option>
				  <option value="5">Grade 5</option>
				</select><br><br>
				<input type="submit">

			</form>
		</div> 
		
		<div id="About" class="tabcontent">
			<h2>About us</h2>
				<p>Whatever your wedding venue needs, start your search here for the perfect meeting, event or celebration venue. MAJKL can accommodate a wide variety of events, so find the perfect space for your next event. Today, with 93 different venues all available for booking online, no matter what your event will be - Majkl will be able to deliver. You don't have to stick to the same boring place at every event. Our venue finding tool makes it easy to find wedding venues - simply enter your number of guests, budget, and date. We'll show you all the venues that fit your requirements.</p>
			<h2> Contact us:</h2>
				<p>Email: majklvenues@fakemail.com</p>
				<p>Telephone: 01505 690 233</p>
				<p>Address: MAJKL HQ 1375 E Buena Vista Dr Orlando, FL Resorts.</p>
			<img src="majkl.jpg" alt="majkl_logo" id="majkl_logo">
		</div>
	</div>
	
</body>

<!-- Want this script to only run after the rest of page is loaded-->
<script>
	document.getElementById("defaultOpen").click();
</script>
</html>

<!-- USE 2D ARRAY TO RECORD VALID VENUE ID AND THE DAY IT WAS -->