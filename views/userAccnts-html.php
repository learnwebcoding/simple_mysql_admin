<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: views/userAccnts-html.php.
 * Purpose: User Accounts page view.
 * Used in: controllers/userAccnts.php.
 * Last reviewed/updated: 06 Apr 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) createUserAccntForm, 2.) dropUserAccntsForm, 3.) selectUserAccntForm, and 4.) userAccntPrivsForm. */

/* ---------- USER ACCOUNTS TABLE DATA ---------- */

// Determine if user accounts table data array is not empty. If not empty, expression evaluates to boolean true.
if (!empty($uA->userAccntsTableDataArray)){
// User accounts table data array is not empty. Iterate over array elements.
 foreach ($uA->userAccntsTableDataArray as list($user, $host, $userAccnt, $password, $privileges, $grant, $on)){
  // Call html entities method to reduce XSS attack surface and set return value on $variable.
  // NOTE: Untrusted data output to HTML is vulnerable to XSS attack. $user, $host, and $userAccnt are untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
  $user = $index->htmlEntities($user);
  $host = $index->htmlEntities($host);
  $userAccnt = $index->htmlEntities($userAccnt);
  $uA->userAccntsTableDataHtml .= "<tr>\n";
  // Drop column.
  // NOTE: rsvd indicates user account reserved for MySQL/phpMyAdmin administration. Simple MySQL Admin does not allow dropping.
  if (($user === "mysql.session") || ($user === "mysql.sys") || ($user === "pma") || ($user === "root")){
   $uA->userAccntsTableDataHtml .= "<td class='text-align-center'>rsvd</td>\n";
  } else {
   $uA->userAccntsTableDataHtml .= "<td class='text-align-center'><label><input type='checkbox' name='dropUserAccntsArray[]' value=\"$userAccnt\" /><span>&#10003;</span></label></td>\n";
  }
  // User name column.
  // NOTE: Asterisk (*) character indicates user account created by MySQL/phpMyAdmin.
  if (($user === "mysql.session") || ($user === "mysql.sys") || ($user === "pma") || ($user === "root")){
   $uA->userAccntsTableDataHtml .= "<td>$user*</td>\n";
  } elseif ($user === ""){
   $uA->userAccntsTableDataHtml .= "<td>Any*</td>\n";
  } else {
   $uA->userAccntsTableDataHtml .= "<td>$user</td>\n";
  }
  // Host name/IP address column.
  $uA->userAccntsTableDataHtml .= "<td>$host</td>\n";
  // Password column.
  if ($password === ""){
   $uA->userAccntsTableDataHtml .= "<td>No</td>\n";
  } else {
   $uA->userAccntsTableDataHtml .= "<td>Yes</td>\n";
  }
  // Global privileges (Grant Yes/No) column.
  if ($grant === ""){
   $uA->userAccntsTableDataHtml .= "<td>$privileges (GRANT No)</td>\n";
  } else {
   $uA->userAccntsTableDataHtml .= "<td>$privileges (GRANT Yes)</td>\n";
  }
  // On column.
  $uA->userAccntsTableDataHtml .= "<td class='text-align-center'>$on</td>\n";
  $uA->userAccntsTableDataHtml .= "</tr>\n";
 }
} else {
 // Similar text in User Accounts page view (views/userAccnts-html.php) and Databases page view (views/databases-html.php).
 $uA->userAccntsTableDataHtml = "<tr>\n<td colspan='6'>Error: Unable to list MySQL user accounts. Most likely <!-- you are not logged into MySQL under the MySQL root user account, -->the MySQL root user account has been changed/corrupted in some way that it is unable to list MySQL user accounts, or Simple MySQL Admin has been changed/corrupted in some way that it is unable to list MySQL user accounts. To continue, please try manually entering the credentials for a MySQL user account with sufficient privileges to list MySQL user accounts into the Simple MySQL Admin <span class='filename'>simple_mysql_admin/connection_credentials.php</span> file, or try uninstalling Simple MySQL Admin and redownloading and reinstalling Simple MySQL Admin from scratch.</td>\n</tr>";
}
// Unset foreach special variables used below, including in views/userAccnts-html-content.php.
unset($user);
unset($userAccnt);

/* ---------- SELECT USER ACCOUNT DROPDOWN ---------- */

foreach ($uA->userAccntsTableDataArray as list($user, $host, $userAccnt, $password, $privileges, $grant, $on)){
 // Determine if Simple MySQL Admin does not consider user account reserved for MySQL/phpMyAdmin administration (ie, determine if Simple MySQL Admin considers user account privileges editable). If Simple MySQL Admin does not consider user account reserved for MySQL/phpMyAdmin administration, expression evaluates to boolean true.
 if (($user !== "mysql.session") && ($user !== "mysql.sys") && ($user !== "pma") && ($user !== "root")){
  // Simple MySQL Admin does not consider user account reserved for MySQL/phpMyAdmin administration (ie, Simple MySQL Admin considers user account privileges editable). Increment number user accounts with editable privileges.
  $uA->numberUserAccntsWithEditablePrivileges++;
  // Determine if foreach current user account was selected in select user account dropdown.
  if ((isset($_SESSION["selectedUserAccnt"])) && ($userAccnt === $_SESSION["selectedUserAccnt"])){
   // foreach current user account was selected in select user account dropdown.
   // Call html entities method to reduce XSS attack surface and set return value on $variable.
   // NOTE:
   // Untrusted data output to HTML is vulnerable to XSS attack. $userAccnt is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
   // If call html entities method before determine if foreach current user account was selected in select user account dropdown, selected user account persistence is lost.
   $userAccnt = $index->htmlEntities($userAccnt);
   // Select user account in select user account dropdown. This persists selected user account.
   $uA->selectUserAccntDropdownOptionHtml .= "<option value=\"$userAccnt\" selected='selected'>$userAccnt</option>\n";
  } else {
   // foreach current user account was not selected in select user account dropdown.
   // Call html entities method to reduce XSS attack surface and set return value on $variable.
   // NOTE:
   // Untrusted data output to HTML is vulnerable to XSS attack. $userAccnt is untrusted data output to HTML. To reduce XSS attack surface, convert all applicable characters to HTML entities.
   // If call html entities method before determine if foreach current user account was selected in select user account dropdown, selected user account persistence is lost.
   $userAccnt = $index->htmlEntities($userAccnt);
   // Do not select user account in select user account dropdown.
   $uA->selectUserAccntDropdownOptionHtml .= "<option value=\"$userAccnt\">$userAccnt</option>\n";
  }
 }
}

$uA->selectUserAccntDropdownHtml = "</div>
      <form method='post' action='index.php' name='selectUserAccntForm'>
       <select name='selectedUserAccnt' id='selectUserAccntDropdown'>
        <option value=''></option>
        $uA->selectUserAccntDropdownOptionHtml
       </select>
       <button type='submit' id='selectUserAccntFormHiddenSubmitBtn' class='hidden'></button>
      </form>";

if ($uA->numberUserAccntsWithEditablePrivileges === 0){
 $uA->selectUserAccntDropdownHtml = "All user accounts are reserved for MySQL/phpMyAdmin administration, and Simple MySQL Admin does not allow editing privileges of user accounts reserved for MySQL/phpMyAdmin administration. For additional information, expand the Edit User Account Privileges section notes.</div>";
}

/* ---------- USER ACCOUNTS HTML CONTENT ---------- */

// Quantity of content makes logic difficult to read. Therefore, place content in external file.
$userAccntsHtmlContentHtml = require_once "views/userAccnts-html-content.php";

/* ---------- VIEW ---------- */

return
  "<div class='view-container'>
    $userAccntsHtmlContentHtml
   </div>
";
