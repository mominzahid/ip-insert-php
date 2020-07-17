<html lang="en-us">
<style>

    h2 {
        color: red;
        text-align: center;
        padding: 25px;
    }

    table {
        text-align: center;
        padding: 5px;
    }

    th {
        padding: 10px;
    }

    td {
        padding: 10px
    }

    .left-align, .right-align {
        width: 40%;
        display: inline-grid;
        text-align: center;
        margin-left:5%;
        vertical-align: top;
        height: 100%;
    }

    .right-align {
        width:40%;
        display: inline-grid;
        margin-left:5%;
        text-align: center;
        vertical-align: -webkit-baseline-middle;
    }
    div {
        border-width: 3px;
        border-color: darkred;
        border-style: dashed;
        border-radius: 15px;
    }
</style>

<body>

<?php

function is_ip($ip)
{
    if ($valid = filter_var($ip, FILTER_VALIDATE_IP) == false) {
        return false;
    }
    return true;
}

// Get connection data
require 'config.php';


$ips = $_POST['ips'];
$ips = preg_split("/\\r\n/", $ips);
$ips = array_filter($ips, "is_ip");
$sql = "INSERT INTO " . $table_name . "( " . $fields . " ) VALUES ";

foreach ($ips as $row) {
    $valid = filter_var($row, FILTER_VALIDATE_IP);
    if ($valid != false) {
        // Build SQL Query Here
        $sql .= "('" . "{$valid}" . "' , " . "true" . " , '" . $valid . "'),";
        // Performing SQL query
    }
}
$sql[strlen($sql) - 1] = ';';

if (sizeof($ips) > 0) {

    $dbconn = pg_connect($connection_string) or die('Could not connect: ' . pg_last_error()); // Localhost DB


    $result = pg_query($sql) or die('Query failed: ' . pg_last_error());

    echo "<div class='left-align'><table>";
    echo "<h3>The following IPs have been added</h3>";
    echo "<th>IP Address</th>";
    foreach($ips as $row) {
        echo "\t<tr>\n";
        echo "\t\t<td>$row</td>\n";

        echo "\t</tr>\n";
    }
    echo "</table>\n";
    echo "</div>";

    $sql = "select id, ip_address, (select count(ip_address) from " . $table_name . " where i.ip_address::inet >= trim(ip_address)::inet and trim(ip_address_end)::inet >= i.ip_address::inet) as count
    from " . $table_name . " i order by count desc limit 10;";

    $result = pg_query($sql) or die('Query failed: ' . pg_last_error());

    echo "<div class='right-align'><table>\n";
    echo "<h3>This table shows if any IPs have any duplicates</h3>";
    echo "<th>ID</th><th>IP Address</th><th>Count</th>";
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        echo "\t<tr>\n";
        foreach ($line as $col_value) {
            echo "\t\t<td>$col_value</td>\n";
        }
        echo "\t</tr>\n";
    }
    echo "</table></div>\n";

    // Free resultset
    pg_free_result($result);
    pg_close($dbconn);
} else {
    echo "<h2>No IPs were Added, most likely due to incorrect input!</h2>";
}


// Closing connection



?>
</body>
</html>
