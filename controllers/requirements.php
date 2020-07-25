<?php
/* -------------------- INTRODUCTION -------------------- */

/* File: controllers/requirements.php.
 * Purpose: Requirements page controller. Require Requirements page class (classes/Requirements.class.php) and instantiate Requirements page $req object. Require Requirements page view (views/requirements-html.php) and return Requirements page HTML content to Index page (front) controller (index.php).
 * Used in: No other file.
 * Last reviewed/updated: 11 Mar 2018.
 * Last reviewed/updated for XSS: 31 May 2017.
 * Published: 14 May 2017.
 * Forms: 1.) connectToMysqlForm. */

/* ---------- INSTANTIATE OBJECT ---------- */

// Require Requirements page class and instantiate Requirements page object.
require_once "classes/Requirements.class.php";
$req = new Requirements();

/* ---------- ASSIGNMENTS ---------- */

/* ---------- REQUIRE VIEW AND RETURN HTML CONTENT TO INDEX PAGE (FRONT) CONTROLLER ---------- */

// Require Requirements page view and return Requirements page HTML content to Index page (front) controller (index.php).
$html = require_once "views/requirements-html.php";
return $html;
