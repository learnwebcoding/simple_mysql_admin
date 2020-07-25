<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: views/databases-html.php.
 * Purpose: Databases page view.
 * Used in: controllers/databases.php.
 * Last reviewed/updated: 06 Apr 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) createDatabaseForm, and 2.) dropDatabaseForm. */

/* ---------- DATABASES TABLE DATA ---------- */

if (!empty($db->fetchDatabaseAndCollation)){
 foreach ($db->fetchDatabaseAndCollation as list($database, $collation)){
  // Call html entities method to reduce XSS attack surface and set return value on $variable.
  // NOTE:
  // Untrusted data output to HTML is vulnerable to XSS attack. $database is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
  // Create database natively does not allow &<>"'\ characters in database name. Therefore, although, in theory, running html entities method here is not required, it is run here as defense in depth measure.
  $database = $index->htmlEntities($database);
  $db->databasesTableDataHtml .= "<tr>\n";
  // Drop column.
  // NOTE: rsvd indicates database reserved for MySQL/phpMyAdmin administration. Simple MySQL Admin does not allow dropping.
  if (($database === "information_schema") || ($database === "mysql") || ($database === "performance_schema") || ($database === "phpmyadmin") || ($database === "sys")){
   $db->databasesTableDataHtml .= "<td class='text-align-center'>rsvd</td>\n";
  } else {
   $db->databasesTableDataHtml .= "<td class='text-align-center'><label><input type='checkbox' name='dropDatabasesArray[]' value='$database' /><span>&#10003;</span></label></td>\n";
  }
  // Database column.
  if (($database === "information_schema") || ($database === "mysql") || ($database === "performance_schema") || ($database === "phpmyadmin") || ($database === "sys") || ($database === "test")){
   // NOTE: Asterisk (*) character indicates database created by MySQL/phpMyAdmin.
   $db->databasesTableDataHtml .= "<td>$database*</td>\n";
  } else {
   $db->databasesTableDataHtml .= "<td>$database</td>\n";
  }
  // Collation column.
  $db->databasesTableDataHtml .= "<td>$collation</td>\n";
  $db->databasesTableDataHtml .= "</tr>\n";
 }
} else {
 // Similar text in User Accounts page view (views/userAccnts-html.php) and Databases page view (views/databases-html.php).
 $db->databasesTableDataHtml = "<tr>\n<td colspan='4'>Error: Unable to list MySQL databases. Most likely <!-- you are not logged into MySQL under the MySQL root user account, -->the MySQL root user account has been changed/corrupted in some way that it is unable to list MySQL databases, or Simple MySQL Admin has been changed/corrupted in some way that it is unable to list MySQL databases. To continue, please try manually entering the credentials for a MySQL user account with sufficient privileges to list MySQL databases into the Simple MySQL Admin <span class='filename'>simple_mysql_admin/connection_credentials.php</span> file, or try uninstalling Simple MySQL Admin and redownloading and reinstalling Simple MySQL Admin from scratch.</td>\n</tr>";
}
// Unset foreach special variables used below, including in views/databases-html-content.php.
unset($collation);

/* ---------- YOUR DEFAULT COLLATION SERVER ---------- */

$yourDefaultCollationServerHtml = "Your MySQL default collation is $db->collationServer";

if ($db->collationServer === "latin1_swedish_ci"){
 $yourDefaultCollationServerHtml .= ", which is the normal MySQL default collation.";
} else {
 $yourDefaultCollationServerHtml .= ". The normal MySQL default collation is latin1_swedish_ci.";
}

/* ---------- SELECT COLLATION OPTIONS ---------- */

// NOTE: $db->selectCollationOptionsArray initialized in Databases page controller (controllers/databases.php).
foreach ($db->selectCollationOptionsArray as $collation){
 // Declare $variable for use in foreach.
 $collationRecommendedFor;
 if ($collation === "utf8mb4_general_ci"){
  $collationRecommendedFor = "(recommended for WordPress)";
 } else {
  $collationRecommendedFor = "";
 }
 if ($collation === $db->collationServer){
  $db->selectCollationOptionsHtml .= "<option value='$collation' selected='selected'>$collation $collationRecommendedFor</option>\n";
 } else {
  $db->selectCollationOptionsHtml .= "<option value='$collation'>$collation $collationRecommendedFor</option>\n";
 }
}

/* ---------- DATABASE HTML CONTENT ---------- */

// Quantity of content makes logic difficult to read. Therefore, place content in external file.
$databasesHtmlContentHtml = require_once "views/databases-html-content.php";

/* ---------- VIEW ---------- */

return "
   <div class='view-container'>
    $databasesHtmlContentHtml
   </div>
";
