<?php

	//Prevent wildcard processing from user input
	$wildcard_characters = array("%", "_");
	$input_text = str_replace($wildcard_characters, mt_rand(), trim($_GET["q"]));
	$input_text = ucwords(strtolower($input_text));

	try {

		$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
		$filter  = ['title' => ['$regex' => $input_text]];
		$options = ['limit' => 10];
		$query = new MongoDB\Driver\Query($filter, $options);

		$rows = $mng->executeQuery("imdb.movies", $query);

		echo "<p>&nbsp;</p>";
		echo "<table>";
		foreach ($rows as $row) {
			$movie = $row->title;
			$year = $row->year;
			$info = $row->info;
			$directors = $info->{"directors"};
			$directors = implode(", ", $directors);
			$rating = $info->{"rating"};
			$genres = $info->{"genres"};
			$genres = implode(", ", $genres);
			$image_url = $info->{"image_url"};
			$plot = $info->{"plot"};
			$rank = $info->{"rank"};
			$running_time_min = round($info->{"running_time_secs"} / 60);
			$actors = $info->{"actors"};
			$actors = implode(", ", $actors);
			echo "<tr>";
			echo "<td>";
			echo "<table>";
			echo "<tr>";
			echo "<td><div>";
			echo "<img src='$image_url' width='225' height='333'>";
			echo "</div></td>";
			echo "<td><div>";
			echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>";
			echo "</div></td>";
			echo "<td><div>";
			echo "<table>";
			echo "<tbody>";
			echo "<tr>";
			echo "<td><h1>$movie</h1></td>";
			echo "<td><h2>($year)</h2></td>";
			echo "<td>&nbsp;</td>";
			echo "</tr>";
			echo "</tbody>";
			echo "</table>";
			echo "<p>$plot</p>";
			echo "<p><b>Rating:</b> $rating/10</p>";
			echo "<p><b>Runtime:</b> $running_time_min min</p>";
			echo "<p><b>Genre(s):</b> $genres</p>";
			echo "<p><strong>Director(s):</strong> $directors</p>";
			echo "<p><strong>Star(s):</strong> $actors</p>";
			echo "</div></td>";
			echo "</tr>";
			echo "</table>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} catch (MongoDB\Driver\Exception\Exception $e) {

		$filename = basename(__FILE__);

		echo "The $filename script has experienced an error.\n";
		echo "It failed with the following exception:\n";

		echo "Exception:", $e->getMessage(), "\n";
		echo "In file:", $e->getFile(), "\n";
		echo "On line:", $e->getLine(), "\n";
	}


?>
