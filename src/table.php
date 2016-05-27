<html>
<head></head>
<body>
<?php 
$table = $_GET['name'];
?>

<a href="index.php">Back</a><br><br>
<h1><?php echo $table; ?></h1>
<br/>
<br/>
<?php
  $dbconn = pg_connect("host=postgres dbname=mvlabs user=mvlabs password=mvlabs") or die('Could not connect: ' . pg_last_error());

  $query = "select * from $table;";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());

  $num_tables = pg_num_rows($result);

  if ($num_tables > 0) {

    echo "<table>\n";
    $line = pg_fetch_array($result, null, PGSQL_ASSOC);

    $header = "<tr>";
    $row = "<tr>";
    foreach ($line as $col_name => $col_value) {
      $header .= "<th>$col_name</th>";
      $row .= "<td>$col_value</td>";
    }
    $header .= "</tr>";
    $row .= "</tr>";
    echo $header . $row;
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      echo "\t<tr>\n";
      foreach ($line as $col_name => $col_value) {
          echo "\t\t<td>$col_value</td>\n";
      }
      echo "\t</tr>\n";
    }
    echo "</table>\n";

  } else {
    echo "No data found in table: '$table'";
  }

  pg_free_result($result);

  pg_close($dbconn);
?>
</body>
</html>
