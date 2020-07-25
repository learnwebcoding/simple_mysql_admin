<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: views/requirements-html.php.
 * Purpose: Requirements page view.
 * Used in: controllers/requirements.php.
 * Last reviewed/updated: 08 Apr 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) connectToMysqlForm. */

/* ---------- CONNECT TO MYSQL FORM ---------- */

// If MySQL is running and MySQL is not connected, show connect to MySQL form. 
// NOTE: Per OWASP XSS (Cross Site Scripting) Prevention Cheat https://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet, never place untrusted data in HTML comments. $username and $hostname are untrusted data in HTML comments. To reduce XSS attack surface, replace $username and $hostname variable dollar sign ($) characters with underscore (_) characters.
if (($index->isRunning) && (!$index->isConnected)){
 $req->connectToMysqlFormHtml = "<br />
      <div><b>Connect to MySQL:</b></div>
      <form method='post' action='index.php' name='connectToMysqlForm'>
       <!-- User name: <input type='text' name='username' value='_username' /><br /> -->
       MySQL root user account password: <input type='text' name='password' value='' /><br />
       <!-- Host name: <input type='text' name='hostname' value='_hostname' /><br /> -->
       <button type='reset' class='btn-sm'>Reset</button> <button type='submit' class='btn-sm'>Connect To MySQL</button>
      </form>
";
}

/* ---------- VIEW ---------- */

return
  "<div class='view-container'>

    <div class='section'>
     <div class='section-title'>
      <span class='section-title-title'>MySQL</span>
     </div>
     <div class='section-content'>
      <div class='line-height-custom'>
       <b>MySQL running status:</b> $index->runningStatus<br />
       <b>MySQL connection status:</b> $index->connectionStatus
      </div>
      $req->connectToMysqlFormHtml
     </div>
    </div>

    <div class='section'>
     <div class='section-title'>
      <span class='section-title-title'>Web Browser Cookies</span>
     </div>
     <div class='section-content'>
      <div id='cookieStatus' class='line-height-custom'><b>Web browser cookies status:</b> <span class='good'>Good</span>. Web browser cookies are enabled.</div>
     </div>
    </div>
   </div>

   <div class='about'>
    Simple MySQL Admin v1.3.0 released 08 Apr 2018.<br />
    Simple MySQL Admin supports Internet Explorer 10+, Edge 12+, Firefox 6+, Chrome 30+, Opera 17+, PHP 5.6.8+, PHP 7+, MariaDB 10+, MySQL 5.5+.<br />
    Simple MySQL Admin is intended solely for personal use in private/Intranet/offline/development environments.<br />
    For additional information, including security, known issues, and disclaimer, see <a href='http://www.learnwebcoding.com/php/simple_mysql_admin.php'>Simple MySQL Admin (learnwebcoding.com)</a>.
   </div>
";
