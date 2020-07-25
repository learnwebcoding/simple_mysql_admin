<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: controllers/databases.php.
 * Purpose: Databases page controller. Require Databases page class (classes/Databases.class.php) and instantiate Databases page $db object. Require Databases page view (views/databases-html.php) and return Databases page HTML content to Index page (front) controller (index.php).
 * Used in: No other file.
 * Last reviewed/updated: 11 Mar 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) createDatabaseForm, and 2.) dropDatabaseForm. */

/* ---------- INSTANTIATE OBJECT ---------- */

// Require Databases page class and instantiate Databases page object passing Index page (front) controller (index.php) $index->dbh property (an object that represents a connection between PHP and MySQL) as argument.
require_once "classes/Databases.class.php";
$db = new Databases($index->dbh);

/* ---------- ASSIGNMENTS ---------- */

$db->selectCollationOptionsArray = array("latin1_swedish_ci", "utf8_bin", "utf8_general_ci", "utf8_unicode_ci", "utf8mb4_general_ci");

/* ---------- DROP DATABASES ---------- */

// NOTE:
// It is possible to move foreach from model to controller, and to call model to drop individual databases, not to drop array of database. Perhaps do this in future. See User Accounts page drop user accounts for same.

// Determine if drop database form was last form submitted and drop database checkboxes were checked. If drop database form was last form submitted and drop database checkboxes were checked, expression evaluates to boolean true.
// NOTE: The $_POST superglobal dropDatabasesArray variable value is an array. The $_POST superglobal dropDatabasesArray variable is set if drop database checkboxes were checked, and is not set if drop database checkboxes were not checked. Therefore, to determine if drop database form was last form submitted and drop database checkboxes were not checked, the drop database form includes a hidden input element whose name attribute value is set as a $_POST superglobal variable.
if (isset($_POST["dropDatabasesArray"])){
 // Drop database form was last form submitted and drop database checkboxes were checked. Get names of databases to drop and set on $variable.
 // NOTE: If drop multiple databases, print_r($dropDatabasesArray) is: Array ( [0] => db1 [1] => db2 [2] => db3 ).
 $dropDatabasesArray = $_POST["dropDatabasesArray"];
 // Call drop databases method to instruct MySQL to drop databases and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
 $sth = $db->dropDatabases($dropDatabasesArray);
 // Convert drop databases array into string and set on $variable.
 // NOTE: For drop databases status report.
 $dropDatabasesString = "'" . implode("' and '", $dropDatabasesArray) . "'";
 // Call html entities method to reduce XSS attack surface and set return value on $variable.
 // NOTE:
 // Untrusted data output to HTML is vulnerable to XSS attack. $dropDatabasesString is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
 // Create database natively does not allow &<>"'\ characters in database name. Therefore, although, in theory, running html entities method here is not required, it is run here as defense in depth measure.
 $dropDatabasesString = $index->htmlEntities($dropDatabasesString);
 // Determine if drop databases (plural, not singular) successful. If successful, expression evaluates to boolean false.
 if ($db->isDropDatabasesSuccessful){
  // Drop databases (plural, not singular) successful. Set string on $_SESSION["variable"], $index->property, and $db->property to report drop database names successful.
  $_SESSION["lastStatus"] = $index->body_main_lastStatus = $db->dropDatabasesStatus = "<span class='good'>Good</span>. Database $dropDatabasesString successfully dropped.";
 } else {
  // Drop databases (plural, not singular) unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
  // NOTE:
  // Returns an array of extended error information for last operation on statement handle.
  // If PDOStatement::execute() method no error, print_r($dropDatabaseErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
  // If PDOStatement::execute() method error (sql syntax error), print_r($dropDatabaseErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZDROP DATABASE db2' at line 1 ).
  $dropDatabaseErrorInfo = $sth->errorInfo();
  // Set string on $_SESSION["variable"], $index->property, and $db->property to report drop database names unsuccessful with PDOStatement::errorInfo error code and error info.
  // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $database, not $dropDatabasesString, to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if drop databases unsuccessful, $dropDatabaseErrorInfo[2] is vulnerable to XSS.
  $_SESSION["lastStatus"] = $index->body_main_lastStatus = $db->dropDatabasesStatus = "<span class='bad'>Bad</span>. Drop database $dropDatabasesString failed. Error code: " . $dropDatabaseErrorInfo[0] . ". Error info: " . $dropDatabaseErrorInfo[2] . ".";
 }
// Determine if drop database form was last form submitted and drop database checkboxes were not checked. If drop database form was last form submitted and drop database checkboxes were not checked, expression evaluates to boolean true.
} elseif (isset($_POST["isDropDatabaseFormSubmitted"])){
 // Drop database form was last form submitted and drop database checkboxes were not checked. Set string on $_SESSION["variable"], $index->property, and $db->property to report drop database failed no database to drop specified.
 $_SESSION["lastStatus"] = $index->body_main_lastStatus = $db->dropDatabasesStatus = "<span class='warning'>Warning</span>. Drop database failed. Error: No database specified.";
} else {
 // Drop database form was not last form submitted. Set string on $db->property to report nothing to report.
 // NOTE: This else reached when; 1.) Index page (front) controller (index.php) is initially loaded, 2.) any form other than drop database form is submitted.
 $db->dropDatabasesStatus = "Nothing to report.";
}

/* ---------- CREATE DATABASE ---------- */

// NOTE:
// Create database section must be placed before database table data section below; otherwise, last database created is not listed.
// Create databases natively does not allow &<>"'\ characters in database name.

// Determine if create database form was last form submitted. If create database form was last form submitted, expression evaluates to boolean true.
if (isset($_POST["createDatabase"])){
 // Create database form was last form submitted. Get name of database to create and set on $variable.
 $createDatabase = $_POST["createDatabase"];
 // Determine if database name field contained data. If database name field contained data, expression evaluates to boolean true.
 if ($createDatabase !== ""){
  // Database name field contained data. Get collation name for database to create and set on $variable.
  $collation = $_POST["collation"];
  // Call create database method to instruct MySQL to create database and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
  $sth = $db->createDatabase($createDatabase, $collation);
  // Call html entities method to reduce XSS attack surface and set return value on $variable.
  // NOTE:
  // Untrusted data output to HTML is vulnerable to XSS attack. $createDatabase is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
  // Create database natively does not allow &<>"'\ characters in database name. Therefore, although, in theory, running html entities method here is not required, it is run here as defense in depth measure.
  $createDatabase = $index->htmlEntities($createDatabase);
  // Determine if create database successful. If successful, expression evaluates to boolean true.
  if ($db->isCreateDatabaseSuccessful){
   // Create database successful. Set string on $_SESSION["variable"], $index->property, and $db->property to report create database name successful.
   $_SESSION["lastStatus"] = $index->body_main_lastStatus = $db->createDatabaseStatus = "<span class='good'>Good</span>. Database '$createDatabase' successfully created.";
  } else {
   // Create database unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
   // NOTE:
   // Returns an array of extended error information for last operation on statement handle.
   // If PDOStatement::execute() method no error, print_r($createDatabaseErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
   // If PDOStatement::execute() method error (sql syntax error), print_r($createDatabaseErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZCREATE DATABASE db1 COLLATE latin1_swedish_ci' at line 1 ).
   // If PDOStatement::execute() method error (user account already created), print_r($createDatabaseErrorInfo) is: Array ( [0] => HY000 [1] => 1007 [2] => Can't create database 'db1'; database exists ).
   $createDatabaseErrorInfo = $sth->errorInfo();
   // Set string on $_SESSION["variable"], $index->property, and $db->property to report create database name unsuccessful with PDOStatement::errorInfo error code and error info.
   // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $createDatabase to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if create database unsuccessful, $createDatabaseErrorInfo[2] is vulnerable to XSS.
   $_SESSION["lastStatus"] = $index->body_main_lastStatus = $db->createDatabaseStatus = "<span class='bad'>Bad</span>. Create database '$createDatabase' failed. Error code: " . $createDatabaseErrorInfo[0] . ". Error info: " . $createDatabaseErrorInfo[2] . ".";
  }
 } else {
  // Database name field contained no data. Set string on $_SESSION["variable"], $index->property, and $db->property to report create database unsuccessful because database name not specified.
  $_SESSION["lastStatus"] = $index->body_main_lastStatus = $db->createDatabaseStatus = "<span class='warning'>Warning</span>. Create database failed. Error: No database name specified.";
 }
} else {
 // Create database form was not the last form submitted. Set string on $db->property to report nothing to report.
 // NOTE: This else reached when; 1.) Index page (front) controller (index.php) is initially loaded, and 2.) any form other than create database form is submitted.
 $db->createDatabaseStatus = 'Nothing to report.';
}

/* ---------- DATABASES TABLE DATA ---------- */

// NOTE:
// Database table data section must be placed after create databases section above; otherwise, last database created is not listed.
// Database table data section automatically runs when Databases section controller (controllers/databases.php, this page) is loaded.

// Call select database and collation method to instruct MySQL to select database names and collation types and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
$sth = $db->selectDatabaseAndCollation();
// Determine if select database names and collation types successful. If successful, expression evaluates to boolean false.
if ($db->isSelectDatabaseAndCollationSuccessful){
 // Select database names and collation types successful. Set string on $index->property to report select database names and collation types successful.
 // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. Database names and collation types successfully selected.";
 // Call PDOStatement::fetchAll(PDO::FETCH_NUM) method to get all of the result set rows and set return value on $db->property.
 // NOTE:
 // Returns an array containing all of the result set rows or boolean false on failure. An empty array is returned if there are zero results to fetch.
 // Both $sth->fetchAll(PDO::FETCH_NUM) and $sth->fetchAll() work with Database page view (views/databases-html.php) code as previously written 17Apr17 using mysqli/mysqli_result object, not PDO/PDOStatement object.
 // XAMPP 5.6.24-1, if $sth->fetchAll(), print_r($db->fetchDatabaseAndCollation) is: Array ( [0] => Array ( [schema_name] => information_schema [0] => information_schema [default_collation_name] => utf8_general_ci [1] => utf8_general_ci ) [1] => Array ( [schema_name] => mysql [0] => mysql [default_collation_name] => latin1_swedish_ci [1] => latin1_swedish_ci ) [2] => Array ( [schema_name] => performance_schema [0] => performance_schema [default_collation_name] => utf8_general_ci [1] => utf8_general_ci ) [3] => Array ( [schema_name] => phpmyadmin [0] => phpmyadmin [default_collation_name] => utf8_bin [1] => utf8_bin ) [4] => Array ( [schema_name] => test [0] => test [default_collation_name] => latin1_swedish_ci [1] => latin1_swedish_ci ) ).
 // XAMPP 5.6.24-1, if $sth->fetchAll(PDO::FETCH_NUM), print_r($db->fetchDatabaseAndCollation) is: Array ( [0] => Array ( [0] => information_schema [1] => utf8_general_ci ) [1] => Array ( [0] => mysql [1] => latin1_swedish_ci ) [2] => Array ( [0] => performance_schema [1] => utf8_general_ci ) [3] => Array ( [0] => phpmyadmin [1] => utf8_bin ) [4] => Array ( [0] => test [1] => latin1_swedish_ci ) ).
 $db->fetchDatabaseAndCollation = $sth->fetchAll(PDO::FETCH_NUM);
 // Determine if fetch database names and collations types successful. If successful, expression evaluates to boolean true.
 if ($db->fetchDatabaseAndCollation !== false){
  // Fetch database names and collation types successful. Set string on $index->property to report fetch database names and collation types successful.
  // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. Database names and collation types successfully fetched.";
 } else {
  // Fetch database names and collation types unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::fetchAll() method error info and set return value on $variable.
  // NOTE:
  // Returns an array of extended error information for last operation on statement handle.
  // If PDOStatement::fetchAll() method no error, print_r($fetchDatabaseAndCollationErrorInfo) is: ??? not determined.
  // If PDOStatement::fetchAll() method error, print_r($fetchDatabaseAndCollationErrorInfo) is: ??? not determined.
  // COMMENT OUT: $fetchDatabaseAndCollationErrorInfo = $sth->errorInfo();
  // Set string on $index->property to report fetch database names and collation types unsuccessful with PDOStatement::errorInfo error code and error info.
  // NOTE: PDOStatement::errorInfo[2] ??? outputs untrusted data ??? to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if fetch database names and collation types unsuccessful, $fetchDatabaseAndCollationErrorInfo[2] is ??? vulnerable to XSS.
  // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Fetch database names and collation types failed. Error code: " . $fetchDatabaseAndCollationErrorInfo[0] . ". Error info: " . $fetchDatabaseAndCollationErrorInfo[2] . ".";
 }
} else {
 // Select database names and collation types unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
 // NOTE:
 // Returns an array of extended error information for last operation on statement handle.
 // If PDOStatement::execute() method no error, print_r($selectDatabaseAndCollationErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
 // If PDOStatement::execute() method error (SQL syntax error), print_r($selectDatabaseAndCollationErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZSELECT schema_name, default_collation_name FROM information_schema.schemata' at line 1 ).
 $selectDatabaseAndCollationErrorInfo = $sth->errorInfo();
 // Set string on $index->property to report select database names and collation types unsuccessful with PDOStatement::errorInfo error code and error info.
 // NOTE: PDOStatement::errorInfo[2] does not output untrusted data to HTML. Therefore, if select database names and collation types unsuccessful, $selectDatabaseAndCollationErrorInfo[2] is not vulnerable to XSS.
 // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Select database names and collation types failed. Error code: " . $selectDatabaseAndCollationErrorInfo[0] . ". Error info: " . $selectDatabaseAndCollationErrorInfo[2] . ".";
}

/* ---------- SHOW COLLATION SERVER ---------- */

// NOTE: Show collation server section automatically runs when Databases page controller (controllers/databases.php, this page) is loaded.

// Call show collation server method to instruct MySQL to show collation server (ie, the MySQL default collation) and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
$sth = $db->showCollationServer();
// Determine if show collation server successful. If successful, expression evaluates to boolean true.
if ($db->isShowCollationServerSuccessful){
 // Show collation server successful. Set string on $index->property to report show collation server successful.
 // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. Collation server successfully shown.";
 // Call PDOStatement::fetch(PDO::FETCH_ASSOC) method to get next row from result set and set return value on $db->property.
 // NOTE:
 // Returns an array indexed by column name as returned in your result set or boolean false on failure.
 // XAMPP 5.6.24-1 print_r($db->fetchCollationServer) is: Array ( [Variable_name] => collation_server [Value] => latin1_swedish_ci ).
 $db->fetchCollationServer = $sth->fetch(PDO::FETCH_ASSOC);
 // Determine if fetch collation server successful. If successful, expression evaluates to boolean true.
 if ($db->fetchCollationServer !== false){
  // Fetch collation server successful. Get server collation and set on $db->property.
  $db->collationServer = $db->fetchCollationServer["Value"];
  // Set string on $index->property to report fetch collation server successful.
  // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. Collation server successfully fetched.";
 } else {
  // Fetch collation server unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::fetch() method error info and set return value on $variable.
  // NOTE:
  // Returns an array of extended error information for last operation on statement handle.
  // If PDOStatement::fetch() method no error, print_r($fetchCollationServerErrorInfo) is: ??? not determined.
  // If PDOStatement::fetch() method error, print_r($fetchCollationServerErrorInfo) is: ??? not determined.
  // COMMENT OUT: $fetchCollationServerErrorInfo = $sth->errorInfo();
  // Set string on $index->property to report fetch collation server unsuccessful with PDOStatement::errorInfo error code and error info.
  // NOTE: PDOStatement::errorInfo[2] ??? outputs untrusted data ??? to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if fetch collation server unsuccessful, $fetchCollationServerErrorInfo[2] is ??? vulnerable to XSS.
  // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Fetch collation server failed. Error code: " . $fetchCollationServerErrorInfo[0] . ". Error info: " . $fetchCollationServerErrorInfo[2] . ".";
 }
} else {
 // Show collation server unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
 // NOTE:
 // Returns an array of extended error information for last operation on statement handle.
 // If PDOStatement::execute() method no error, print_r($showCollationServerErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
 // If PDOStatement::execute() method error (SQL syntax error), print_r($showCollationServerErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZSHOW VARIABLES LIKE 'collation_server'' at line 1 ).
 $showCollationServerErrorInfo = $sth->errorInfo();
 // Set string on $index->property to report show collation server unsuccessful with PDOStatement::errorInfo error code and error info.
 // NOTE: PDOStatement::errorInfo[2] does not output untrusted data to HTML. Therefore, if show collation server unsuccessful, $showCollationServerErrorInfo[2] is not vulnerable to XSS.
 // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Show collation server failed. Error code: " . $showCollationServerErrorInfo[0] . ". Error info: " . $showCollationServerErrorInfo[2] . ".";
}

/* ---------- REQUIRE VIEW AND RETURN HTML CONTENT TO INDEX PAGE (FRONT) CONTROLLER ---------- */

// Require Databases page view and return Databases page HTML content to Index page (front) controller (index.php).
$html = require_once "views/databases-html.php";
return $html;
