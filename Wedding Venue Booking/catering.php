<html>

<link rel="stylesheet" href="stylesheet.css">

<title> Catering Costs </title>

<div class="header">
	<h1>Catering</h1>
	
</div>

<div class="Content">
	
	<p> Here are the pricing options for your party size: </p>
	
	<table>
		<thead>
			<tr> 
				<th> Party Size</th>
				<th>C1</th>
				<th>C2</th>
				<th>C3</th>
				<th>C4</th>
				<th>C5</th>
			</tr>
		</thead>
		<tbody>
			<!-- For loop that multiplies the party size by all cost pricings ranging from min to max-->
			<?php 
			for ($i = $_GET["min"]; $i <= $_GET["max"]; $i+=5) {
				echo "<tr><td>".$i."</td><td>".$i*$_GET["c1"]."</td><td>".$i*$_GET["c2"]."</td><td>".$i*$_GET["c3"]."</td><td>".$i*$_GET["c4"]."</td><td>".$i*$_GET["c5"]."</td></tr>";
			}
			
			?>
		</tbody>
	</table>
</div>

</html>