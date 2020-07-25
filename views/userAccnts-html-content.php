<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: views/userAccnts-html-content.php.
 * Purpose: User Accounts page view. Content from views/userAccnts-html.php is placed in this external file so logic in views/userAccnts-html.php is easier to read.
 * Used in: views/userAccnts-html.php.
 * Last reviewed/updated: 07 Apr 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) createUserAccntForm, 2.) dropUserAccntsForm, 3.) selectUserAccntForm, and 4.) userAccntPrivsForm. */
return "
    <div class='section'>
     <div class='section-title'>
      <span class='section-title-title'>Create User Account</span>
     </div>
     <div class='section-content'>
      <div class='section-content-status'><b>Create user account status:</b> $uA->createUserAccntStatus</div>
      <form method='post' action='index.php' name='createUserAccntForm'>
       User name: <input type='text' name='createUserAccntUsername' required /><br />
       Password (optional): <input type='text' name='createUserAccntPassword' /><br />
       Host name/IP address: <input type='text' name='createUserAccntHostname' value='localhost' /><br />
       <button type='reset' class='btn-sm'>Reset</button> <button type='submit' class='btn-sm'>Create User Account</button>
      </form>
     </div>
    </div>

    <div class='section'>
     <div class='section-title'>
      <span class='section-title-title'>Drop User Accounts</span>
     </div>
     <div class='section-content'>
      <div class='section-content-status'><b>Drop user accounts status:</b> $uA->dropUserAccntsStatus</div>
      <form method='post' action='index.php' name='dropUserAccntsForm' id='dropUserAccntsForm'>
       <table>
        <thead>
         <tr>
          <th class='text-align-center'>Drop</th>
          <th>User Name</th>
          <th>Host&nbsp;Name/<br />
           IP Address</th>
          <th>Password</th>
          <th>Global Privileges (GRANT Yes/No)</th>
          <th class='text-align-center'>On</th>
         <tr>
        </thead>
        <tbody>
         $uA->userAccntsTableDataHtml
        </tbody>
        <tfoot>
         <tr>
          <td colspan='6'>
           <ul>
            <li>rsvd = User account reserved for MySQL/phpMyAdmin administration. Simple MySQL Admin does not allow dropping.</li>
            <li>* = User account created by MySQL/phpMyAdmin.</li>
            <li>Any = Any user name (e.g., foo) accepted. In the user account, any user name is represented as two apostrophe ('') characters (i.e., empty string), not as 'Any'.</li>
            <li>% = Any host name (e.g., foo) accepted. In the user account, any host name is represented as apostrophe, percent sign, and apostrophe ('%') characters, not as '' (i.e., empty string).</li>
            <li>ALL PRIVILEGES = All privileges with possible exception of GRANT.</li>
            <li>USAGE = No privileges with possible exception of GRANT.</li>
            <li>On = On database.tables (e.g., *.* means on all databases and all tables).</li>
           </ul>
          </td>
         </tr>
        </tfoot>
       </table>
       <button type='reset' class='btn-sm'>Reset</button> <button type='submit' class='btn-sm'>Drop User Accounts</button>
       <input type='hidden' name='isDropUserAccntsFormSubmitted' value='yes' />
      </form>
     </div>
    </div>

    <div class='section' id='editUserAccntPrivsFieldset'>
     <div class='section-title'>
      <div>
       <span class='section-title-title'>Edit User Account Privileges</span>
       <span class='section-title-notes-plus-minus-icons float-right' onclick='ToggleDisplaySectionNotesUtil.toggleDisplaySectionNotes(\"edit-user-accnt-privs\")'>
        <img src='./images/plusSignExpand.gif' alt='Plus Sign Expand' class='edit-user-accnt-privs' />
        <img src='./images/minusSignCollapse.gif' alt='Minus Sign Collapse' class='edit-user-accnt-privs display-none' />
       </span>
      </div>
      <ul class='edit-user-accnt-privs display-none'>
       <li>Simple MySQL Admin does not allow editing privileges of user accounts reserved for MySQL/phpMyAdmin administration. Therefore, user accounts reserved for MySQL/phpMyAdmin administration are not listed in the Select user account dropdown. Moreover, if all user accounts are reserved for MySQL/phpMyAdmin administration, the Select user account dropdown is replaced with text explaining such. When this is the case, to edit user account privileges, first create a user account above.</li>
       <li>Simple MySQL Admin allows editing only the <dfn title='Global privileges are administrative or apply to all databases on a given server. To assign global privileges, use ON *.* syntax.'>global privileges</dfn> shown below. For the global privileges not shown, defaults are used. To manage additional privileges, use phpMyAdmin.</li>
       <li>Data, Structure, and Administration are types/categories of global privileges, not global privileges themselves.</li> 
      </ul>
     </div>
     <div class='section-content'>
      <div class='section-content-status'><b>Edit user account privileges status:</b> $uA->editStatus</div>
      <div class='line-height-custom'>1.) Select user account: $uA->selectUserAccntDropdownHtml
      <div class='line-height-custom'>2.) Edit user account privileges below and then click save:</div>
      <form method='post' action='index.php' name='userAccntPrivsForm' id='userAccntPrivsForm'>
       <fieldset class='float-left ua-all-privs-container'>
        <legend>
         <label><input type='radio' name='supercategoryRadioBtnPrivsArray[]' value='usage' id='usage' class='' /><span>&#9679;</span> <dfn title='No privileges with possible exception of GRANT.'>USAGE</dfn> (all unchecked &#177; <dfn title='Allows adding users and privileges without reloading the privilege tables.'>GRANT</dfn>)</label>&nbsp;&nbsp;
         <label><input type='radio' name='supercategoryRadioBtnPrivsArray[]' value='all privileges' id='all privileges' class='' /><span>&#9679;</span> <dfn title='All privileges with possible exception of GRANT.'>ALL PRIVILEGES</dfn> (all checked &#177; <dfn title='Allows adding users and privileges without reloading the privilege tables.'>GRANT</dfn>)</label>
        </legend>
        <div class=''>
         <fieldset class='float-left'>
          <legend><label><input type='checkbox' name='categoryCheckboxesArray[]' value='data' id='data' class='all category' /><span>&#10003;</span> Data</label></legend>
          <div class='ua-subcategory-checkboxes-container'>
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='select' id='select' class='all data' /><span>&#10003;</span> <dfn title='Allows reading data.'>SELECT</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='insert' id='insert' class='all data' /><span>&#10003;</span> <dfn title='Allows inserting and replacing data.'>INSERT</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='update' id='update' class='all data' /><span>&#10003;</span> <dfn title='Allows changing data.'>UPDATE</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='delete' id='delete' class='all data' /><span>&#10003;</span> <dfn title='Allows deleting data.'>DELETE</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='file' id='file' class='all data' /><span>&#10003;</span> <dfn title='Allows importing data from and exporting data into files.'>FILE</dfn></label>
          </div>
         </fieldset>
         <fieldset class='float-left'>
          <legend><label><input type='checkbox' name='categoryCheckboxesArray[]' value='structure' id='structure' class='all category' /><span>&#10003;</span> Structure</label></legend>
          <div class='ua-subcategory-checkboxes-container'>
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='create' id='create' class='all structure' /><span>&#10003;</span> <dfn title='Allows creating new databases and tables.'>CREATE</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='alter' id='alter' class='all structure' /><span>&#10003;</span> <dfn title='Allows altering the structure of existing tables.'>ALTER</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='index' id='index' class='all structure' /><span>&#10003;</span> <dfn title='Allows creating and dropping indexes.'>INDEX</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='drop' id='drop' class='all structure' /><span>&#10003;</span> <dfn title='Allows dropping databases and tables.'>DROP</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='create temporary tables' id='create temporary tables' class='all structure' /><span>&#10003;</span> <dfn title='Allows creating temporary tables.'>CREATE TEMPORARY TABLES</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='show view' id='show view' class='all structure' /><span>&#10003;</span> <dfn title='Allows performing SHOW CREATE VIEW queries.'>SHOW VIEW</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='create routine' id='create routine' class='all structure' /><span>&#10003;</span> <dfn title='Allows creating stored routines.'>CREATE ROUTINE</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='alter routine' id='alter routine' class='all structure' /><span>&#10003;</span> <dfn title='Allows altering and dropping stored routines.'>ALTER ROUTINE</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='execute' id='execute' class='all structure' /><span>&#10003;</span> <dfn title='Allows executing stored routines.'>EXECUTE</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='create view' id='create view' class='all structure' /><span>&#10003;</span> <dfn title='Allows creating new views.'>CREATE VIEW</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='event' id='event' class='all structure' /><span>&#10003;</span> <dfn title='Allows to set up events for the event scheduler.'>EVENT</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='trigger' id='trigger' class='all structure' /><span>&#10003;</span> <dfn title='Allows creating and dropping triggers.'>TRIGGER</dfn></label>
          </div>
         </fieldset>
         <fieldset class='float-left'>
          <legend><label><input type='checkbox' name='categoryCheckboxesArray[]' value='administration' id='administration' class='all category' /><span>&#10003;</span> Administration</label></legend>
          <div class='ua-subcategory-checkboxes-container'>
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='grant' id='grant' class='all administration' /><span>&#10003;</span> <dfn title='Allows adding users and privileges without reloading the privilege tables.'>GRANT</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='super' id='super' class='all administration' /><span>&#10003;</span> <dfn title='Allows connecting, even if maximum number of connections is reached; required for most administrative operations like setting global variables or killing threads of other users.'>SUPER</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='process' id='process' class='all administration' /><span>&#10003;</span> <dfn title='Allows viewing processes of all users.'>PROCESS</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='reload' id='reload' class='all administration' /><span>&#10003;</span> <dfn title='Allows reloading server settings and flushing the server&apos;s caches.'>RELOAD</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='shutdown' id='shutdown' class='all administration' /><span>&#10003;</span> <dfn title='Allows shutting down the server.'>SHUTDOWN</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='show databases' id='show databases' class='all administration' /><span>&#10003;</span> <dfn title='Gives access to the complete list of databases.'>SHOW DATABASES</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='lock tables' id='lock tables' class='all administration' /><span>&#10003;</span> <dfn title='Allows locking tables for the current thread.'>LOCK TABLES</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='references' id='references' class='all administration' /><span>&#10003;</span> <dfn title='Has no effect in this MySQL version.'>REFERENCES</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='replication client' id='replication client' class='all administration' /><span>&#10003;</span> <dfn title='Allows the user to ask where the slaves / masters are.'>REPLICATION CLIENT</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='replication slave' id='replication slave' class='all administration' /><span>&#10003;</span> <dfn title='Needed for the replication slaves.'>REPLICATION SLAVE</dfn></label><br />
           <label><input type='checkbox' name='itemCheckboxPrivsArray[]' value='create user' id='create user' class='all administration' /><span>&#10003;</span> <dfn title='Allows creating, dropping and renaming user accounts.'>CREATE USER</dfn></label>
          </div> 
         </fieldset>
        </div>
       </fieldset>
       <div class='clear-both'></div>
       <button type='reset' id='userAccntPrivsFormResetBtn' class='btn-sm'>Reset</button> <button type='submit' name='saveUserAccntPrivsBtn' class='btn-sm'>Save User Account Privileges</button>
       <input type='hidden' name='isUserAccntPrivsFormSubmitted' value='yes' />
      </form>
     </div>
    </div>
";
