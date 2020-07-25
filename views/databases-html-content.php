<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: views/databases-html-content.php.
 * Purpose: Databases page view. Content from views/databases-html.php is placed in this external file so logic in views/databases-html.php is easier to read.
 * Used in: views/databases-html.php.
 * Last reviewed/updated: 05 Apr 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) createDatabaseForm, and 2.) dropDatabaseForm. */
return "
    <div class='section'>
     <div class='section-title'>
      <div>
       <span class='section-title-title'>Create Database</span>
       <span class='section-title-notes-plus-minus-icons float-right' onclick='ToggleDisplaySectionNotesUtil.toggleDisplaySectionNotes(\"create-database\")'>
        <img src='./images/plusSignExpand.gif' alt='Plus Sign Expand' class='create-database' />
        <img src='./images/minusSignCollapse.gif' alt='Minus Sign Collapse' class='create-database display-none' />
       </span>
      </div>
      <ul class='create-database display-none'>
       <li>Simple MySQL Admin allows selecting only the most commonly used/recommended collations. To select additional collations, please suggest collations to add to the Collation dropdown or use phpMyAdmin.</li>
       <li>$yourDefaultCollationServerHtml For additional information, see <a href='https://dev.mysql.com/doc/refman/5.7/en/charset-applications.html'>11.1.5 Configuring Application Character Set and Collation (dev.mysql.com)</a>.</li>
       <li>phpBB QuickInstall creates a database for phpBB that uses the MySQL default collation.</li>
       <li>The recommended WordPress database collation is utf8mb4_general_ci. For additional information, see <a href='https://codex.wordpress.org/Installing_WordPress'>Installing WordPress (codex.wordpress.org)</a>.</li>
      </ul>
     </div>
     <div class='section-content'>
      <div class='section-content-status'><b>Create database status:</b> $db->createDatabaseStatus</div>
      <form method='post' action='index.php' name='createDatabaseForm'>
       Database name: <input type='text' name='createDatabase' required /><br />
       Collation: <select name='collation'>$db->selectCollationOptionsHtml</select><br />
       <button type='reset' class='btn-sm'>Reset</button> <button type='submit' class='btn-sm'>Create Database</button>
      </form>
     </div>
    </div>

    <div class='section'>
     <div class='section-title'>
      <span class='section-title-title'>Drop Database</span>
     </div>
     <div class='section-content'>
      <div class='section-content-status'><b>Drop database status:</b> $db->dropDatabasesStatus</div>
      <form method='post' action='index.php' name='dropDatabaseForm'>
       <table>
        <thead>
         <tr>
          <th class='text-align-center'>Drop</th>
          <th>Database</th>
          <th>Collation</th>
         <tr>
        </thead>
        <tbody>
         $db->databasesTableDataHtml
        </tbody>
        <tfoot>
         <tr>
          <td colspan='4'>
           <ul>
            <li>rsvd = Database reserved for MySQL/phpMyAdmin administration. Simple MySQL Admin does not allow dropping.</li>
            <li>* = Database created by MySQL/phpMyAdmin.</li>
           </ul>
          </td>
         </tr>
        </tfoot>
       </table>
       <button type='reset' class='btn-sm'>Reset</button> <button type='submit' class='btn-sm'>Drop Database</button>
       <input type='hidden' name='isDropDatabaseFormSubmitted' value='yes' />
      </form>
     </div>
    </div>
";
