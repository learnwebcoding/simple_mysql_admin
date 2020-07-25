<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: classes/Requirements.class.php.
 * Purpose: Requirements page model. Class definition for Requirements page $req object. Requirements page $req object declares properties representing Requirements page web page content and defines methods representing Requirements page interface.
 * Used in: controllers/requirements.php.
 * Last reviewed/updated: 05 Apr 2018.
 * Last reviewed/updated for SQL injection: 31 May 2017.
 * Published: 14 May 2017.
 * NOTE: No HTML in class definition. */

/* -------------------- PHP CLASS DEFINITION -------------------- */

// Instantiated as $req object in controller (controllers/requirements.php).
class Requirements {

/* ---------- PROPERTIES ---------- */

 // Properties: $req->property.
 // Purpose: $req object properties.
 // NOTE: No logic in properties.

 // First assigned value in view (views/requirements-html.php).
 public
  $connectToMysqlFormHtml;		// String. If not declared as $req-property, throws following which references first use: Notice: Undefined variable: connectToMysqlFormHtml.

/* ---------- METHODS ---------- */

 // NOTE:
 // Default access level for methods is public.
 // PDO = PHP Data Object.

 // Method: function __construct(). Class constructor method.
 // Purpose: In general, to automatically perform things when object is instantiated. Typically, to initialize properties with non constant values (http://php.net/manual/en/language.oop5.properties.php). Here: nothing to do.
 // NOTE:
 // Class constructor method automatically runs when object instantiated.
 // Arguments to object constructor method (in controller) are automatically passed to class constructor method (in class, here) when object is instantiated (in controller). This allows objects to be instantiated with properties from other objects, which is known as dependency injection.
 function __construct(){
 }

} // Close Requirements class.
