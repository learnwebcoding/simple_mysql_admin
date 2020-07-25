<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: controllers/template.php.
 * Purpose: Template page controller. Require Template page class (classes/Template.class.php) and instantiate Template page $template object. Require Template page view (views/template-html.php) and return Template page HTML content to Index page controller (index.php).
 * Used in: No other file.
 * Last reviewed/updated: 11 Mar 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: None. */

/* ---------- INSTANTIATE OBJECT ---------- */

// Require Template page class and instantiate Template page object.
require_once "classes/Template.class.php";
$res = new Template();

/* ---------- ASSIGNMENTS ---------- */

/* ---------- REQUIRE VIEW AND RETURN HTML CONTENT TO INDEX PAGE (FRONT) CONTROLLER ---------- */

// Require Template page view and return Template page HTML content to Index page controller (index.php).
$html = require_once "views/template-html.php";
return $html;
