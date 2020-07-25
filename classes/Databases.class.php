<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: classes/Databases.class.php.
 * Purpose: Databases page model. Class definition for Databases page $db object. Database page $db object declares properties representing Database page web page content and defines methods representing Database page interface.
 * Used in: controllers/databases.php.
 * Last reviewed/updated: 02 Apr 2018.
 * Last reviewed/updated for SQL injection: 31 May 2017.
 * Published: 14 May 2017.
 * NOTE: No HTML in class definition. */

/* -------------------- PHP CLASS DEFINITION -------------------- */

// Instantiated as $db object in controller (controllers/databases.php).
class Databases {

/* ---------- PROPERTIES ---------- */

 // Properties: $db->property.
 // Purpose: $db object properties.
 // NOTE: No logic in properties.

 // First assigned value in Databases page class (classes/Databases.class.php, this file).
 private
  $dbh;		// Object. Represents a connection between PHP and a database server (aka, dbh = database handle). PHP connects to database server via the $index->dbh property (an object), which is passed from the $index object to the $db object. (function __construct() method below.)
 public
  $isDropDatabasesSuccessful,				// Boolean. ($db->dropDatabases() method below.)
  $isCreateDatabaseSuccessful,				// Boolean. ($db->createDatabase() method below.)
  $isSelectDatabaseAndCollationSuccessful,	// Boolean. ($db->selectDatabaseAndCollation() method below.)
  $fetchDatabaseAndCollation,				// Array or boolean false. ($db->selectDatabaseAndCollation() method below.)
  $isShowCollationServerSuccessful,			// Boolean. ($db->showCollationServer() method below.)
  $fetchCollationServer;					// Array or boolean false. ($db->showCollationServer() method below.)

 // First assigned value in Databases page controller (controllers/databases.php).
 public
  $selectCollationOptionsArray,		// Array. (assignments section.)
  $dropDatabasesStatus,				// String. (drop databases section.)
  $createDatabaseStatus,			// String. (create database section.)
  $collationServer;					// String. (show collation server section.)

 // First assigned value in Databases page view (views/databases-html.php).
 public
  $databasesTableDataHtml,			// String. First use is with .= operator. If not declared as $uA-property, throws following which references first use: Notice: Undefined variable: databasesTableDataHtml.
  $selectCollationOptionsHtml;		// String. First use is with .= operator. If not declared as $uA-property, throws following which references first use: Notice: Undefined variable: selectCollationOptionsHtml.

/* ---------- METHODS ---------- */

 // NOTE:
 // Default access level for methods is public.
 // PDO = PHP Data Object.

 // Method: function __construct(). Class constructor method.
 // Purpose: In general, to automatically perform things when object is instantiated. Typically, to initialize properties with non constant values (http://php.net/manual/en/language.oop5.properties.php). Here, to initialize the $db object with the $index->dbh property, which is an object that represents a connection between PHP and MySQL. Accomplished by passing $index->dbh as argument to $db object constructor method in Databases page controller (databases.php), which, in turn, is automatically passed as argument to Databases class constructor method in Databases page class (Databases.class.php, this file) when $db object is instantiated.
 // NOTE:
 // Class constructor method automatically runs when object instantiated.
 // Arguments to object constructor method (in controller) are automatically passed to class constructor method (in class, here) when object is instantiated (in controller). This allows objects to be instantiated with properties from other objects, which is known as dependency injection.
 function __construct($pdoConnObject){
  $this->dbh = $pdoConnObject;
 }

 // Method: $db->dropDatabases().
 // Purpose: Instruct MySQL to drop databases.
 function dropDatabases($databases){
  // Initialize $variable to count number of drop databases that are unsuccessful.
  $numberDropDatabasesUnsuccessful = 0;
  // Iterate over databases to drop.
  foreach ($databases as $database){
   // Set SQL statement on $variable instructing MySQL to drop database.
   // NOTE: $database is untrusted data and apparently a MySQL identifier. PDO::prepare/PDOStatement::bindParam do not accept MySQL identifiers as variables and placeholders. As a result, the following SQL statement is vulnerable to SQL injection.
   $sql = "DROP DATABASE $database";
   // Call PDO::prepare() method to prepare SQL statement for execution and set return value (PDOStatement object) on $variable.
   // NOTE: Returns PDOStatement object (aka, stmt or sth = statement handle) if database server successfully prepares the statement. Returns boolean false or emits PDOException (depending on error handling) if database server cannot successfully prepare the statement.
   $sth = $this->dbh->prepare($sql);
   // Call PDOStatement::execute() method to execute prepared SQL statement and set return boolean value on $variable to indicate drop database (singular, not plural) successful.
   // NOTE:
   // Returns boolean true on success or boolean false on failure.
   // Drop database (singular, not plural) considered successful/unsuccessful if PDOStatement::execute() method successful/unsuccessful, respectively.
   $isDropDatabaseSuccessful = $sth->execute();
   // Determine if drop database (singular, not plural) successful. If successful, expression evaluates to boolean true.
   if ($isDropDatabaseSuccessful){
    // Drop database (singular, not plural) successful. Nothing to do here.
   } else {
    // Drop database (singular, not plural) unsuccessful. Increment count of number of drop databases that are unsuccessful.
    $numberDropDatabasesUnsuccessful++;
   }
  }
  // Determine if drop databases (plural, not singular) successful and set boolean value on $this->property to indicate drop databases successful.
  // NOTE: Drop databases (plural, not singular) considered successful/unsuccessful if all individual drop database (singular, not plural) considered successful. Otherwise, drop databases (plural, not singular) considered unsuccessful.
  $this->isDropDatabasesSuccessful = empty($numberDropDatabasesUnsuccessful) ? true : false;
  // Return PDOStatement object (aka, stmt or sth = statement handle).
  // NOTE: Not required/used if drop databases successful.
  return $sth;
 }

 // Method: $db->createDatabase().
 // Purpose: Instruct MySQL to create database.
 function createDatabase($database, $collation){
  // Set SQL statement with named placeholder on $variable instructing MySQL to create database.
  // NOTE: $database is untrusted data and apparently a MySQL identifier. PDO::prepare/PDOStatement::bindParam do not accept MySQL identifiers as variables and placeholders. As a result, the following SQL statement is vulnerable to SQL injection.
  $sql = "CREATE DATABASE $database COLLATE :collation";
  // Call PDO::prepare() method to prepare SQL statement for execution and set return value (PDOStatement object) on $variable.
  // NOTE: Returns PDOStatement object (aka, stmt or sth = statement handle) if database server successfully prepares the statement. Returns boolean false or emits PDOException (depending on error handling) if database server cannot successfully prepare the statement.
  $sth = $this->dbh->prepare($sql);
  // Bind parameter (aka, placeholder) to $variable and explicitly indicate data type.
  $sth->bindParam(':collation', $collation, PDO::PARAM_STR);
  // Call PDOStatement::execute() method to execute prepared SQL statement and set return boolean value on $this->property to indicate create database successful.
  // NOTE:
  // Returns boolean true on success or boolean false on failure.
  // Create database considered successful/unsuccessful if PDOStatement::execute() method successful/unsuccessful, respectively.
  $this->isCreateDatabaseSuccessful = $sth->execute();
  // Return PDOStatement object (aka, stmt or sth = statement handle).
  // NOTE: Not required/used if create database successful.
  return $sth;
 }

 // Method: $db->selectDatabaseAndCollation().
 // Purpose: Instruct MySQL to select database names and collation types.
 function selectDatabaseAndCollation(){
  // Set SQL statement on $variable instructing MySQL to select database name (schema_name) and database collation types (default_collation_name) from the database server table that stores information about databases (information_schema.schemata).
  // NOTE:
  // For additional info, see Information Schema SCHEMATA Table https://mariadb.com/kb/en/mariadb/information-schema-schemata-table/.
  // NOTE: No untrusted data (ie, no untrusted $variable or $object->property) to process with PDO::prepare/PDOStatement::bindParam. As a result, the following SQL statement is not vulnerable to SQL injection.
  $sql = "SELECT schema_name, default_collation_name FROM information_schema.schemata";
  // Call PDO::prepare() method to prepare SQL statement for execution and set return value (PDOStatement object) on $variable.
  // NOTE: Returns PDOStatement object (aka, stmt or sth = statement handle) if database server successfully prepares the statement. Returns boolean false or emits PDOException (depending on error handling) if database server cannot successfully prepare the statement.
  $sth = $this->dbh->prepare($sql);
  // Call PDOStatement::execute() method to execute prepared SQL statement and set return boolean value on $this->property to indicate select database names and collation types successful.
  // NOTE:
  // Returns boolean true on success or boolean false on failure.
  // Select database names and collation types considered successful/unsuccessful if PDOStatement::execute() method successful/unsuccessful, respectively.
  $this->isSelectDatabaseAndCollationSuccessful = $sth->execute();
  // Return PDOStatement object (aka, stmt or sth = statement handle).
  return $sth;
 }

 // Method: $db->showCollationServer().
 // Purpose: Instruct MySQL to show collation server.
 // NOTE: Collation server is the MySQL default collation.
 function showCollationServer(){
  // Set SQL statement on $variable instructing MySQL to show collation server.
  // NOTE:
  // Apparently SHOW VARIABLES LIKE 'collation_database' returns 'collation_server'; that is, unless a database is in use, at which time 'collation_database' returns the collation of the database in use. Hence, use SHOW VARIABLES LIKE 'collation_server', not SHOW VARIABLES LIKE 'collation_database'. For additional information, see:
  // 11.1.4 Connection Character Sets and Collations https://dev.mysql.com/doc/refman/5.7/en/charset-connection.html.
  // 11.1.5 Configuring Application Character Set and Collation https://dev.mysql.com/doc/refman/5.7/en/charset-applications.html.
  // 6.1.5 Server System Variables https://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html.
  // Configuring MariaDB with my.cnf https://mariadb.com/kb/en/mariadb/configuring-mariadb-with-mycnf/.
  // NOTE: No untrusted data (ie, no untrusted $variable or $object->property) to process with PDO::prepare/PDOStatement::bindParam. As a result, the following SQL statement is not vulnerable to SQL injection.
  $sql = "SHOW VARIABLES LIKE 'collation_server'";
  // Call PDO::prepare() method to prepare SQL statement for execution and set return value (PDOStatement object) on $variable.
  // NOTE: Returns PDOStatement object (aka, stmt or sth = statement handle) if database server successfully prepares the statement. Returns boolean false or emits PDOException (depending on error handling) if database server cannot successfully prepare the statement.
  $sth = $this->dbh->prepare($sql);
  // Call PDOStatement::execute() method to execute prepared SQL statement and set return boolean value on $this->property to indicate show collation server successful.
  // NOTE:
  // Returns boolean true on success or boolean false on failure.
  // Show collation server considered successful/unsuccessful if PDOStatement::execute() method successful/unsuccessful, respectively.
  $this->isShowCollationServerSuccessful = $sth->execute();
  // Return PDOStatement object (aka, stmt or sth = statement handle).
  return $sth;
 }

} // Close Databases class.
