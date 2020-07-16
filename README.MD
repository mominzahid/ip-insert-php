# Insert IP Utility

This is a handy utility to insert IP Addresses into the database. If query succeeds, it will show the IP Addresses inserted and IPs with their counts to identify counts, if any. 

The script assumes 3 fields in the table: ip_start, boolean, ip_end. May have to be refactored for a different table structure.

The utility requires a config file called config.php with the following variables defined:

### _****Config.php****_

`$connection_string = "host={hostname} dbname={db_name} " . "user={db_user} password={localuser}";`

`$table_name = "{insert table name here}";`

`$fields = "{field1, field2, field3, .....}";`
