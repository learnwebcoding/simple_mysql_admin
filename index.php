<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: index.php.
 * Purpose: Index page controller. Front controller for Simple MySQL Admin. Web page file. Configure error reporting. Require Index page class (Index.class.php) and instantiate Index page $index object. By default require Requirements page controller (controllers/requirements.php).  Dynamically require Requirements page controller (controllers/requirements.php), User Accounts page controller (controllers/userAccnts.php) and Databases page controller (controllers/databases.php). Require Index page view (index-html.php) and output Index page HTML content to web browser.
 * Used in: No other file.
 * Last reviewed/updated: 11 Mar 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017. */

/* ---------- ERROR REPORTING ---------- */

// Configure error reporting.
// NOTE: To display errors, change '0' to '1'. When displaying errors; 1.) table content font-size changes from 14px (correct) to 16px (incorrect) in IE11, FF54, and CH59, and 2.) clicking tabs can cause screen to flicker, which is most frequently noticeable (in FF53 and IE11, not CH58) when click User Accounts tab.
error_reporting(E_ALL);
ini_set('display_errors', '0');

/* ---------- INSTANTIATE OBJECT ---------- */

// Require Index page class and instantiate Index page object.
require_once "classes/Index.class.php";
$index = new Index();

/* ---------- ASSIGNMENTS ---------- */

$index->head_title = "Simple MySQL Admin"; // No markup in title tags.
$index->head_meta_charset = "UTF-8";
$index->head_meta_author = "Steve Taylor";
$index->head_meta_keywords = "simple, mini, MySQL, MariaDB, admin, administrator"; // Cap words as if used in sentence. No markup in keywords.
$index->head_meta_description = "The Simple MySQL Admin front controller."; // Sentence and/or structured data less than 160 char per Google. No markup in description.
$index->head_meta_viewport = "width=device-width, initial-scale=1";
$index->head_link_stylesheets = include_once "stylesheets/stylesheets.php";
$index->body_main_appName = "Simple MySQL Admin";
$index->body_main_lastStatus = "Nothing to report.";
$index->body_main_tabs = require_once "views/tabs.php";
// $index->body_main_page = see page to load dynamically section below.
// $index->body_main_javascript = see javascripts to load dynamically section below.

/* ---------- GET MYSQL ROOT USER ACCOUNT PASSWORD FROM CONNECT TO MYSQL FORM AND SET ON SESSION ---------- */

// Determine if connect to MySQL form was submitted. If submitted, expression evaluates to boolean true.
if (isset($_POST["password"])){
 // Connect to MySQL form was submitted. Get MySQL root user account password value from $_POST superglobal variable and set on $_SESSION superglobal variable.
 // NOTE: If connect to MySQL form was submitted with empty/blank password field, password value is empty string ("").
 $_SESSION["password"] = $_POST["password"];
}

/* ---------- MySQL RUNNING: MYSQLI ---------- */

// Determine and report MySQL running status (ie, running or not running) by trying to connect to MySQL using valid host name and invalid user name and invalid password. Use invalid user name and invalid password to avoid prompting for valid credentials, and because if MySQL not running, cannot connect to MySQL regardless if using valid/invalid credentials.
// NOTE: By default, MySQL allows 'Any'@'localhost' (using password: NO) to connect to MySQL (to confirm, see phpMyAdmin User accounts tab). Hence, the need to use invalid password.
$validHostname = "localhost";
$invalidUsername = "bogusUsername_abc123";
$invalidPassword = "bogusPassword_def456";
// Call connect mysqli method to establish mysqli connection to MySQL and set returned mysqli object on $index->property.
$index->mysqli = $index->connectMysqli($validHostname, $invalidUsername, $invalidPassword);
// Determine if MySQL running. If running, expression evaluates to boolean true.
if (($index->mysqli->connect_error === null) || ($index->mysqli->connect_errno === 2002)){
 // MySQL not running. Set boolean false on $index->property to indicate MySQL not running.
 $index->isRunning = false;
 // Set string on $index->property to indicate MySQL not running.
 $index->runningStatus = "<span class='bad'>Bad</span>. MySQL is not running. To continue, first start MySQL and then reload this web page.";
 // Set string on $index->property to indicate nothing to report.
 $index->connectionStatus = "Nothing to report.";
} else {
 // MySQL running. Set boolean true on $index->property to indicate MySQL running.
 $index->isRunning = true;
 // Set string on $index->property to indicate MySQL running.
 $index->runningStatus = "<span class='good'>Good</span>. MySQL is running.";
}

/* ---------- MySQL CONNECTION: PDO ---------- */

// Determine if MySQL running. If MySQL running, expression evaluates to boolean true.
if ($index->isRunning){
 // MySQL running. First try PDO connection to MySQL root user account getting typical MySQL hostname and root user account username and password (ie, credentials) from Simple MySQL Admin connection_credentials.php file. Get typical MySQL hostname and root user account credentials from connection_credentials.php file.
 include_once 'connection_credentials.php';
 // Set PDO_MYSQL Data Source Name (DSN/dsn) without database and with charset on $variable.
 // NOTE:
 // With database change to $dsn = "mysql:host=$hostname;dbname=test;charset=utf8";.
 // Charset UTF-8 also explicitly set in Index page class (classes/Index.class.php) $index->htmlEntities() method.
 $dsn = "mysql:host=$hostname;charset=utf8";
 // 1.) Call $index->connectPdo() method to perform first try PDO MySQL connection.
 // NOTE: This try occurs automatically without prompting and gets typical MySQL hostname and root user account credentials from connection_credentials.php file.
 $index->dbh = $index->connectPdo($dsn, $username, $password);
 // Call $index->isConnected() method to determine if first try PDO MySQL connection successful and set return value on $index->property. If successful, returns boolean true. If unsuccessful, returns boolean false.
 $index->isConnected = $index->isConnected($index->dbh);
 // Determine if first try PDO MySQL connection successful. If successful, expression evaluates to boolean true.
 if ($index->isConnected){
  // First try PDO MySQL connection successful. Set string on $index->property to indicate (first try) PDO MySQL connection successful.
  $index->connectionStatus = "<span class='good'>Good</span>. You are connected to MySQL.";
  // Set string on $variable to indicate connected user account.
  $connectedUserAccnt = "'$username'@'$hostname'";
 } else {
  // First try PDO MySQL connection unsuccessful. Determine if connect to MySQL form has been submitted. If submitted, expression evaluates to boolean true.
  if (isset($_SESSION["password"])){
   // Connect to MySQL form has been submitted. Get MySQL root user account password from $_SESSION superglobal variable and set on $variable.
   // NOTE: $_SESSION["password"] from Index page (front) controller (index.php, this file) get MySQL root user account password from connect to MySQL form and set on session section above.
   $password = $_SESSION["password"];
   // 2.) Call $index->connectPdo() method to perform second try PDO MySQL connection.
   // NOTE: This try gets typical MySQL hostname and root user account username from connection_credentials.php and gets MySQL root user account password from connect to MySQL form prompt for MySQL root user account password, which is set on $_SESSION superglobal variable for current web browser session connection persistence to avoid additional connect to MySQL form prompts for password. If connection_credentials.php is writable, future close/open web browser first try PDO connection to MySQL above are successful and this second try is not reached. If connection_credentials.php is not writable, future close/open web browser first try PDO connection to MySQL above are again unsuccessful and this second try with connect to MySQL form prompt for MySQL root user account password is again reached.
   $index->dbh = $index->connectPdo($dsn, $username, $password);
   // Call $index->isConnected() method to determine if second try PDO MySQL connection successful and set return value on $index->property. If successful, returns boolean true. If unsuccessful, returns boolean false.
   $index->isConnected = $index->isConnected($index->dbh);
   // Determine if second try PDO MySQL connection successful. If successful, expression evaluates to boolean true.
   if ($index->isConnected){
    // Second try PDO MySQL connection successful. Set string on $index->property to indicate (second try) PDO MySQL connection successful.
    $index->connectionStatus = "<span class='good'>Good</span>. You are connected to MySQL.";
    // Set string on $variable to indicate connected user account.
    $connectedUserAccnt = "'$username'@'$hostname'";
    // Set string representing typical MySQL hostname and root user account credentials, including root user account password from connect to MySQL form, on $variable.
    $connectionCredentialsFileContent = "<?php" . PHP_EOL . " \$hostname = 'localhost'; // Or IP address (e.g., \$hostname = '127.0.0.1';)." . PHP_EOL . " \$username = 'root';" . PHP_EOL . " \$password = '$password';" . PHP_EOL . "";
    // Call $index->writeToFile() method to try to write MySQL hostname and root user account credentials, including root user account password from connect to MySQL form, to Simple MySQL Admin simple_phpmyadmin/connection_credentials.php file.
    // NOTE: Per "w" parameter, open file for writing only, place file pointer at beginning of file, truncate file to zero length, and if file does not exist, attempt to create it.
    // NOTE: No apparent benefit to using PHP is_writable() method in conjunction with PHP fopen() method.
    $isWriteCredentialsToFileSuccessful = $index->writeToFile("connection_credentials.php", "w", $connectionCredentialsFileContent);
    // Determine if write credentials to file is not successful. If not successful, expression evaluates to boolean true.
    if (!$isWriteCredentialsToFileSuccessful){
     // Write credentials to file unsuccessful. Set string on $index->property to indicate (second try) PDO MySQL connection successful but write credentials to connection_credentials.php unsuccessful with advise.
     $index->connectionStatus = "<span class='good'>Good</span>/<span class='bad'>Bad</span>. You are connected to MySQL. However, the MySQL root user account password could not be written to the Simple MySQL Admin <span class='filename'>simple_mysql_admin/connection_credentials.php</span> file. This means the prompt for the MySQL root user account password will appear each time the web browser is closed/opened and Simple MySQL Admin is started. To avoid the prompt, either make certain the <span class='filename'>simple_mysql_admin/connection_credentials.php</span> file exists and is writable (i.e., the read-only attribute is unchecked), or manually enter the MySQL root user account password into the <span class='filename'>simple_mysql_admin/connection_credentials.php</span> file.";
    } else {
     // Write credentials to file successful. Nothing to do here.
    }
   } else {
    // Second try PDO MySQL connection unsuccessful. Most likely MySQL root user account exists, but password is invalid. Set string on $index->property to indicate (second try) PDO MySQL connection unsuccessful with advise.
    $index->connectionStatus = "<span class='bad'>Bad</span>. The MySQL root user account password is invalid. To continue, please enter the MySQL root user account password below. If uncertain, try the default MySQL root user account password (no password) by leaving the password field below empty/blank and clicking connect.";
   }
  } else {
   // Connect to MySQL form has not been submitted. Set string on $index->property to indicate (first try) PDO MySQL connection unsuccessful with advice.
   $index->connectionStatus = "<span class='bad'>Bad</span>. You are not connected to MySQL. Simple MySQL Admin, like phpMyAdmin, connects to MySQL using the MySQL root user account credentials. Apparently the MySQL root user account is password protected. To continue, please enter the MySQL root user account password below.";
  }
 }
}

/* ---------- PAGE TO LOAD DYNAMICALLY ---------- */

// Determine if PDO connected to MySQL. If connected, expression evaluates to boolean true.
// NOTE: PDO connected to MySQL if MySQL running and PDO connected to MySQL.
//if ($index->isConnected){
 // PDO connected to MySQL. Determine if Tabs view (views/tabs.php) tab just clicked or if Tabs view (views/tabs.php) tab clicked earlier in web browser session. If just clicked or if clicked earlier in web browser session, expression evaluates to boolean true.
 // NOTE: If Tabs view (views/tabs.php) tab just clicked, isset($_GET["page"] evaluates to boolean true. If Tabs view (views/tabs.php) tab clicked earlier in web browser session, isset($_SESSION["page"] evaluates to boolean true.
 if ((isset($_GET["page"])) || (isset($_SESSION["page"]))){
  // Tabs view (views/tabs.php) tab just clicked or Tabs view (views/tabs.php) tab clicked earlier in web browser session. Determine if Tabs view (views/tabs.php) tab just clicked. If just clicked, expression evaluates to boolean true.
  if (isset($_GET["page"])){
   // Tabs view (views/tabs.php) tab just clicked. Get page to load dynamically from $_GET superglobal variable and set on $index->property and $_SESSION superglobal variable.
   // NOTE:
   // This is to dynamically change currently loaded page per Tabs view (views/tabs.php) tab click.
   // Index page (front) controller (index.php, this file) dynamically loads Requirements page controller (controllers/requirements.php), User Accounts page controller (controllers/userAccnts.php), or Databases page controller (controllers/databases.php) using Tabs view (views/tabs.php) URL variables, $_GET superglobal, and $index->page, which does not require web browser cookies enabled.
   $_SESSION["page"] = $index->page = $_GET["page"];
  } else {
   // Tabs view (views/tabs.php) tab not just clicked. Instead, Tabs view (views/tabs.php) tab clicked earlier in web browser session. Get page to load dynamically from $_SESSION superglobal variable and set on $index->property.
   // NOTE:
   // This is to persist currently dynamically loaded page across HTTP requests.
   // Index page (front) controller (index.php, this file) persists currently dynamically loaded page across HTTP requests using $_SESSION["page"], which requires web browser cookies enabled.
   $index->page = $_SESSION["page"];
  }
 } else {
  // Tabs view (views/tabs.php) tab not just clicked and Tabs view (views/tabs.php) tab not clicked earlier in web browser session.
  // NOTE: This is default load Requirements page.
  $_SESSION["page"] = $index->page = "requirements";
 }
 // Set page to load dynamically on $index->property.
 $index->body_main_page = require_once "controllers/$index->page.php";
//}

/* ---------- JAVASCRIPTS TO LOAD DYNAMICALLY ---------- */

// Set default javascript to load dynamically for all pages (Requirements, User Accounts, and Databases) on $variable.
// NOTE: Per OWASP XSS (Cross Site Scripting) Prevention Cheat https://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet, never place untrusted data in HTML script tags. $index->page is trusted data. Therefore, $index->page is not vulnerable to XSS.
$javascript =
"<script type='text/javascript' src='javascripts/simple_mysql_admin.js'></script>
   <script type='text/javascript'>TabsUtil.page = '$index->page';</script>";

// Determine if PDO not connected to MySQL. If PDO not connected to MySQL, expression evaluates to boolean true.
// NOTE: PDO not connected to MySQL if MySQL not running or PDO not connected to MySQL.
if (!$index->isConnected){
 // PDO not connected to MySQL. Append javascript to end of $variable.
 $javascript .= "
   <script type='text/javascript'>TabsUtil.hideTabs = true;</script>";
}

// Determine if page to load dynamically is User Accounts page. If User Accounts page, expression evaluates to boolean true.
// Per OWASP XSS (Cross Site Scripting) Prevention Cheat https://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet, never place untrusted data in HTML script tags. $uA->privsCsv and $uA->isEditUserAccntPrivsFieldsetForm are trusted data. Therefore, $uA->privsCsv and $uA->isEditUserAccntPrivsFieldsetForm are not vulnerable to XSS.
if ($index->page === "userAccnts"){
 // Page to load dynamically is User Accounts page. Append javascripts to end of $variable.
 $javascript .= "
   <script type='text/javascript'>EditUserAccntPrivsUtil.privsCsv = '$uA->privsCsv';</script>
   <script type='text/javascript'>EditUserAccntPrivsUtil.isEditUserAccntPrivsFieldsetForm = '$uA->isEditUserAccntPrivsFieldsetForm';</script>";
}

// Set JavaScripts to load dynamically on $index->property.
$index->body_main_javascript = $javascript;

/* ---------- REQUIRE VIEW AND OUTPUT HTML CONTENT TO WEB BROWSER ---------- */

// Require Index page view and output Index page HTML content to web browser.
$html = require_once "views/index-html.php";
echo $html;
