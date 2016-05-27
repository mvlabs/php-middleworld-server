<html>
<head></head>
<body>
<a href="info.php">phpinfo</a>

<br/>
<br/>
<?php
  $dbconn = pg_connect("host=postgres dbname=mvlabs user=mvlabs password=mvlabs") or die('Could not connect: ' . pg_last_error());

  $query = 'select relname from pg_stat_user_tables order by relname;';
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());

  $num_tables = pg_num_rows($result);

  if ($num_tables > 0) {

    echo "<table>\n";
    echo "<tr><th>Table name</th><th><!-- --></th></tr>";
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      echo "\t<tr>\n";
      foreach ($line as $col_value) {
          echo "\t\t<td>$col_value</td>\n";
          echo "<td><a href=\"table.php?name=$col_value\">Visualizza</a></td>";
      }
      echo "\t</tr>\n";
    }
    echo "</table>\n";

  } else {
    echo "No tables found in database: 'mvlabs'";
  }

  pg_free_result($result);

  pg_close($dbconn);
?>
</body>
</html>
