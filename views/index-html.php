<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: views/index-html.
 * Purpose: Index page view.
 * Used in: index.php.
 * Last reviewed/updated: 19 Ju1 2017.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: None. */

/* ---------- PERSIST LAST STATUS ---------- */

// Determine if last status report was outcome of SQL statement. If last status report was outcome of SQL statement, expression evaluates to boolean true.
// NOTE: $_SESSION superglobal last status variable set in User Accounts page controller (controllers/userAccnts.php) and Databases page controller (controllers/databases.php).
if (isset($_SESSION["lastStatus"])){
 // Last status report was outcome of SQL statement. Get last status report from $_SESSION superglobal last status variable and set on $index->property.
 $index->body_main_lastStatus = $_SESSION["lastStatus"];
}

/* ---------- VIEW ---------- */

return "<!DOCTYPE html>
<html lang='en'>
 <head>
  <title>$index->head_title</title><!-- No markup in title tags. -->
  <meta charset='$index->head_meta_charset' />
  <meta name='Author' content='$index->head_meta_author' />
  <meta name='Keywords' content='$index->head_meta_keywords' /><!-- Cap words as if used in sentence. No markup in Keywords. -->
  <meta name='Description' content='$index->head_meta_description' /><!-- Sentence and/or structured data less than 160 char per Google. No markup in Description. -->
  <meta name='viewport' content='$index->head_meta_viewport' />
  $index->head_link_stylesheets
 </head>
 <body>
  <main>
   <div class='last-status'><b>Last:</b> $index->body_main_lastStatus</div>
   <div>
    $index->body_main_tabs
    <a href='https://www.learnwebcoding.com/php/simple_mysql_admin.php' class='app-name'>$index->body_main_appName</a>
   </div>
   $index->body_main_page
   $index->body_main_javascript
  </main>
 </body>
</html>";
