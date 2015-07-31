<?php
require_once("database.php");
?>

<html>
<head>
	<title>List</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>	
	<div id="container">
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">

			<div id="">
				<h3>Counter</h3>
				<p><a href="create.php">Create New Counter</a></p>

				<?php
				echo "<table border='1'>
				<tr>
				<th>ID</th>
				<th>Counter URL</th>
				<th>Destination URL</th>
				<th>Total Click</th>
				<th>Unique Click</th>
				<th>Edit</th>			
				</tr>";

				//PAGINATION
				$limit = 10;
				$page = 0;
				if(isset($_GET['page'])) {
					$page = $_GET['page'] - 1;
					$page = ($page * $limit);;
				} 
				$total_query = mysqli_query($con, "SELECT COUNT(DISTINCT tbl_counter.counter_id) AS total_rows FROM tbl_counter LEFT JOIN tbl_click ON tbl_counter.counter_id = tbl_click.counter_id WHERE tbl_counter.enable = 0");
				$total_array = mysqli_fetch_array($total_query);
				$total_rows = $total_array['total_rows'];
				$total_page = $total_rows / $limit;
				$total_page = floor($total_page) + 1;

				//ORIGINAL SQL
				$result = mysqli_query($con, "SELECT tbl_counter.counter_id, tbl_counter.encoded_counter_id, tbl_counter.destination_url, sum(tbl_click.click_count) AS total_click, COUNT(distinct case when tbl_click.cookie_id != 0 then tbl_click.cookie_id end) AS unique_click FROM tbl_counter LEFT JOIN tbl_click ON tbl_counter.counter_id = tbl_click.counter_id WHERE tbl_counter.enable = 0 GROUP BY tbl_counter.counter_id ORDER BY tbl_counter.counter_id ASC LIMIT 10 OFFSET $page");
				//NEW SQL
				//$result = mysqli_query($con, "SELECT tbl_counter.counter_id, tbl_counter.encoded_counter_id, tbl_counter.destination_url, sum(tbl_click.click_count) AS total_click, count(distinct tbl_click.cookie_id) AS unique_click FROM tbl_counter LEFT JOIN tbl_click ON tbl_counter.counter_id = tbl_click.counter_id WHERE tbl_counter.enable = 0 AND (tbl_click.cookie_id > 0 OR tbl_click.cookie_id IS NULL) GROUP BY tbl_counter.counter_id ORDER BY tbl_counter.counter_id ASC LIMIT 10 OFFSET $page");
				while($row = mysqli_fetch_array($result)) {
					
					echo "<tr>";
					echo "<td>" . $row['counter_id'] . "</td>";
					$counter_id = $row['counter_id'];
					$counter_url = dirname($_SERVER["SCRIPT_NAME"]) ."/c.php?id=" . $row['encoded_counter_id'];
					
					echo "<td> <a href = ' $counter_url'>".$_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]) . "/c.php?id=" . $row['encoded_counter_id']." </a>";
					$destination_url = $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]) . "/c.php?id=" . $row['encoded_counter_id'];
					
					echo "<td>" . $row['destination_url'] . "</td>";

					if(!$row['total_click']){
						echo "<td>0</td>";
						$total_click = 0;
					}else{
						echo "<td>" . $row['total_click'] . "</td>";
						$total_click = $row['total_click'];
					}

					echo "<td>" . $row['unique_click'] . "</td>";
					
					echo '<td>'. '<a href="edit.php?counter_id=' .$counter_id. ' ">Edit</a></td>';			
					echo "</tr>";
				}
				echo "</table>";		

				mysqli_close($con);

				//PAGINATION OUTPUT
				echo '<div id="paging">';
				for($i = 1; $i <= $total_page; $i++){
					echo '<span id="page">';
					echo '<a href="http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]) . '/list.php?page=' . $i . '">' . $i . '</a>';
					echo '<span id="page">';
				}
				echo "</div>";
				?>

			</div>
		</form>
	</div>
</body>
</html>