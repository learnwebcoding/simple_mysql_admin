<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: classes/Index.class.php.
 * Purpose: Index page model. Class definition for Index page $index object. Index page $index object declares properties representing Index page web page content and defines methods representing Index page interface.
 * Used in: index.php.
 * Last reviewed/updated: 02 Apr 2018.
 * Last reviewed/updated for SQL injection: 31 May 2017.
 * Published: 14 May 2017.
 * NOTE: No HTML in class definition. */

/* -------------------- PHP CLASS DEFINITION -------------------- */

// Instantiated as $index object in controller (index.php).
class Index {

/* ---------- PROPERTIES ---------- */

 // Properties: $index->property.
 // Purpose: $index object properties.
 // NOTE: No logic in properties.

 // First assigned value in Index page (front) controller (index.php) | value assignments section.
 public
  $head_title,				// String.
  $head_meta_charset,		// String.
  $head_meta_author,		// String.
  $head_meta_keywords,		// String.
  $head_meta_description,	// String.
  $head_meta_viewport,		// String.
  $head_link_stylesheets,	// String.
  $body_main_webPageTitle,	// String.
  $body_main_lastStatus,	// String.
  $body_main_tabs,			// String.
  $body_main_page,			// Statement.
  $body_main_javascript;	// String.

 // First assigned value in Index page (front) controller (index.php) | MySQL running: mysqli section.
 public
  $mysqli,				// Object. Represents a connection between PHP and MySQL. PHP connects to MySQL via the $index->mysqli object.
  $isRunning,			// Boolean.
  $runningStatus,		// String.
  $connectionStatus;	// String.

 // First assigned value in Index page (front) controller (index.php) | MySQL connection: PDO section.
 public
  $dbh,				// Object. Represents a connection between PHP and a database server (aka, dbh = database handle). PHP connects to database server via the $index->dbh property (an object).
  $isConnected;		// Boolean.

 // First assigned value in Index page (front) controller (index.php) | page to load dynamically section.
 public
  $page;		// String. Value used to; 1.) load pages dynamically in Index page (front) controller (index.php) via Tabs view (views/tabs.php) tab URL variables and $_GET["page"] superglobal variable, 2.) persist currently loaded page across HTTP requests via Index page (front) controller (index.php) $_SESSION["page"] superglobal variable, and 3.) highlight tabs in Tabs view (views/tabs.php) via JavaScript TabsUtil.page property.

/* ---------- METHODS ---------- */

 // NOTE:
 // Default access level for methods is public.
 // PDO = PHP Data Object.

 // Method: function __construct(). Class constructor method.
 // Purpose: In general, to automatically perform things when object is instantiated. Typically, to initialize properties with non constant values (http://php.net/manual/en/language.oop5.properties.php). Here: 1.) to start session so as to persist data across HTTP requests (ie, across Index page (front) controller (index.php) reloads as occurs upon form submission, not upon F5 (Refresh/Reload)).
 // NOTE:
 // Class constructor method automatically runs when object instantiated.
 // Arguments to object constructor method (in controller) are automatically passed to class constructor method (in class, here) when object is instantiated (in controller). This allows objects to be instantiated with properties from other objects, which is known as dependency injection.
 function __construct(){
  // Start new session.
  // NOTE:
  // session_start() can be used to start new or resume existing session.
  // Since Index page (front) controller (index.php, this file) is Simple MySQL Admin front controller, start new session here means session available to entire Simple MySQL Admin app/all .php web pages.
  // To use cookie-based sessions, session_start() must be called before outputting anything to the browser (http://php.net/manual/en/function.session-start.php).
  // For future consideration: Currently, Simple MySQL Admin does not: 1.) unset $_SESSION superglobal variables by explicitly setting $_SESSION to empty array via $_SESSION = array(); 2.) destroy session via session_destroy(); and 3.) worry about session security such as session hijacking, session fixation, etc.
  session_start();
 }

 // Method: $index->connectMysqli().
 // Purpose: Instantiate and return an object that represents a connection between PHP and MySQL. PHP connects to MySQL via the $index->mysqli object. To determine if MySQL is running.
 function connectMysqli($hostname, $username, $password){
  $this->mysqli = new mysqli($hostname, $username, $password);
  return $this->mysqli;
  $this->mysqli->close();
 }

 // Method: $index->connectPdo().
 // Purpose: Instantiate and return a PDO instance that represents a connection between PHP and MySQL (aka, dbh = database handle). PHP connects to MySQL via the $index->dbh object. To try PDO connection to MySQL.
 function connectPdo($dsn, $username, $password){
  try {
   $this->dbh = new PDO($dsn, $username, $password);
   return $this->dbh;
  } catch (Exception $e){
   // Nothing to do here.
  }
 }

 // Method: $index->isConnected().
 // Purpose: Determine if PDO MySQL connection successful and return result as boolean value.
 function isConnected($dbh){
  // Determine if PDO MySQL connection successful. If PDO MySQL connection successful, expression evaluates to boolean true.
  if (isset($dbh)){
   // PDO MySQL connection successful. Return boolean true to indicate PDO MySQL connection successful.
   return true;
  } else {
   // PDO MySQL connection unsuccessful. Return boolean false to indicate PDO MySQL connection unsuccessful.
   return false;
  }
 }

 // Method: $index->writeToFile().
 // Purpose: Write content to a file and return result as boolean value.
 function writeToFile($filename, $mode, $content){
  // fopen() function tries to open file pointer resource (aka, handle) to $filename. If fopen() can open file pointer resource to $filename, fopen() returns the file pointer resource. If fopen() cannot open file pointer resource to $filename, fopen() function returns boolean false. run fopen(), set return value on $fileHandle, and determine if fopen() cannot open file pointer resource to $filename. If fopen() cannot open file pointer resource to $filename, !$fileHandle evaluates to boolean true.
  if (!$fileHandle = fopen($filename, $mode)){
   // Cannot open file pointer resource to $filename. Return boolean false to indicate write to file unsuccessful.
   return $isWriteToFileSuccessful = false;
  } else {
   // Can open file pointer resource to $filename. Write to file.
   fwrite($fileHandle, $content);
   // Close open file pointer resource.
   fclose($fileHandle);
   // Return boolean true to indicate write to file successful.
   return $isWriteToFileSuccessful = true;
  }
 }

 // Method: $index->htmlEntities().
 // Purpose: To reduce XSS attack surface, convert all applicable characters to HTML entities.
 // NOTE:
 // Explicitly set ENT_HTML401 even though it is default.
 // Charset UTF-8 also explicitly set in Index page (front) controller (index.php) $dsn statement.
 // ENT_QUOTES escapes: ' with &#039; and " with &quot;.
 // ENT_HTML401 and ENT_XHTML escape at least: & with &amp;, < with &lt;, and > with &gt;. Do not escape \.
 // Can create user accounts with user: &, <, >, and ".
 // Cannot create user accounts with user: ', z'z, \, and z\z (no error, reports good successfully created, Simple MySQL Admin does not list account, phpMyAdmin lists account).
 function htmlEntities($str){
  return htmlentities($str, ENT_QUOTES | ENT_HTML401, "UTF-8");
 }

} // Close Index class.
