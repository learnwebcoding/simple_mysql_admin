Simple_MySQL_Admin_Readme.txt
Last reviewed/edited: 08 Apr 2018.

Simple MySQL Admin is developed by Steve Taylor at Learn Web Coding (http://www.learnwebcoding.com/). Simple MySQL Admin is intended solely for personal use in private/Intranet/offline/development environments, not public/Internet/online/production environments. Use Simple MySQL Admin at your own risk. For additional information, including security, known issues, and disclaimer, see the Simple MySQL Admin home page (http://www.learnwebcoding.com/php/simple_mysql_admin.php). Please email comments/suggestions to steve@learnwebcoding.com. Please feel free to download, edit, and/or fork the Simple MySQL Admin source code. The Simple MySQL Admin source code is available at GitHub (https://github.com/learnwebcoding/simple_mysql_admin).

-----------------
TABLE OF CONTENTS
-----------------

1.) INTRODUCTION
2.) WEB BROWSER SUPPORT
3.) PHP SUPPORT
4.) DATABASE SERVER SUPPORT
5.) RELEASE HISTORY AND CHANGELOG
6.) DISCLAIMER

----------------
1.) INTRODUCTION
----------------

Simple MySQL Admin is a web-based graphical user interface (web GUI) for managing MySQL (https://www.mysql.com/) and MariaDB (https://mariadb.org/). Simple MySQL Admin is a lightweight and easy to use alternative to phpMyAdmin (https://www.phpmyadmin.net/). Simple MySQL Admin is written primarily in PHP. Simple MySQL Admin supports only the most commonly used MySQL/MariaDB features. Currently, Simple MySQL Admin supports:

User accounts:
* Create user accounts.
* Drop user accounts.
* Edit user account global privileges.

Databases:
* Create database.
* Drop databases.

-----------------------
2.) WEB BROWSER SUPPORT
-----------------------

Simple MySQL Admin supports Internet Explorer 10+, Edge 12+, Firefox 6+, Chrome 30+, and Opera 17+.

---------------
3.) PHP SUPPORT
---------------

Simple MySQL Admin supports PHP 5.6.8+ and PHP 7+. PHP 5.6.7- not tested.

---------------------------
4.) DATABASE SERVER SUPPORT
---------------------------

Simple MySQL Admin supports MariaDB 10+ and MySQL 5.5+.

---------------------------------
5.) RELEASE HISTORY AND CHANGELOG
---------------------------------

v1.3.0 released 08 Apr 2018:
* Update database server support. Primary changes: 1.) add support for MySQL 5.7 stores MySQL user account password in mysql.user table authentication_string column, not password column (in MySQL 5.7, mysql.user table password column does not exit); 2.) add support for MySQL 5.7 'mysql.session'@'localhost' and 'mysql.sys'@'localhost' reserved user accounts, and MariaDB 10.0 - 10.2 'root'@'computername-pc' reserved user account; 3.) add support for MySQL 5.7 sys reserved database; and 4.) change MySQL to store Simple MySQL Admin | User Accounts | create user account | host name/IP address field empty/blank in mysql.user table host column as any host name ('%' string), not empty string (''), and change Simple MySQL Admin | User Accounts | create user account reports to display host name/IP address field empty/blank as any host name ('%' string), not empty string ('').
* Replace User Accounts | edit user account privileges | select user account dropdown with explanatory text if all user accounts are reserved.
* Update lwc.css and update CSS styles.

v1.2.2 released 11 Mar 2018:
* Fix path to images.
* Update PHP support.
* Add database server support.
* Update comments for consistency with other projects.

v1.2.1 released 28 Ju1 2017:
* Update lwc.css.
* Edit simple_mysql_admin.js to be unobtrusive. Change IE support from IE9+ to IE10+.
* Change Simple MySQL Admin information displayed at bottom of Requirements page.

v1.2 released 20 Ju1 2017:
* Redesign interface. Primary changes; 1.) replace page section HTML fieldset and legend elements with div elements, 2.) set max-width dependent on viewport width and center in viewport, and 3.) move app name from top of interface to float: right of tabs.
* Make last status report persistent.
* Move section notes from section content always displayed to section title with plus/minus icon and JavaScript to toggle display.
* Remove use database code as belonging in future Tables page as dropdown.
* End User Accounts | edit user account privileges | selected user account persistence if selected user account is dropped.
* Change references to Index section, Requirements section, User Accounts section, and Databases section to Index page, Requirements page, User Accounts page, and Databases page.

v1.1.2 released 20 Jun 2017:
* Update simple_mysql_admin.css to improve presentation/consistency and to eliminate redundant overriding of lwc.css styles.
* Add HTML button element class='btn-md' attribute where missing and input element required attribute where appropriate.

v1.1.1 released 11 Jun 2017:
* Add lwc.css as primary style sheet and supplement/override lwc.css styles in simple_mysql_admin.css.
* Update CSS styles.

v1.1 released 31 May 2017:
* Move MySQL running mysqli connection object and MySQL connection PDO connection object from Connection section to Index section.
* Replace Connection section with Requirements section.
* Group MySQL running and MySQL connection under MySQL fieldset in, and add Web Browser Cookies fieldset to, Requirements section.
* Add User Accounts tab and Databases tab are not shown until Requirements section is fulfilled.
* Change boundary between model code and controller code in User Accounts section and Databases section: move code deemed less model-worthy (ie, "isExecuteSuccessful" and "isFetchSuccessful" conditionals) from model to controller.
* Add comment Last reviewed/updated for SQL injection: Date or Last reviewed/updated for XSS: Date to Introduction section of relevant files.
* Change status report from bad to warning when click Go button without specifying/selecting required information.
* Sync check/uncheck radio buttons/checkboxes between JavaScript EditUserAccntPrivsUtil.checkUserAccntPrivsFormRadioBtnCheckboxes() method and JavaScript EditUserAccntPrivsUtil.coordinateCheckedUncheckedHierarchy() method.
* Change grant all privileges without grant option from SQL statement that grants all individual global privileges except grant to SQL statement that grants all privileges without grant option.
* Update Simple_MySQL_Admin_Readme.txt.

v1.0.1 released 16 May 2017:
* Update Simple_MySQL_Admin_Readme.txt.

v1.0 released 14 May 2017:
* Initial release.

--------------
6.) DISCLAIMER
--------------

THIS SOFTWARE IS PROVIDED "AS IS" AND ANY EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
