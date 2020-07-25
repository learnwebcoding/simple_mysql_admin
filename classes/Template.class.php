<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: classes/Template.class.php.
 * Purpose: Template page model. Class definition for Template page $template object. Template page $template object declares properties representing Template page web page content and defines methods representing Template page interface.
 * Used in: controllers/template.php.
 * Last reviewed/updated: 15 Jul 2017.
 * Last reviewed/updated for SQL injection: 31 May 2017.
 * Published: 14 May 2017.
 * NOTE: No HTML in class definition. */

/* -------------------- PHP CLASS DEFINITION -------------------- */

// Instantiated as $template object in controller (controllers/template.php).
class Template {

/* ---------- PROPERTIES ---------- */

 // Properties: $template->property.
 // Purpose: $template object properties.
 // NOTE: No logic in properties.

/* ---------- METHODS ---------- */

 // NOTE:
 // Default access level for methods is public.
 // PDO = PHP Data Object.

 // Method: function __construct(). Class constructor method.
 // Purpose: In general, to automatically perform things when object is instantiated. Typically, to initialize properties with non constant values (http://php.net/manual/en/language.oop5.properties.php). Here: 1.) ; 2.) ; and 3.) .
 // NOTE:
 // Class constructor method automatically runs when object instantiated.
 // Arguments to object constructor method (in controller) are automatically passed to class constructor method (in class, here) when object is instantiated (in controller). This allows objects to be instantiated with properties from other objects, which is known as dependency injection.
 function __construct(){
 }

} // Close Template class.
