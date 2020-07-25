<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: controllers/userAccnts.php.
 * Purpose: User Accounts page controller. Require User Accounts page class (classes/UserAccnts.class.php) and instantiate User Accounts page $uA object. Require User Accounts page view (views/userAccnts-html.php) and return User Accounts page HTML content to Index page (front) controller (index.php).
 * Used in: No other file.
 * Last reviewed/updated: 07 Apr 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) createUserAccntForm, 2.) dropUserAccntsForm, 3.) selectUserAccntForm, and 4.) userAccntPrivsForm. */

/* ---------- INSTANTIATE OBJECT ---------- */

// Require User Accounts page class and instantiate User Accounts page object passing Index page (front) controller (index.php) $index->dbh property (an object that represents a connection between PHP and MySQL) as argument.
require_once "classes/UserAccnts.class.php";
$uA = new UserAccnts($index->dbh);

/* ---------- ASSIGNMENTS ---------- */

$uA->numberUserAccntsWithEditablePrivileges = 0;

/* ---------- PERSIST/NOT PERSIST SELECTED USER ACCOUNT ---------- */

// NOTE:
// Persist/not persist selected user account section must be placed before edit user account privileges section below; otherwise, $_SESSION["selectedUserAccnt"] is not available to edit user account privileges section.
// Persist/not persist selected user account section must be placed before get selected user account privileges section below; otherwise, $_SESSION["selectedUserAccnt"] is not available to get selected user account privileges section.
// To not persist selected user account when drop selected user account, see drop user account section below.

// Determine if select user account form was submitted and user account other than blank/none was selected. If select user account form was submitted and user account other than blank/none was selected, expression evaluates to boolean true.
if ((isset($_POST["selectedUserAccnt"])) && ($_POST["selectedUserAccnt"] !== "")){
 // Select user account form was submitted and user account other than blank/none was selected. Get selected user account from $_POST superglobal variable and set on $_SESSION superglobal variable.
 // NOTE: User Accounts page class (classes/UserAccnts.class.php, this file) persists selected user account across HTTP requests, which requires web browser cookies enabled.
 $_SESSION["selectedUserAccnt"] = $_POST["selectedUserAccnt"];
 // Determine if select user account form was submitted and user account blank/none was selected. If select user account form was submitted and user account blank/none was selected, expression evaluates to boolean true.
} elseif ((isset($_POST["selectedUserAccnt"])) && ($_POST["selectedUserAccnt"] === "")){
 // Select user account form was submitted and user account blank/none was selected. Regardless if select user account form was previously submitted and user account other than no/blank user account was selected, and, therefore, regardless if selected user account persistence above is being used, stop selected user account persistence by setting $_SESSION superglobal variable to special value null.
 $_SESSION["selectedUserAccnt"] = null;
}

/* ---------- CREATE USER ACCOUNT ---------- */

// NOTE: Create user account section must be placed before user accounts table data section below; otherwise, last user account created is not available for listing in user accounts table data.

// Determine if create user account form was last form submitted. If create user account form was last form submitted, expression evaluates to boolean true.
if (isset($_POST["createUserAccntUsername"])){
 // Create user account form was last form submitted. Get user name for user account to create and set on $variable.
 $createUserAccntUsername = $_POST["createUserAccntUsername"];
 // Determine if user name field contained data. If user name field contained data, expression evaluates to boolean true.
 if ($createUserAccntUsername !== ""){
  // User name field contained data. Get password for user account to create and set on $variable.
  $createUserAccntPassword = $_POST["createUserAccntPassword"];
  // Get host name for user account to create and set on $variable.
  $createUserAccntHostname = $_POST["createUserAccntHostname"];
  // Call create user account method to instruct MySQL to create user account and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
  $sth = $uA->createUserAccnt($createUserAccntUsername, $createUserAccntPassword, $createUserAccntHostname);
  // Call html entities method to reduce XSS attack surface and set return value on $variable.
  // NOTE: Untrusted data output to HTML is vulnerable to XSS attack. $createUserAccntUsername and $createUserAccntHostname are untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
  $createUserAccntUsername = $index->htmlEntities($createUserAccntUsername);
  $createUserAccntHostname = $index->htmlEntities($createUserAccntHostname);
  // Determine if create user account host name is '' (ie, empty string). If create user account host name is '' (ie, empty string), expression evaluates to boolean true.
  // NOTE: Simple MySQL Admin | User Accounts page | create user account form | host name/IP address field empty/blank is stored in mysql.user table host column as '%' string, and is represented in user account as 'username'@'%'. Therefore, if create user account host name is '' (ie, empty string), set create user account host name to '%' string for proper display in $index->body_main_lastStatus and $uA->createUserAccntStatus below.
  if ($createUserAccntHostname === ''){
   // Create user account host name is '' (ie, empty string). Set create user account host name to '%'.
   $createUserAccntHostname = '%';
  }
  // Determine if create user account successful. If successful, expression evaluates to boolean true.
  if ($uA->isCreateUserAccntSuccessful){
   // Create user account successful. Set string on $_SESSION["variable"], $index->property, and $uA->property to report create user account name successful.
   $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->createUserAccntStatus = "<span class='good'>Good</span>. User account '$createUserAccntUsername'@'$createUserAccntHostname' successfully created.";
  } else {
   // Create user account unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
   // NOTE:
   // Returns an array of extended error information for last operation on statement handle.
   // If PDOStatement::execute() method no error, print_r($createUserAccntErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
   // If PDOStatement::execute() method error (sql syntax error), print_r($createUserAccntErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZCREATE USER 'steve1'@'localhost' IDENTIFIED BY ''' at line 1 ).
   // If PDOStatement::execute() method error (user account already created), print_r($createUserAccntErrorInfo) is: Array ( [0] => HY000 [1] => 1396 [2] => Operation CREATE USER failed for 'steve1'@'localhost' ).
   $createUserAccntErrorInfo = $sth->errorInfo();
   // Set string on $_SESSION["variable"], $index->property, and $uA->property to report create user account name unsuccessful with PDOStatement::errorInfo error code and error info.
   // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $userAccnt to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if create user account unsuccessful, $createUserAccntErrorInfo[2] is vulnerable to XSS.
   $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->createUserAccntStatus = "<span class='bad'>Bad</span>. Create user account '$createUserAccntUsername'@'$createUserAccntHostname' failed. Error code: " . $createUserAccntErrorInfo[0] . ". Error info: " . $createUserAccntErrorInfo[2] . ".";
  }
 } else {
  // User name field contained no data. Set string on $_SESSION["variable"], $index->property, and $uA->property to report create user account unsuccessful because user name not specified.
  $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->createUserAccntStatus = "<span class='warning'>Warning</span>. Create user account failed. Error: No user name specified.";
 }
} else {
// Create user account form was not last form submitted. Set string on $uA->property to report nothing to report.
// NOTE: This else reached when; 1.) Simple MySQL Admin front controller (index.php) is initially loaded, and 2.) any form other than create user account form is submitted.
 $uA->createUserAccntStatus = 'Nothing to report.';
}

/* ---------- DROP USER ACCOUNTS ---------- */

// NOTE:
// For user accounts table data, see user accounts table data section below.
// Drop user account section must be placed before user accounts table data section below; otherwise, last user accounts dropped are listed in user accounts table data.
// It is possible to move foreach from model to controller, and to call model to drop individual user accounts, not to drop array of user accounts. Perhaps do this in future. See Databases page drop databases for same.

// Determine if drop user accounts form was last form submitted and drop user account checkboxes were checked. If drop user accounts form was last form submitted and drop user account checkboxes were checked, expression evaluates to boolean true.
// NOTE: The $_POST superglobal dropUsersArray variable value is an array. The $_POST superglobal dropUsersArray variable is set if drop user account checkboxes were checked, and is not set if drop user account checkboxes were not checked. Therefore, to determine if drop user accounts form was last form submitted and drop user account checkboxes were not checked, the drop user accounts form includes a hidden input element whose name attribute value is set as a $_POST superglobal variable.
if (isset($_POST["dropUserAccntsArray"])){
 // Drop user accounts form was last form submitted and drop user account checkboxes were checked. Get name of user accounts to drop and set on $variable.
 // NOTE: If drop multiple user accounts, print_r($dropUserAccntsArray) is: Array ( [0] => 'steve1'@'localhost' [1] => 'steve2'@'localhost' [2] => 'steve3'@'localhost' ).
 $dropUserAccntsArray = $_POST["dropUserAccntsArray"];
 // Call drop user accounts method to instruct MySQL to drop user accounts and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
 $sth = $uA->dropUserAccnts($dropUserAccntsArray);
 // Convert drop user accounts array into drop user accounts string and set on $variable.
 // NOTE: For drop user accounts status report.
 $dropUserAccntsString = implode(" and ", $dropUserAccntsArray);
 // Call html entities method to reduce XSS attack surface and set return value on $variable.
 // NOTE: Untrusted data output to HTML is vulnerable to XSS attack. $dropUserAccntsString is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
 $dropUserAccntsString = $index->htmlEntities($dropUserAccntsString);
 // Determine if drop user accounts (plural, not singular) successful. If successful, expression evaluates to boolean false.
 if ($uA->isDropUserAccntsSuccessful){
  // Drop user accounts (plural, not singular) successful. Set string on $_SESSION["variable"], $index->property, and $uA property to report drop user accounts users successful.
  $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->dropUserAccntsStatus = "<span class='good'>Good</span>. User account $dropUserAccntsString successfully dropped.";
  // Iterate over drop user accounts array elements.
  foreach ($dropUserAccntsArray as $dropUserAccnt){
   // Determine if drop user account is User Accounts | edit user accounts | selected user account. If drop user account is User Accounts | edit user accounts | selected user account, expression evaluates to boolean true.
   if ($dropUserAccnt === $_SESSION["selectedUserAccnt"]){
    // Drop user account is User Accounts | edit user accounts | selected user account. Stop selected user account persistence by setting $_SESSION superglobal variable to special value null.
    $_SESSION["selectedUserAccnt"] = null;
   }
  }
 } else {
  // Drop user accounts (plural, not singular) unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
  // NOTE:
  // Returns an array of extended error information for last operation on statement handle.
  // If PDOStatement::execute() method no error, print_r($dropUserAccntErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
  // If PDOStatement::execute() method error (sql syntax error), print_r($dropUserAccntErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZDROP USER 'steve2'@'localhost'' at line 1 ).
  // If PDOStatement::execute() method error (user account already dropped), print_r($dropUserAccntErrorInfo) is: Array ( [0] => HY000 [1] => 1396 [2] => Operation DROP USER failed for 'steve1'@'localhost' ).
  $dropUserAccntErrorInfo = $sth->errorInfo();
  // Set string on $_SESSION["variable"], $index->property, and $uA->property to report drop user accounts users unsuccessful with PDOStatement::errorInfo error code and error info.
  // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $userAccnt, not $dropUserAccntsString, to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if drop user accounts unsuccessful, $dropDatabaseErrorInfo[2] is vulnerable to XSS.
  $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->dropUserAccntsStatus = "<span class='bad'>Bad</span>. Drop user account $dropUserAccntsString failed. Error code: " . $dropUserAccntErrorInfo[0] . ". Error info: " . $dropUserAccntErrorInfo[2] . ".";
 }
// Determine if drop user accounts form was last form submitted and drop user account checkboxes were not checked. If drop user accounts form was last form submitted and drop user account checkboxes were not checked, expression evaluates to boolean true.
} elseif (isset($_POST["isDropUserAccntsFormSubmitted"])){
 // Drop user accounts form was last form submitted and drop checkboxes were not checked. Set string on $_SESSION["variable"], $index->property, and $uA->property to report drop user account failed no user account to drop specified.
 $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->dropUserAccntsStatus = "<span class='warning'>Warning</span>. Drop user account failed. Error: No user account specified.";
// Drop user accounts form was not last form submitted. Set string on $uA->property to report nothing to report.
// NOTE: This else reached when; 1.) Simple MySQL Admin front controller (index.php) is initially loaded, and 2.) any form other than drop user accounts form is submitted.
} else {
 $uA->dropUserAccntsStatus = "Nothing to report.";
}

/* ---------- EDIT USER ACCOUNT PRIVILEGES ---------- */

// NOTE:
// Edit user account privileges section must be placed after persist/not persist selected user account section above; otherwise, $_SESSION["selectedUserAccnt"] is not available to edit user account privileges section.
// Untrusted data output to HTML is vulnerable to XSS attack. $uA->selectedUserAccntFromSession is untrusted data output to HTML. Typically, call $index->htmlEntities() method before if else conditional that outputs untrusted data to HTML. Reason? The single call to $index->htmlEntities() method covers both if else outcomes. Also typically, the $index->htmlEntities() method argument is solely untrusted data output to HTML. Here, however, the $index->htmlEntities() method argument ($uA->selectedUserAccntFromSession) is not only untrusted data output to HTML, but an argument to three SQL related methods below. Because $uA->selectedUserAccntFromSession is also an argument to SQL related methods, cannot call $index->htmlEntities($uA->selectedUserAccntFromSession) in a scope that alters the $uA->selectedUserAccntFromSession value as passed to the SQL related methods. For the upstream calls to $index->htmlEntities(), this can require calling $index->htmlEntities() within, not before, the if else conditional. Alternatively, if the untrusted data output to HTML is commented out as being unneeded, the call to $index->htmlEntities() can be commented out, omitted, or otherwise noted as being unneeded.

// Overview: In User Accounts page | edit user account privileges, when select a user account, the selected user account privileges are displayed for editing in the user account privileges form.
// Sections (when click save user account privileges button, the following occurs): 1.) Get edited user account privileges via $_POST superglobal (no method call), 2.) Revoke all privileges (call $db->revokeAllPrivs() method), 3.) Revoke grant option (call $db->revokeGrantOption() method), 4.) Process privileges cvs string (no method call), and 5.) Grant privileges (call $uA->grant() method).
// User account privileges form radio button and checkbox hierarchy nomenclature:
// Supercategory radio buttons = all privileges and usage radio buttons. (Top of hierarchy.)
// Category checkboxes = data, structure, and administration checkboxes. (Middle of hierarchy.)
// Subcategory checkboxes = those under data, structure, OR administration. For example, data subcategory checkboxes are SELECT through FILE.
// Item checkboxes = those under data, structure, AND administration. Item checkboxes are all from SELECT through CREATE USER. 28 total item checkboxes. (Bottom of hierarchy.)
// All category radio buttons/checkboxes = supercategory radio buttons and category checkboxes.
// All checkboxes = all category checkboxes and all item checkboxes, not supercategory radio buttons.
// Get selected user account privileges section must be placed before user accounts table data section below; otherwise, saved user account privileges are not available for listing in user accounts table data.

// 1.) Get edited user account privileges via $_POST superglobal (no method call).
// Determine if user account privileges form was last form submitted and select user account form was previously submitted and user account other than blank/none was selected. If user account privileges form was last form submitted and select user account form was previously submitted and user account other than blank/none was selected, expression evaluates to boolean true.
// NOTE:
// $_POST superglobal supercategoryRadioBtnPrivsArray and itemCheckboxPrivsArray variable values are arrays. The $_POST superglobal supercategoryRadioBtnPrivsArray and itemCheckboxPrivsArray variables are set if a supercategory radio button or item checkboxes, respectively, were checked, and are not set if a supercategory radio button or item checkboxes, respectively, were not checked. Therefore, to determine if user account privileges form was last form submitted and a supercategory radio button or item checkboxes were not checked, the user account privileges form includes a hidden input element whose name attribute value is set as a $_POST superglobal variable.
// $_SESSION superglobal selected user account variable set in User Accounts page class (UserAccnts.class.php).
if ((isset($_POST["isUserAccntPrivsFormSubmitted"])) && (isset($_SESSION["selectedUserAccnt"]))){
 // User account privileges form was last form submitted and select user account form was previously submitted and user account other than blank/none was selected. Get selected user account from $_SESSION superglobal selected user account variable and set on $uA->property.
 // NOTE: $_SESSION superglobal selected user account variable set in persist/not persist selected user account section above.
 $uA->selectedUserAccntFromSession = $_SESSION["selectedUserAccnt"];
 // Determine if a supercategory radio button was checked. If supercategory radio button was checked, expression evaluates to boolean true.
 if (isset($_POST["supercategoryRadioBtnPrivsArray"])){
  // A supercategory radio button was checked. Get supercategory radio button value attribute value and set on $variable.
  // NOTE:
  // All privileges checked print_r($editedUserAccntPrivsArray) is: Array ( [0] => all privileges ).
  // Usage checked print_r($editedUserAccntPrivsArray) is: Array ( [0] => usage ).
  $editedUserAccntPrivsArray = $_POST["supercategoryRadioBtnPrivsArray"];
 } else {
  // A supercategory radio button (ie, all (privileges or usage) was not checked. Item checkboxes were checked. Get item checkbox value attribute values and set on $variable.
  // NOTE: Data category checked print_r($editedUserAccntPrivsArray) is: Array ( [0] => select [1] => insert [2] => update [3] => delete [4] => file ).
  $editedUserAccntPrivsArray = $_POST["itemCheckboxPrivsArray"];
  // Determine if all item checkboxes checked except grant. If all item checkboxes check except grant, expression evaluates to boolean true.
  if ((count($editedUserAccntPrivsArray) === 27) && (!in_array("grant", $editedUserAccntPrivsArray))){
   // All item checkboxes checked except grant. Set Array ( [0] => all privileges ) on $variable to override previous value.
   $editedUserAccntPrivsArray = array("all privileges");
   // Set boolean value on $uA->property to indicate edited user account privileges CSV string does not include "GRANT" substring.
   $uA->isEditedUserAccntPrivsIncludeGrant = false;
  }
 }
 // Convert privileges array to uppercase comma separated values (CSV) string and set on $uA->property.
 $uA->editedUserAccntPrivsCsv = strtoupper(implode(", ", $editedUserAccntPrivsArray));
 // 2.) Revoke all privileges (call $db->revokeAllPrivs() method).
 // Call revoke all privileges method to instruct MySQL to revoke all privileges on all databases.tables from selected user account and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
 $sth_revokeAllPrivs = $uA->revokeAllPrivs($uA->selectedUserAccntFromSession);
 // NOTE: Do not call html entities method to reduce XSS attack surface before if else conditional that outputs untrusted data to HTML. For additional information why do not call html entities method here (alters value of SQL method argument), see note near top of this section.
 // Determine if revoke all privileges successful. If successful, expression evaluates to boolean false.
 if ($uA->isRevokeAllPrivsSuccessful){
  // Revoke all privileges successful.
  // NOTE: Do not call html entities method to reduce XSS attack surface within if else conditional that outputs untrusted data to HTML. For additional information why do not call html entities method here (output to HTML commented out), see note near top of this section.
  // Set string on $index->property and $uA->property to report user account revoke all privileges successful.
  // COMMENT OUT: $index->body_main_lastStatus = $uA->editStatus = "<span class='good'>Good</span>. User account $uA->selectedUserAccntFromSession privileges successfully revoked.";
  // 3.) Revoke grant option (call $db->revokeGrantOption() method).
  // Call revoke grant option method to instruct MySQL to revoke grant option on all databases.tables from selected user account and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
  $sth_revokeGrantOption = $uA->revokeGrantOption($uA->selectedUserAccntFromSession);
  // NOTE: Do not call html entities method to reduce XSS attack surface before if else conditional that outputs untrusted data to HTML. For additional information why do not call html entities method here (alters value of SQL method argument), see note near top of this section.
  // Determine if revoke grant option successful. If successful, expression evaluates to boolean false.
  if ($uA->isRevokeGrantOptionSuccessful){
   // Revoke grant option successful.
   // NOTE: Do not call html entities method to reduce XSS attack surface within if else conditional that outputs untrusted data to HTML. For additional information why do not call html entities method here (output to HTML commented out), see note near top of this section.
   // Set string on $index->property and $uA->property to report user account revoke grant option successful.
   // COMMENT OUT: $index->body_main_lastStatus = $uA->editStatus = "<span class='good'>Good</span>. User account $uA->selectedUserAccntFromSession grant option successfully revoked.";
   // 4.) Process privileges cvs string (no method call).
   // Determine if edited user account privileges CSV string includes "GRANT" substring. If edited user account privileges CSV string includes "GRANT" substring, strpos() function returns integer indicating position (zero based) of substring in string. If edited user account privileges CSV string does not include "GRANT" substring, strpos() function returns boolean false.
   // NOTE:
   // Granting the grant privilege has it own special syntax (WITH GRANT OPTION, not GRANT GRANT). If GRANT is present in edited user account privileges CSV string, query will fail. Therefore, must remove GRANT from edited user account privileges CSV string, which is done here.
   // Because strpos() function return value position 0 evaluates to boolean false, do not evaluate strpos() return value itself. Instead, compare strpos() return value against boolean (ie, !==/=== boolean). For additional info, see http://php.net/manual/en/function.strpos.php.
   // strpos() function is faster than strstr() function. For additional info, see http://php.net/manual/en/function.strstr.php.
   if (strpos($uA->editedUserAccntPrivsCsv, "GRANT") !== false){
    // Edited user account privileges CSV string includes "GRANT" substring. Set boolean value on $uA->property to indicate edited user account privileges CSV string includes "GRANT" substring.
    $uA->isEditedUserAccntPrivsIncludeGrant = true;
    // Determine location of "GRANT" substring in edited user account privileges CSV string (which can be at beginning of, inside of, at end of, or only of) and then remove "GRANT" substring from edited user account privileges CSV string. Determine if edited user account privileges CSV string includes "GRANT, " substring, which means "GRANT" is at beginning of edited user account privileges CSV string. If includes "GRANT, " substring, strpos() function returns integer indicating position (zero based) of substring in string. If does not include "GRANT, " substring, strpos() function returns boolean false.
    // NOTE:
    // Because strpos() function return value position 0 evaluates to boolean false, do not evaluate strpos() return value itself. Instead, compare strpos() return value against boolean (ie, !==/=== boolean). For additional info, see http://php.net/manual/en/function.strpos.php.
    // strpos() function is faster than strstr() function. For additional info, see http://php.net/manual/en/function.strstr.php.
    if (strpos($uA->editedUserAccntPrivsCsv, "GRANT, ") !== false){
     // Edited user account privileges CSV string includes "GRANT, " substring. Remove "GRANT" substring from edited user account privileges CSV string and reset edited user account privileges CSV string on $uA->property.
     $uA->editedUserAccntPrivsCsv = str_replace("GRANT, ", "", $uA->editedUserAccntPrivsCsv);
    // Determine if edited user account privileges CSV string includes " GRANT," substring, which means "GRANT" is inside of edited user account privileges CSV string. If edited user account privileges CSV string includes " GRANT," substring, strpos() function returns integer indicating position (zero based) of substring in string. If edited user account privileges CSV string does not include " GRANT," substring, strpos() function returns boolean false.
    } elseif (strpos($uA->editedUserAccntPrivsCsv, " GRANT,") !== false){
     // Edited user account privileges CSV string includes " GRANT," substring. Remove "GRANT" substring from edited user account privileges CSV string and reset edited user account privileges CSV string on $uA->property.
     $uA->editedUserAccntPrivsCsv = str_replace(" GRANT,", "", $uA->editedUserAccntPrivsCsv);
    // Determine if edited user account privileges CSV string includes ", GRANT" substring, which means "GRANT" is at end of edited user account privileges CSV string. If edited user account privileges CSV string includes ", GRANT" substring, strpos() function returns integer indicating position (zero based) of substring in string. If edited user account privileges CSV string does not include ", GRANT" substring, strpos() function returns boolean false.
    } elseif (strpos($uA->editedUserAccntPrivsCsv, ", GRANT") !== false){
     // Edited user account privileges CSV string includes ", GRANT" substring. Remove "GRANT" substring from edited user account privileges CSV string and reset edited user account privileges CSV string on $uA->property.
     $uA->editedUserAccntPrivsCsv = str_replace(", GRANT", "", $uA->editedUserAccntPrivsCsv);
    } else {
     // "GRANT" substring is only of edited user account privileges CSV string. Set edited user account privileges CSV string to "USAGE".
     // NOTE: If GRANT is only privilege, query is "GRANT USAGE ON ... REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR..." Hence, set edited user account privileges CSV string to "USAGE".
     $uA->editedUserAccntPrivsCsv = "USAGE";
    }
   }
   // 5.) Grant privileges (call $uA->grant() method).
   // Call grant method to instruct MySQL to grant edited user account privileges on all databases.tables to selected user account including default grants for global privileges not listed in user account privileges form and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
   $sth_grant = $uA->grant($uA->selectedUserAccntFromSession);
   // Call html entities method to reduce XSS attack surface and set return value on $variable.
   // NOTE: Untrusted data output to HTML is vulnerable to XSS attack. $uA->selectedUserAccntFromSession is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
   $uA->selectedUserAccntFromSession = $index->htmlEntities($uA->selectedUserAccntFromSession);
   // Determine if grant successful. If successful, expression evaluates to boolean false.
   if ($uA->isGrantSuccessful){
    // Grant successful. Set string on $_SESSION["variable"], $index->property, and $uA->property to report user account grant privileges successful.
    // NOTE: Called $index->htmlEntities($uA->selectedUserAccntFromSession) to reduce XSS attack surface above.
    $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->editStatus = "<span class='good'>Good</span>. User account $uA->selectedUserAccntFromSession privileges successfully edited.";
   } else {
    // Grant unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
    // NOTE:
    // Returns an array of extended error information for last operation on statement handle.
    // If PDOStatement::execute() method no error, print_r($grantErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
    // If PDOStatement::execute() method error (sql syntax error), print_r($grantErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZGRANT USAGE ON *.* TO 'steve1'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_H' at line 1 ).
    $grantErrorInfo = $sth_grant->errorInfo();
    // Set string on $_SESSION["variable"], $index->property, and $uA->property to report user account grant privileges unsuccessful with PDOStatement::errorInfo error code and error info.
    // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $uA->selectedUserAccntFromSession to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if grant unsuccessful, $grantErrorInfo[2] is vulnerable to XSS.
    $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->editStatus = "<span class='bad'>Bad</span>. Grant user account $uA->selectedUserAccntFromSession privileges failed. Error code: " . $grantErrorInfo[0] . ". Error info: " . $grantErrorInfo[2] . ".";
   }
  } else {
   // Revoke grant option unsuccessful.
   // Call html entities method to reduce XSS attack surface and set return value on $variable.
   // NOTE: Untrusted data output to HTML is vulnerable to XSS attack. $uA->selectedUserAccntFromSession is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
   // Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
   // NOTE:
   // Returns an array of extended error information for last operation on statement handle.
   // If PDOStatement::execute() method no error, print_r($revokeGrantOptionErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
   // If PDOStatement::execute() method error (sql syntax error), print_r($revokeGrantOptionErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZREVOKE GRANT OPTION ON *.* FROM 'steve1'@'localhost'' at line 1 ).
   $revokeGrantOptionErrorInfo = $sth_revokeGrantOption->errorInfo();
   // Set string on $_SESSION["variable"], $index->property, and $uA->property to report revoke grant option unsuccessful with PDOStatement::errorInfo error code and error info.
   // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $uA->selectedUserAccntFromSession to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if revoke grant option unsuccessful, $revokeGrantOptionErrorInfo[2] is vulnerable to XSS.
   $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->editStatus = "<span class='bad'>Bad</span>. Revoke user account $uA->selectedUserAccntFromSession grant option failed. Error code: " . $revokeGrantOptionErrorInfo[0] . ". Error info: " . $revokeGrantOptionErrorInfo[2] . ".";
  }
 } else {
  // Revoke all privileges unsuccessful.
  // Call html entities method to reduce XSS attack surface and set return value on $variable.
  // NOTE: Untrusted data output to HTML is vulnerable to XSS attack. $uA->selectedUserAccntFromSession is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
  // Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
  // NOTE:
  // Returns an array of extended error information for last operation on statement handle.
  // If PDOStatement::execute() method no error, print_r($revokeAllPrivsErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
  // If PDOStatement::execute() method error (sql syntax error), print_r($revokeAllPrivsErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZREVOKE ALL PRIVILEGES ON *.* FROM 'steve1'@'localhost'' at line 1 ).
  $revokeAllPrivsErrorInfo = $sth_revokeAllPrivs->errorInfo();
  // Set string on $_SESSION["variable"], $index->property, and $uA->property to report user account revoke all privileges unsuccessful with PDOStatement::errorInfo error code and error info.
  // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $uA->selectedUserAccntFromSession to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if revoke all privileges unsuccessful, $revokeAllPrivsErrorInfo[2] is vulnerable to XSS.
  $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->editStatus = "<span class='bad'>Bad</span>. Revoke user account $uA->selectedUserAccntFromSession privileges failed. Error code: " . $revokeAllPrivsErrorInfo[0] . ". Error info: " . $revokeAllPrivsErrorInfo[2] . ".";
 }
// Determine if user account privileges form was last form submitted and either select user account form was not previously submitted or select user account form was previously submitted and user account blank/none was selected. If user account privileges form was last form submitted and either select user account form was not previously submitted or select user account form was previously submitted and user account blank/none was selected, expression evaluates to boolean true.
} elseif ((isset($_POST["isUserAccntPrivsFormSubmitted"])) && (!isset($_SESSION["selectedUserAccnt"]))){
 // User account privileges form was last form submitted and either select user account form was not previously submitted or select user account form was previously submitted and user account blank/none was selected. Set string on $_SESSION["variable"], $index->property, and $uA->property to report edit user account privileges failed because no user account selected.
 $_SESSION["lastStatus"] = $index->body_main_lastStatus = $uA->editStatus = "<span class='warning'>Warning</span>. Edit user account privileges failed. Error: No user account selected.";
// User account privileges form was not last form submitted. Set string on $uA->property to report nothing to report.
// NOTE: This else reached when; 1.) Simple MySQL Admin front controller (index.php) is initially loaded, and 2.) any form other than user account privileges form is submitted.
} else {
 $uA->editStatus = "Nothing to report.";
}

/* ---------- USER ACCOUNTS TABLE DATA ---------- */

// Overview: Get data to display in drop user accounts form and generate data for use in get selected user account privileges section below.
// Sections: 1.) Select user accounts user, host, and password (call $uA->selectUserHostPassword() method), 2.) Show user account grants (call $uA->showGrants() method), and 3.) Generate $uA->userAccntsPrivsCsv for get selected user account privileges section below (no method call).
// For drop user accounts, see drop user accounts section above.
// User accounts table data section must be placed after create user accounts section above; otherwise, last user created is not available for listing in user accounts table data.
// User accounts table data section must be placed after drop user accounts section above; otherwise, last user accounts dropped are listed in user accounts table data.
// User accounts table data section must be placed after edit user account privileges section above; otherwise, saved user account privileges are not available for listing in user accounts table data.
// User accounts table data section must be placed before get selected user account privileges section below; otherwise, $uA->userAccntsPrivsCsv is not available for use in get selected user account privileges.
// User accounts table data section automatically runs when User Accounts page controller (controllers/userAccnts.php, this page) is loaded.

// 1.) Select user accounts user, host, and password (call $uA->selectUserHostPassword() method).
// Call select user host password method to instruct MySQL to select user accounts user, host, and password and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
$sth_selectUserHostPassword = $uA->selectUserHostPassword();
// Determine if select user accounts user, host, and password successful. If successful, expression evaluates to boolean true.
if ($uA->isSelectUserHostPasswordSuccessful){
 // Select user accounts user, host, and password successful. Set string on $index->property to report select user accounts user, host, and password successful.
 // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. User accounts user, host, and password successfully selected.";
 // Call PDOStatement::fetchAll(PDO::FETCH_NUM) method to get all of the result set rows and set return value on $variable.
 // NOTE:
 // Returns an array containing all of the result set rows or boolean false on failure. An empty array is returned if there are zero results to fetch.
 // Both $sth->fetchAll(PDO::FETCH_NUM) and $sth_selectUserHostPassword->fetchAll() work with User Accounts page view (views/userAccnts-html.php) code as previously written 17Apr17 using mysqli/mysqli_result object, not PDO/PDOStatement object.
 // XAMPP 5.6.24-1 plus 'steve1'@'localhost' (using password: YES), if $sth_selectUserHostPassword->fetchAll(PDO::FETCH_NUM), print_r($fetchUserHostPassword) is: Array ( [0] => Array ( [0] => [1] => localhost [2] => ) [1] => Array ( [0] => pma [1] => localhost [2] => ) [2] => Array ( [0] => root [1] => localhost [2] => ) [3] => Array ( [0] => root [1] => 127.0.0.1 [2] => ) [4] => Array ( [0] => root [1] => ::1 [2] => ) [5] => Array ( [0] => steve1 [1] => localhost [2] => *B45AF0033B731B4C37F7E40E98A5F8198930B37C ) ).
 // XAMPP 5.6.24-1 plus 'steve1'@'localhost' (using password: YES), if $sth_selectUserHostPassword->fetchAll(), print_r($fetchUserHostPassword) is: Array ( [0] => Array ( [user] => [0] => [host] => localhost [1] => localhost [password] => [2] => ) [1] => Array ( [user] => pma [0] => pma [host] => localhost [1] => localhost [password] => [2] => ) [2] => Array ( [user] => root [0] => root [host] => localhost [1] => localhost [password] => [2] => ) [3] => Array ( [user] => root [0] => root [host] => 127.0.0.1 [1] => 127.0.0.1 [password] => [2] => ) [4] => Array ( [user] => root [0] => root [host] => ::1 [1] => ::1 [password] => [2] => ) [5] => Array ( [user] => steve1 [0] => steve1 [host] => localhost [1] => localhost [password] => *B45AF0033B731B4C37F7E40E98A5F8198930B37C [2] => *B45AF0033B731B4C37F7E40E98A5F8198930B37C ) ).
 $fetchUserHostPassword = $sth_selectUserHostPassword->fetchAll(PDO::FETCH_NUM);
 // Determine if fetch user accounts user, host, and password successful. If successful, expression evaluates to boolean true.
 if ($fetchUserHostPassword !== false){
  // Fetch user accounts user, host, and password successful. Set string on $index->property to report fetch user accounts user, host, and password successful.
  // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. User accounts user, host, and password successfully fetched.";
  // Iterate over array elements.
  foreach ($fetchUserHostPassword as list($user, $host, $password)){
   // Get user account user (string) and host (string) and set string representing user account on $variable.
   $userAccnt = "'$user'@'$host'";
   // 2.) Show user account grants (call $uA->showGrants() method).
   // Call show grants method to instruct MySQL to show user account grants and set return value (PDOStatement object (aka, stmt or sth = statement handle)) on $variable.
   $sth_showGrants = $uA->showGrants($userAccnt);
   // Determine if show user account grants successful. If successful, expression evaluates to boolean true.
   if ($uA->isShowGrantsSuccessful){
    // Show user account grants successful. Set string on $index->property to report show user account grants successful.
    // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. User account $userAccnt grants successfully shown.";
    // Call PDOStatement::fetchColumn(0) method to get single column from next row of result set and set return value on $variable.
    // NOTE:
    // Returns string representing single column from next row of result set or boolean false if there are no more rows.
    // XAMPP 5.6.24-1 ''@'localhost', if $sth_showGrants->fetchColumn(0), print_r($fetchGrants) is: GRANT USAGE ON *.* TO ''@'localhost'.
    // XAMPP 5.6.24-1 'root'@'localhost', if $sth_showGrants->fetchColumn(0), print_r($fetchGrants) is: GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION.
    // XAMPP 5.6.24-1 'steve1'@'localhost' (using password: YES) plus assigned Data category of privileges via phpMyAdmin, if $sth_showGrants->fetchColumn(0), print_r($fetchGrants) is: GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'steve1'@'localhost' IDENTIFIED BY PASSWORD '*B45AF0033B731B4C37F7E40E98A5F8198930B37C'.
    // XAMPP 5.6.24-1 ''@'localhost', if $sth_showGrants->fetchAll(), print_r($fetchGrants) is (an array with one element which is an array): Array ( [0] => Array ( [Grants for @localhost] => GRANT USAGE ON *.* TO ''@'localhost' [0] => GRANT USAGE ON *.* TO ''@'localhost' ) ).
    // XAMPP 5.6.24-1 'root'@'localhost', if $sth_showGrants->fetchAll(), print_r($fetchGrants) is (an array with two elements both of which are arrays): Array ( [0] => Array ( [Grants for root@localhost] => GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION [0] => GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION ) [1] => Array ( [Grants for root@localhost] => GRANT PROXY ON ''@'%' TO 'root'@'localhost' WITH GRANT OPTION [0] => GRANT PROXY ON ''@'%' TO 'root'@'localhost' WITH GRANT OPTION ) ).
    // XAMPP 5.6.24-1 'steve1'@'localhost' (using password: YES) plus assigned Data category of privileges via phpMyAdmin, if $sth_showGrants->fetchAll(), print_r($fetchGrants) is (an array with one element which is an array): Array ( [0] => Array ( [Grants for steve1@localhost] => GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'steve1'@'localhost' IDENTIFIED BY PASSWORD '*B45AF0033B731B4C37F7E40E98A5F8198930B37C' [0] => GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'steve1'@'localhost' IDENTIFIED BY PASSWORD '*B45AF0033B731B4C37F7E40E98A5F8198930B37C' ) ).
    $fetchGrants = $sth_showGrants->fetchColumn(0);
    // Determine if fetch user account grants successful. If successful, expression evaluates to boolean true.
    if ($fetchGrants !== false){
     // Fetch user account grants successful. Set string on $index->property to report fetch user account grants successful.
     // COMMENT OUT: $index->body_main_lastStatus = "<span class='good'>Good</span>. User account $userAccnt grants successfully fetched.";
     // Determine if grants string includes "WITH GRANT OPTION" substring and set return value on $variable. If grants string includes "WITH GRANT OPTION" substring, strpos() function returns integer indicating position (zero based) of substring in string. If grants string does not include "WITH GRANT OPTION" substring, strpos() function returns boolean false.
     $grantsStringWithGrantOptionPos = strpos($fetchGrants, "WITH GRANT OPTION");
     // Determine if user account privileges does not include GRANT privilege. If user account privileges does not include GRANT privilege, expression evaluates to boolean true.
     // NOTE: Because strpos() function return value position 0 evaluates to boolean false, do not evaluate strpos() return value itself. Instead, compare strpos() return value against boolean (ie, !==/=== boolean). For additional info, see http://php.net/manual/en/function.strpos.php.
     // NOTE: strpos() function is faster than strstr() function. For additional info, see http://php.net/manual/en/function.strstr.php.
     if ($grantsStringWithGrantOptionPos === false){
      // User account privileges does not include GRANT privilege. Set string on $variable to indicate user account privileges does not include GRANT privilege.
      $grantString = "";
     } else {
      // User account privileges includes GRANT privilege. Set string on $variable to indicate user account privileges includes GRANT privilege.
      $grantString = "GRANT";
     }
     // From grants string, get substring before ' TO' and set on $variable.
     $grantsSubstringOneBeforeTo = strstr($fetchGrants, " TO", true);
     // From grants substring one before to, get substring after 'GRANT ' (zero based) and set on $variable.
     $grantsSubstringTwoAfterGrant = substr($grantsSubstringOneBeforeTo, 5);
     // From grants substring two after grant, split by ' ON ' into an array of two substrings and set on $variable.
     // NOTE: First substring represents user account privileges. Second substring represents (the user account privileges are) on databases.tables.
     $userAccntPrivsAndOnArray = explode(" ON ", $grantsSubstringTwoAfterGrant);
     // Generate array containing user accounts table data and set on $uA->property.
     // NOTE: User account user ($user), host ($host), userAccnt ($userAccnt), password ($password) are derived from select user host password method above. User account privileges ($userAccntPrivsAndOnArray[0]), grant ($grantString), and on databases.tables ($userAccntPrivsAndOnArray[1]) are derived from show grants method above.
     $uA->userAccntsTableDataArray[] = array($user, $host, $userAccnt, $password, $userAccntPrivsAndOnArray[0], $grantString, $userAccntPrivsAndOnArray[1]);
     // 3.) Generate $uA->userAccntsPrivsCsv for get selected user account privileges section below (no method call).
     // Get user account privileges from user account privileges and on databases.tables array and set on $uA->property.
     $userAccntPrivs = $userAccntPrivsAndOnArray[0];
     // Concatenate user account privileges and user account grant string into single comma separated values (CSV) string and set on $variable.
     $userAccntPrivsCsv = "$userAccntPrivs";
     if ($grantString !== ""){
      $userAccntPrivsCsv .= ", $grantString";
     }
     // Set user account as key, and set user account privileges and grant CSV as value in array of key-value pairs on $uA->property.
     // NOTE: $uA->userAccntsPrivsCsv not used in this section. Created for get selected user account privileges section below.
     $uA->userAccntsPrivsCsv[$userAccnt] = $userAccntPrivsCsv;
    } else {
     // Fetch user account grants unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::fetchColumn() method error info and set return value on $variable.
     // NOTE:
     // Returns an array of extended error information for last operation on statement handle.
     // If PDOStatement::fetchColumn() method no error, print_r($fetchGrantsErrorInfo) is: ??? not determined.
     // If PDOStatement::fetchColumn() method error, print_r($fetchGrantsErrorInfo) is: ??? not determined.
     // COMMENT OUT: $fetchGrantsErrorInfo = $sth_showGrants->errorInfo();
     // Set string on $index->property to report fetch user account grants unsuccessful with PDOStatement::errorInfo error code and error info.
     // NOTE: PDOStatement::errorInfo[2] ??? outputs untrusted data ??? to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if fetch user account grants unsuccessful, $fetchGrantsErrorInfo[2] is ??? vulnerable to XSS.
     // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Fetch user account $userAccnt grants failed. Error code: " . $fetchGrantsErrorInfo[0] . ". Error info: " . $fetchGrantsErrorInfo[2] . ".";
    }
   } else {
    // Show user account grants unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
    // NOTE:
    // Returns an array of extended error information for last operation on statement handle.
    // If PDOStatement::execute() method no error, print_r($showGrantsErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
    // If PDOStatement::execute() method error for 'root'@'localhost' user account (SQL syntax error), print_r($showGrantsErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZSHOW GRANTS FOR 'root'@'localhost'' at line 1 ).
    // COMMENT OUT: $showGrantsErrorInfo = $sth_showGrants->errorInfo();
    // Set string on $index->property to report show grants for user account unsuccessful with PDOStatement::errorInfo error code and error info.
    // NOTE: PDOStatement::errorInfo[2] outputs untrusted data $userAccnt to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if show user account grants unsuccessful, $showGrantsErrorInfo[2] is vulnerable to XSS.
    // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Show user account $userAccnt grants failed. Error code: " . $showGrantsErrorInfo[0] . ". Error info: " . $showGrantsErrorInfo[2] . ".";
   }
  } // Close foreach.
 } else {
  // Fetch user accounts user, host, and password unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::fetchAll() method error info and set return value on $variable.
  // NOTE:
  // Returns an array of extended error information for last operation on statement handle.
  // If PDOStatement::fetchAll() method no error, print_r($fetchUserHostPasswordErrorInfo) is: ??? not determined.
  // If PDOStatement::fetchAll() method error, print_r($fetchUserHostPasswordErrorInfo) is: ??? not determined.
  // COMMENT OUT: $fetchUserHostPasswordErrorInfo = $sth->errorInfo();
  // Set string on $index->property to report fetch user accounts user, host, and password unsuccessful with PDOStatement::errorInfo error code and error info.
  // NOTE: PDOStatement::errorInfo[2] ??? outputs untrusted data ??? to HTML without opportunity to pass through $index->htmlEntities() method. Therefore, if fetch user accounts user, host, and password unsuccessful, $fetchUserHostPasswordErrorInfo[2] is ??? vulnerable to XSS.
  // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Fetch user accounts user, host, and password failed. Error code: " . $fetchUserHostPasswordErrorInfo[0] . ". Error info: " . $fetchUserHostPasswordErrorInfo[2] . ".";
 }
} else {
 // Select user accounts user, host, and password unsuccessful. Call PDOStatement::errorInfo() method to get PDOStatement::execute() method error info and set return value on $variable.
 // NOTE:
 // Returns an array of extended error information for last operation on statement handle.
 // If PDOStatement::execute() method no error, print_r($selectUserHostPasswordErrorInfo) is: Array ( [0] => 00000 [1] => [2] => ).
 // If PDOStatement::execute() method error (SQL syntax error), print_r($selectUserHostPasswordErrorInfo) is: Array ( [0] => 42000 [1] => 1064 [2] => You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'ZZSELECT user, host, password FROM mysql.user ORDER BY user' at line 1 ).
 $selectUserHostPasswordErrorInfo = $sth->errorInfo();
 // Set string on $index->property to report select user accounts user, host, and password unsuccessful with PDOStatement::errorInfo error code and error info.
 // NOTE: PDOStatement::errorInfo[2] does not output untrusted data to HTML. Therefore, if select user accounts user, host, and password unsuccessful, $selectUserHostPasswordErrorInfo[2] is not vulnerable to XSS.
 // COMMENT OUT: $index->body_main_lastStatus = "<span class='bad'>Bad</span>. Select user accounts user, host, and password failed. Error code: " . $selectUserHostPasswordErrorInfo[0] . ". Error info: " . $selectUserHostPasswordErrorInfo[2] . ".";
}

/* ---------- GET SELECTED USER ACCOUNT PRIVILEGES ---------- */

// NOTE:
// Get selected user account privileges section must be placed after persist/not persist selected user account section above; otherwise, $_SESSION["selectedUserAccnt"] is not available to get selected user account privileges section.
// Get selected user account privileges section must be placed after user accounts table data section above; otherwise, $uA->userAccntsPrivsCsv is not available to get selected user account privileges section.

// Determine if select user account form was submitted and user account other than blank/none was selected. If select user account form was submitted and user account other than blank/none was selected, expression evaluates to boolean true.
// NOTE: $_SESSION superglobal selected user account variable set in persist/not persist selected user account section above and drop user accounts section above.
if (isset($_SESSION["selectedUserAccnt"])){
 // Select user account form was submitted and a user account other than blank/none was selected. Get selected user account and set on $variable.
 $selectedUserAccnt = $_SESSION["selectedUserAccnt"];
 // Get selected user account privileges CSV and set on $uA->property.
 // NOTE:
 // $uA->userAccntsPrivsCsv from user accounts table data section above.
 // $uA->privsCsv property value set on JavaScript EditUserAccntPrivsUtil.privsCsv property in Index page (front) controller (index.php).
 $uA->privsCsv = trim($uA->userAccntsPrivsCsv[$selectedUserAccnt]);
 // Call html entities method to reduce XSS attack surface and set return value on $variable.
 // NOTE: Untrusted data output to HTML is vulnerable to XSS attack. $selectedUserAccnt is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
 // COMMENT OUT: $selectedUserAccnt = $index->htmlEntities($selectedUserAccnt);
 // Set string on $index->property and $uA->property to report selected user account name privileges are shown for editing.
 // COMMENT OUT: $index->body_main_lastStatus = $uA->editStatus = "<span class='good'>Good</span>. User account $selectedUserAccnt privileges are shown below for editing.";
}

/* ---------- IS LAST FORM SUBMITTED AN EDIT USER ACCOUNT PRIVILEGES FIELDSET FORM ---------- */

// Determine if last form submitted was an edit user account privileges fieldset form. If last form submitted was an edit user account privileges fieldset form, expression evaluates to boolean true.
if ((isset($_POST["selectedUserAccnt"])) || (isset($_POST["isUserAccntPrivsFormSubmitted"]))){
 // Last form submitted was an edit user account privileges fieldset form. Set string value on $uA->property to indicate last form submitted was an edit user account privileges fieldset form.
 // NOTE:
 // $uA->isEditUserAccntPrivsFieldsetForm property value set on JavaScript EditUserAccntPrivsUtil.isEditUserAccntPrivsFieldsetForm property in Index page (front) controller (index.php).
 // Yes, use string "true", not boolean true. In Index page (front) controller (index.php), PHP evaluates $uA->isEditUserAccntPrivsFieldsetForm = true as 1, not as boolean true.
 $uA->isEditUserAccntPrivsFieldsetForm = "true";
}

/* ---------- REQUIRE VIEW AND RETURN HTML CONTENT TO INDEX PAGE (FRONT) CONTROLLER ---------- */

// Require User Accounts page view and return User Accounts page HTML content to Index page (front) controller (index.php).
$html = require_once "views/userAccnts-html.php";
return $html;
