/* -------------------- INTRODUCTION -------------------- */

/* File: javascripts/simple_mysql_admin.js.
 * Purpose: Primary JavaScript for Simple MySQL Admin.
 * Used in: javascripts/javascripts.php.
 * Last reviewed/updated: 11 Mar 2018.
 * Published: 14 May 2017.
 * Unobtrusive: 1.) decouple HTML/JavaScript: a.) no register JavaScript event handlers in HTML via HTML attributes (eg, onload and onclick), and b.) no embed JavaScripts in HTML via HTML script element; 2.) as reasonable, decouple CSS/JavaScript. Eg, as reasonable, use JavaScript to change HTML class attribute value assignments (loose coupling), not use JavaScript to change JavaScript style object CSS property value assignments (tight coupling); and 3.) no define JavaScript variables and functions on JavaScript global scope.
 * Objects: TabsUtil and EditUserAccntPrivsUtil. */

// ---------- NOTES FOR USER ACCOUNTS PAGE ----------

// User Accounts page | edit user account privileges | select user account form dropdown works as follows. Overview: 1.) user selects user account in select user account dropdown; 2.) PHP gets selected user account privileges from user accounts privileges CVS (controllers/userAccnts.php) and assigns selected user account privileges to JavaScript property (views/userAccnts-html.php); 3.) JavaScript gets selected user account privileges and checks radio button/checkboxes in user account privileges form that correspond to selected user account privileges; 4.) user edits user account privileges and clicks save user account privileges button; 5.) PHP sets edited user account privileges on MySQL database. More specifically, when select user account in select user account dropdown, submit select user account form so PHP can get selected user account and then get selected user account privileges from user accounts privileges CVS. To avoid having to manually click a submit button after selecting user account, programmatically and automatically click hidden submit button. This requires registering select user account dropdown change event to handler that clicks select user account form hidden submit button. When select user account form is submitted, PHP gets selected user account privileges and assigns them to JavaScript property. This transfer of selected user account privileges from PHP to JavaScript involves PHP User Accounts page controller (userAccnts.php), PHP User Accounts page view (userAccnts-html.php), and JavaScript primary Simple MySQL Admin file (simple_mysql_admin.js). When select user account form is submitted and Index page (front) controller (index.php) reloads, JavaScript checks user account privileges form radio button/checkboxes that correspond to selected user account privileges. This requires registering web browser window load event to handler that checks user account privileges form radio button/checkboxes.

/* -------------------- JAVASCRIPT OBJECT DEFINITIONS -------------------- */

/* ---------- NEW JAVASCRIPT OBJECT DEFINITION ---------- */

var ToggleDisplaySectionNotesUtil = {

 // ---------- TOGGLE DISPLAY SECTION NOTES UTILITY PROPERTIES ----------

 // ---------- TOGGLE DISPLAY SECTION NOTES UTILITY METHODS ----------

 // Method: ToggleDisplaySectionNotesUtil.toggleDisplaySectionNotes().
 // Purpose: Handler for User Accounts page | edit user account privileges plus/minus icon click event. Handler for Databases page | edit user account privileges plus/minus icon click event. Toggle display of corresponding section notes and plus/minus icons.
 toggleDisplaySectionNotes: function(section){
  // Get reference to section elements to toggle.
  var sectionElementsToToggle = document.getElementsByClassName(section);
  // Iterate over section elements to toggle.
  for (var i = 0; i < sectionElementsToToggle.length; i++){
   // Toggle section element class = "display-none" attribute.
   sectionElementsToToggle[i].classList.toggle("display-none");
  }
 }
};

/* ---------- NEW JAVASCRIPT OBJECT DEFINITION ---------- */

var TabsUtil = {

 // ---------- TABS UTILITY PROPERTIES ----------

 hideTabs: false,
 // TabsUtil.page assigned string value representing clicked tab/page to load dynamically in Index page (front) controller (index.php).
 page: "",

 // ---------- TABS UTILITY METHODS ----------

 // Method: TabsUtil.isCookiesDisabled().
 // Purpose: Handler for web browser window load event. Determine and report if web browser cookies are disabled.
 // NOTE: If cookies enabled, nothing to do as TabsUtil.hideTabs property initialized above with boolean false value.
 isCookiesDisabled: function(){
  // Get if cookies enabled/disabled and set return boolean true/false on local variable.
  var isCookiesEnabled = navigator.cookieEnabled;
  // Determine if cookies disabled. If disabled, expression evaluates to boolean true.
  if (!isCookiesEnabled){
   // Cookies disabled. Set boolean true on TabsUtil.hideTabs property to indicate hide tabs.
   TabsUtil.hideTabs = true;
   // Get reference to Requirements page view (view/requirements-html.php) cookie status report element.
   var cookieStatusElement = document.getElementById("cookieStatus");
   // Set cookie status element inner HTML string to report bad web browser cookies are disabled with advise.
   cookieStatusElement.innerHTML = "<b>Web browser cookies status:</b> <span class='bad'>Bad</span>. Web browser cookies are disabled. To continue, enable web browser cookies and then reload this web page.";
  }
 },

 // Method: TabsUtil.hideTabs().
 // Purpose: Handler for web browser window load event. Hide Tabs view (views/tabs.php) tabs except Requirements tab.
 // NOTE: If TabsUtil.hideTabs property value is false, nothing to do as default Tabs view (views/tabs.php) shows all tabs and default Requirements page view (views/requirement-html.php) cookie status reports good web browser cookies are enabled.
 hideTabs: function(){
  // Determine if TabsUtil.hideTabs property value is boolean true. If true, expression evaluates to boolean true.
  if (TabsUtil.hideTabs === true){
   // TabsUtil.hideTabs property value is boolean true. Get reference to Tabs view (views/tabs.php) tab elements.
   var tabs = document.getElementsByClassName("tab-hyperlink");
   // Iterate over tab elements.
   for (var i = 0; i < tabs.length; i++){
    // Determine if iterated tab not Requirements tab.
    if (tabs[i].id !== "requirementsTab"){
     // Iterated tab not Requirements tab. Hide tab.
     tabs[i].classList.add("display-none");
    }
   }
  }
 },

 // Method: TabsUtil.highlightTabs().
 // Purpose: Handler for web browser window load event. Highlight clicked Tabs view (views/tabs.php) tab.
 // NOTE: Value assigned to TabsUtil.page property in Index page (front) controller (index.php) represents both clicked Tabs view (views/tabs.php) tab and page to load dynamically.
 highlightTabs: function(){
  // Get reference to Tabs view (views/tabs.php) tab elements and set on local variable.
  var tabs = document.getElementsByClassName("tab-hyperlink");
  // Set TabsUtil.page property value on local variable.
  var clickedTab = TabsUtil.page;
  // Iterate over tab elements.
  for (var i = 0; i < tabs.length; i++){
   // Determine if tab is clicked tab.
   if (tabs[i].id === clickedTab + "Tab"){
    // Tab is clicked tab. Highlight clicked Tabs view (views/tabs.php) tab.
    // NOTE: By default, Tabs view (views/tabs.php) tabs have a element class='tab-hyperlink' attribute. This appends ' tab-hyperlink-active' to end of class='tab-hyperlink' attribute value.
    tabs[i].className += " tab-hyperlink-active";
   }
  }
 },

 // Get reference to elements in order to register event handlers.
 // NOTE: Elements (including the form element) accept events from child elements (including child elements of the form element) via event bubbling.
 window: window,
};

// Register event handlers.
// Register web browser window load event to a handler that determines and reports if web browser cookies are disabled.
TabsUtil.window.addEventListener("load", TabsUtil.isCookiesDisabled, false);
// Register web browser window load event to a handler that hides Tabs view (views/tabs.php) tabs except Requirements tab.
TabsUtil.window.addEventListener("load", TabsUtil.hideTabs, false);
// Register web browser window load event to a handler that highlights Tabs view (views/tabs.php) tabs.
TabsUtil.window.addEventListener("load", TabsUtil.highlightTabs, false);

/* ---------- NEW JAVASCRIPT OBJECT DEFINITION ---------- */

var EditUserAccntPrivsUtil = {

 // ---------- EDIT USER ACCOUNT PRIVILEGES UTILITY PROPERTIES ----------

 // EditUserAccntPrivsUtil.privsCsv assigned string value representing selected user account privileges in User Accounts page view (views/userAccnts-html.php).
 privsCsv: "",
 // EditUserAccntPrivsUtil.isEditUserAccntPrivsFieldsetForm assigned string value representing is last form submitted an edit user account privileges fieldset form in User Accounts page view (views/userAccnts-html.php).
 isEditUserAccntPrivsFieldsetForm: "",
 // Represents User Account page | edit user account privileges | user account privileges form category checkbox names.
 categoryCheckboxNamesArray: ["data", "structure", "administration"],

 // ---------- EDIT USER ACCOUNT PRIVILEGES UTILITY METHODS ----------

 // Method: EditUserAccntPrivsUtil.clickSelectUserAccntFormHiddenSubmitBtn().
 // Purpose: Handler for: 1.) User Accounts page | edit user account privileges | select user account form dropdown change event; and 2.) User Accounts page | edit user account privileges | user account privileges form reset button click event. Click User Accounts page | edit user account privileges | select user account form hidden submit button.
 clickSelectUserAccntFormHiddenSubmitBtn: function(){
  // Get reference to select user account form hidden submit button.
  var selectUserAccntFormHiddenSubmitBtn = document.getElementById("selectUserAccntFormHiddenSubmitBtn");
  // Click select user account form hidden submit button.
  selectUserAccntFormHiddenSubmitBtn.click();
 },

 // Method: EditUserAccntPrivsUtil.checkUserAccntPrivsFormRadioBtnCheckboxes().
 // Purpose: Handler for web browser window load event. Check User Account page | edit user account privileges | user account privileges form radio button/checkboxes that correspond to selected user account privileges. If last form submitted was an edit user account privileges fieldset form, position edit user account privileges fieldset to top of viewport.
 checkUserAccntPrivsFormRadioBtnCheckboxes: function(){
  // Determine if last form submitted was an edit user account privileges fieldset form. If yes, expression evaluates to boolean true.
  // NOTE: If place this scrollIntoView if conditional just inside end of method, scrollIntoView if conditional code is not reached if select user account form was submitted and user account blank/none was selected. Hence, place this scrollIntoView if conditional here just inside beginning of method.
  if (EditUserAccntPrivsUtil.isEditUserAccntPrivsFieldsetForm === "true"){
   // Last form submitted was an edit user account privileges fieldset form. Get reference to edit user account privileges fieldset.
   var editUserAccntPrivsFieldset = document.getElementById("editUserAccntPrivsFieldset");
   // Position edit user account privileges fieldset to top of viewport.
   editUserAccntPrivsFieldset.scrollIntoView(true);
  }
  // Determine if selected user account privileges CSV string includes "ALL PRIVILEGES" or "USAGE" substring. If yes, indexOf() function returns integer indicating position (zero based) of substring in string. If no, indexOf() function returns integer -1.
  // NOTE: EditUserAccntPrivsUtil.privsCsv assigned string value representing selected user account privileges in User Accounts page controller (userAccnts.php).
  if ((EditUserAccntPrivsUtil.privsCsv.indexOf("ALL PRIVILEGES") !== -1) || (EditUserAccntPrivsUtil.privsCsv.indexOf("USAGE") !== -1)){
   // Selected user account privileges CSV string includes "ALL PRIVILEGES" or "USAGE" substring. Determine if selected user account privileges CSV string includes "ALL PRIVILEGES" substring. If yes, indexOf() function returns integer indicating position (zero based) of substring in string. If no, indexOf() function returns integer -1.
   if (EditUserAccntPrivsUtil.privsCsv.indexOf("ALL PRIVILEGES") !== -1){
    // Selected user account privileges CSV string includes "ALL PRIVILEGES" substring. Get reference to all privileges radio button and set on local variable.
    var allPrivilegesRadioBtn = document.getElementById("all privileges");
    // Check all privileges radio button.
    allPrivilegesRadioBtn.checked = true;
    // Get reference to all checkboxes elements and set on local variable.
    var allCheckboxes = document.querySelectorAll(".all");
    // Determine number of all checkboxes and set on local variable.
    var numberAllCheckboxes = allCheckboxes.length;
    // Iterate over all checkboxes setting each on local variable.
    for (var i = 0; i < numberAllCheckboxes; i++){
     var allCheckbox = allCheckboxes[i];
     // Check all checkbox.
     allCheckbox.checked = true;
    }
    // Determine if selected user account privileges CSV string does not include "GRANT" substring.
    // NOTE: ALL PRIVILEGES is means all privileges with possible exception of GRANT.
    if (EditUserAccntPrivsUtil.privsCsv.indexOf("GRANT") === -1){
     // Selected user account privileges CSV string does not include "GRANT" substring. Sync check/uncheck radio buttons/checkboxes between JavaScript EditUserAccntPrivsUtil.checkUserAccntPrivsFormRadioBtnCheckboxes() method here, and JavaScript EditUserAccntPrivsUtil.coordinateCheckedUncheckedHierarchy() method below. Get reference to grant checkbox element, administration checkbox element, and all privileges radio button element (done above) and set on local variable.
     var grantCheckbox = document.getElementById("grant");
     var administrationCheckbox = document.getElementById("administration");
     // Uncheck grant checkbox, administration checkbox, and all privileges radio button.
     grantCheckbox.checked = false;
     administrationCheckbox.checked = false;
     allPrivilegesRadioBtn.checked = false;
    }
   // Determine if selected user account privileges CSV string includes "USAGE" substring. If yes, indexOf() function returns integer indicating position (zero based) of substring in string. If no, indexOf() function returns integer -1.
   } else if (EditUserAccntPrivsUtil.privsCsv.indexOf("USAGE") !== -1){
    // Selected user account privileges CSV string includes "USAGE" substring. Get reference to usage radio button element and set on local variable.
    var usageRadioBtn = document.getElementById("usage");
    // Check usage radio button.
    usageRadioBtn.checked = true;
    // Determine if selected user account privileges CSV string includes "GRANT" substring.
    // NOTE: USAGE means no privileges with possible exception of GRANT.
    if (EditUserAccntPrivsUtil.privsCsv.indexOf("GRANT") !== -1){
     // Selected user account privileges CSV string includes "GRANT" substring. Sync check/uncheck radio buttons/checkboxes between JavaScript EditUserAccntPrivsUtil.checkUserAccntPrivsFormRadioBtnCheckboxes() method here, and JavaScript EditUserAccntPrivsUtil.coordinateCheckedUncheckedHierarchy() method below. Get reference to grant checkbox element and usage radio button element (done above) and set on local variable.
     var grantCheckbox = document.getElementById("grant");
     // Check grant checkbox.
     grantCheckbox.checked = true;
     // Uncheck usage radio button.
     usageRadioBtn.checked = false;
    }
   }
  } else {
   // Selected user account privileges CSV string does not include "ALL PRIVILEGES" or "USAGE" substring. Convert selected user account privileges CSV string into an array and set on local variable.
   var privsCsvArray = EditUserAccntPrivsUtil.privsCsv.split(", ");
   // Determine number of array elements and set on local variable.
   var len = privsCsvArray.length;
   // Iterate over array elements setting each on local variable.
   for (var i = 0; i < len; i++){
    var privsCsvArrayElement = privsCsvArray[i];
    // Get reference to checkbox element with id="privsCsvArrayElement" attribute and set on local variable.
    var checkbox = document.getElementById(privsCsvArrayElement.toLowerCase());
    // Check checkbox.
    checkbox.checked = true;
   }
   // Get length category checkbox names array and set on local variable.
   var numberCategoryCheckboxNames = EditUserAccntPrivsUtil.categoryCheckboxNamesArray.length;
   // Iterate over category checkbox names array setting each on local variable.
   for (var i = 0; i < numberCategoryCheckboxNames; i++){
    var categoryCheckboxName = EditUserAccntPrivsUtil.categoryCheckboxNamesArray[i];
    // Get reference to category checkbox's subcategory checkboxes elements and set on local variable.
    var subcategoryCheckboxes = document.querySelectorAll("." + categoryCheckboxName);
    // Determine number of subcategory checkboxes and set on local variable.
    var numberSubcategoryCheckboxes = subcategoryCheckboxes.length;
    // Initialize local variable to count number of subcategory checkboxes that are checked.
    var numberSubcategoryCheckboxesChecked = 0;
    // Iterate over subcategory checkboxes setting each on local variable.
    for (var j = 0; j < numberSubcategoryCheckboxes; j++){
     var subcategoryCheckbox = subcategoryCheckboxes[j];
     // Determine if subcategory checkbox is checked.
     if (subcategoryCheckbox.checked === true){
      // Subcategory checkbox is checked. Increment count of number of subcategory checkboxes that are checked.
      numberSubcategoryCheckboxesChecked++;
     }
    }
    // Determine if all subcagetory checkboxes are checked.
    if (numberSubcategoryCheckboxes === numberSubcategoryCheckboxesChecked){
     // All all subcategory checkboxes are checked. Get reference to category checkbox.
     var categoryCheckbox = document.getElementById(categoryCheckboxName);
     // Check category checkbox.
     categoryCheckbox.checked = true;
    }
   }
  }
 },

 // Method: EditUserAccntPrivsUtil.coordinateCheckedUncheckedHierarchy().
 // Purpose: Handler for User Accounts page | edit user account privileges | user account privileges form click event. Coordinate User Accounts page | edit user account privileges | user account privileges form radio button/checkboxes checked/unchecked hierarchy per user clicks.
 // NOTE:
 // User account privileges form radio button and checkbox hierarchy nomenclature:
 // Supercategory radio buttons = all privileges and usage radio buttons. (Top of hierarchy.)
 // Category checkboxes = data, structure, and administration checkboxes. (Middle of hierarchy.)
 // Subcategory checkboxes = those under data, structure, OR administration. For example, data subcategory checkboxes are SELECT through FILE.
 // Item checkboxes = those under data, structure, AND administration. Item checkboxes are all from SELECT through CREATE USER. (Bottom of hierarchy.)
 // All category radio buttons/checkboxes = supercategory radio buttons and category checkboxes.
 // All checkboxes = all category checkboxes and all item checkboxes, not supercategory radio buttons.
 // The EditUserAccntPrivsUtil.coordinateHierarchyPerUserClicks() method is divided into three non-independent sections:
 // Section 1. If clicked supercategory radio button, sync checkboxes from top to middle and from top to bottom of hierarchy; that is, if clicked (parent/ancestor) supercategory radio button, apply supercategory radio button checked/unchecked status to (child/descendant) all checkboxes.
 // Section 2.) If clicked category checkbox: a.) sync checkboxes from middle to bottom of hierarchy; that is, if clicked (parent) category checkbox, apply category checkbox checked/unchecked status to (child) subcategory checkboxes; and b.) sync checkboxes from middle to top of hierarchy; that is, if clicked (child) category checkbox, apply category checkboxes all checked/unchecked status to (parent) supercategory radio button.
 // Section 3.) If clicked item checkbox: a.) sync checkboxes from bottom to middle of hierarchy; that is, if clicked (child) item checkbox, apply (sibling) subcategory checkboxes all checked/unchecked status to (parent) category checkbox; and b.) sync checkboxes from bottom to top of hierarchy; that is, if clicked (descendant) item checkbox, apply (descendant/child) all checkboxes all checked/unchecked status to (ancestor/parent) supercategory radio button.
 // In User Accounts page view (userAccnts-html-content.php): 1.) radio button/checkbox element id attribute values are identical to radio button/checkbox text except in lower case; and 2.) checkbox (not radio button) element class attribute values are 'all category', 'all data', 'all structure', or 'all administration' to reflect checkbox position in radio button/checkbox hierarchy. This coordination between HTML element id and class attribute values makes it possible to get reference to supercategory checkboxes, category checkbox(es), subcategory checkbox(es), and item checkbox(es) using JavaScript event.target property, event.target.id property, getElementById() method, and/or querySelectorAll() method.
 coordinateCheckedUncheckedHierarchy: function(event){ // [object Event].
  // Get references to elements and set on local variables.
  // Get reference to clicked radio button/checkbox element.
  var checkbox = event.target; // [object HTMLInputElement].
  // Get clicked radio button/checkbox element id attribute value.
  // NOTE: radio button/checkbox element id attribute values (eg, select) are identical to radio button/checkbox text (eg, SELECT) except in lower case.
  var checkboxId = checkbox.id; // eg, select.
  // Get reference to all privileges radio button.
  var allPrivilegesRadioBtn = document.getElementById("all privileges");
  // Get reference to usage radio button.
  var usageRadioBtn = document.getElementById("usage");
  // Get reference to category checkboxes.
  var categoryCheckboxes = document.querySelectorAll(".category");
  // Determine number of category checkboxes.
  var numberCategoryCheckboxes = categoryCheckboxes.length;
  // Get reference to all checkboxes (ie, all category checkboxes and all item checkboxes).
  var allCheckboxes = document.querySelectorAll(".all");
  // Determine number of all checkboxes.
  var numberAllCheckboxes = allCheckboxes.length;
  // Section 1. If clicked supercategory radio button, sync checkboxes from top to middle and from top to bottom of hierarchy; that is, if clicked (parent/ancestor) supercategory radio button, apply supercategory radio button checked/unchecked status to (child/descendant) all checkboxes (ie, all category checkboxes and all item checkboxes).
  // Get reference to clicked radio button/checkbox element. Done above.
  // Get clicked radio button/checkbox element id attribute value. Done above.
  // Determine if clicked radio/button checkbox is supercategory radio button. If yes, expression evaluates to boolean true.
  if ((checkboxId === "all privileges") || (checkboxId === "usage")){
   // Clicked radio/button checkbox is supercategory radio button.
   // Determine if clicked supercategory radio button is all privileges radio button. If yes, set boolean true on local variable to indicate need to check all checkboxes. If no, set boolean false on local variable to indicate need to uncheck all checkboxes.
   var isCheckedAllPrivilegesTrueUsageFalse = checkboxId === "all privileges" ? true : false;
   // Get reference to all checkboxes. Done above.
   // Determine number of all checkboxes. Done above.
   // Iterate over all checkboxes setting each on local variable.
   for (var i = 0; i < numberAllCheckboxes; i++){
    var allCheckbox = allCheckboxes[i];
    // Check/uncheck all checkbox.
    allCheckbox.checked = isCheckedAllPrivilegesTrueUsageFalse;
   }
  // Section 2.) If clicked category checkbox: a.) sync checkboxes from middle to bottom of hierarchy; that is, if clicked (parent) category checkbox, apply category checkbox checked/unchecked status to (child) subcategory checkboxes; and b.) sync checkboxes from middle to top of hierarchy; that is, if clicked (child) category checkbox, apply category checkboxes all checked/unchecked status to (parent) supercategory radio button.
  // 2a.) Sync checkboxes from middle to bottom of hierarchy; that is, if clicked (parent) category checkbox, apply category checkbox checked/unchecked status to (child) subcategory checkboxes.
  // Determine if clicked radio button/checkbox is category checkbox. If yes, expression evaluates to boolean true.
  // NOTE: It is only necessary to determine if clicked radio button/checkbox is category checkbox. It is not necessary to determine which  category checkbox was clicked.
  } else if ((checkboxId === "data") || (checkboxId === "structure") || (checkboxId === "administration")){
   // Clicked radio button/checkbox is category checkbox. Determine if clicked category checkbox is checked. If yes, set boolean true on local variable to indicate need to check subcategory checkboxes. If no, set boolean false on local variable to indicate need to uncheck subcategory checkboxes.
   var isCategoryCheckboxCheckedTrueUncheckedFalse = checkbox.checked === true ? true : false;
   // Get reference to clicked category checkbox's subcategory checkboxes elements and set on local variable.
   var subcategoryCheckboxes = document.querySelectorAll("." + checkboxId);
   // Determine number of subcategory checkboxes.
   var len = subcategoryCheckboxes.length;
   // Iterate over subcategory checkboxes setting each on local variable.
   for (var i = 0; i < len; i++){
    var subcategoryCheckbox = subcategoryCheckboxes[i];
    // Check/uncheck subcategory checkbox.
    subcategoryCheckbox.checked = isCategoryCheckboxCheckedTrueUncheckedFalse;
   }
   // 2b.) Sync checkboxes from middle to top of hierarchy; that is, if clicked (child) category checkbox, apply category checkboxes all checked/unchecked status to (parent) supercategory radio buttons. More specifically: 1.) if clicked category checkbox is now unchecked and now all item checkboxes are unchecked, then check usage radio button; 2.) if clicked category checkbox is now checked, then uncheck usage radio button; 3.) if clicked category checkbox is now unchecked, then uncheck all privileges radio button; and 4.) if clicked category checkbox is now checked and now all three category checkboxes are checked (meaning all item checkboxes are checked), then check all privileges radio button.
   // Get reference to supercategory radio buttons. Done above.
   // Get reference to category checkboxes. Done above.
   // Determine number of category checkboxes. Done above.
   // Initialize local variable to count number of category checkboxes that are checked.
   var numberCategoryCheckboxesChecked = 0;
   // Iterate over category checkboxes setting each on local variable.
   for (var i = 0; i < numberCategoryCheckboxes; i++){
    var categoryCheckbox = categoryCheckboxes[i];
    // Determine if category checkbox is checked. If yes, expression evaluates to boolean true.
    if (categoryCheckbox.checked){
     // Category checkbox is checked. Increment count of number of category checkboxes that are checked.
     numberCategoryCheckboxesChecked++;
    }
   }
   // Determine if number of category checkboxes that are checked equals zero. If yes, expression evaluates to boolean true.
   // NOTE: An optional determination. However, if number of category checkboxes that are checked (already determined above) does not equal zero, avoids iterating over all checkboxes.
   if (numberCategoryCheckboxesChecked === 0){
    // Number of category checkboxes that are checked equals zero.
    // Get reference to all checkboxes. Done above.
    // Determine number of all checkboxes. Done above.
    // Initialize local variable to count number of all checkboxes that are checked.
    var numberAllCheckboxesChecked = 0;
    // Iterate over all checkboxes setting each on local variable.
    for (var i = 0; i < numberAllCheckboxes; i++){
     var allCheckbox = allCheckboxes[i];
     // Determine if all checkbox is checked. If yes, expression evaluates to boolean true.
     if (allCheckbox.checked){
      // All checkbox is checked. Increment count of number of all checkboxes that are checked.
      numberAllCheckboxesChecked++;
     }
    }
    // Determine if number of all checkboxes that are checked equals zero. If yes, expression evaluates to boolean true.
    if (numberAllCheckboxesChecked === 0){
     // Number of all checkboxes that are checked equals zero. Check usage radio button.
     usageRadioBtn.checked = true;
    }
   // Determine if number of category checkboxes that are checked is greater than zero and less than number of category checkboxes. If yes, expression evaluates to boolean true.
   } else if ((numberCategoryCheckboxesChecked > 0) && (numberCategoryCheckboxesChecked < numberCategoryCheckboxes)){
    // Number of category checkboxes that are checked is greater than zero and less than number of category checkboxes. Uncheck all privileges radio button and uncheck usage radio button.
    allPrivilegesRadioBtn.checked = false;
    usageRadioBtn.checked = false;
   // Determine if number of category checkboxes that are checked is equal to number of category checkboxes. If yes, expression evaluates to boolean true.
   } else if (numberCategoryCheckboxesChecked === numberCategoryCheckboxes){
    // Number of category checkboxes that are checked is equal to number of category checkboxes. Check all privileges radio button.
    allPrivilegesRadioBtn.checked = true;
   }
  // Section 3.) If clicked item checkbox: a.) sync checkboxes from bottom to middle of hierarchy; that is, if clicked (child) item checkbox, apply (sibling) subcategory checkboxes all checked/unchecked status to (parent) category checkbox; and b.) sync checkboxes from bottom to top of hierarchy; that is, if clicked (descendant) item checkbox, apply (descendant/child) all checkboxes all checked/unchecked status to (ancestor/parent) supercategory radio button.
  } else {
   // Clicked radio button/checkbox is item checkbox.
   // Get clicked item checkbox element class attribute value.
   var classAttribute = checkbox.getAttribute("class");
   // Determine if class attribute string value includes "data" substring. If yes, indexOf() function returns integer indicating position (zero based) of substring in string. If no, indexOf() function returns integer -1.
   if (classAttribute.indexOf("data") !== -1){
    // Class attribute string value includes "data" substring. Item checkbox is data subcategory checkbox. Set value on local property to indicate item checkbox is data subcategory checkbox.
    var subcategory = "data";
   // Determine if class attribute string value includes "structure" substring. If yes, indexOf() function returns integer indicating position (zero based) of substring in string. If no, indexOf() function returns integer -1.
   } else if (classAttribute.indexOf("structure") !== -1){
    // Class attribute string value includes "structure" substring. Item checkbox is structure subcategory checkbox. Set value on local property to indicate item checkbox is structure subcategory checkbox.
    var subcategory = "structure";
   } else {
    // Item checkbox is administration subcategory checkbox. Set value on local property to indicate item checkbox is administration subcategory checkbox.
    var subcategory = "administration";
   }
   // 3a.) Sync checkboxes from bottom to middle of hierarchy; that is, if clicked (child) item checkbox, apply (sibling) subcategory checkboxes all checked/unchecked status to (parent) category checkbox.
   // Get reference to clicked item checkbox's (parent) category checkbox and set on local variable.
   var categoryCheckbox = document.getElementById(subcategory);
   // Get reference to clicked item checkbox's (sibling) subcategory checkboxes and set on local variable.
   var subcategoryCheckboxes = document.querySelectorAll("." + subcategory);
   // Determine number of subcategory checkboxes.
   var numberSubcategoryCheckboxes = subcategoryCheckboxes.length;
   // Initialize local variable to count number of subcategory checkboxes that are checked.
   var numberSubcategoryCheckboxesChecked = 0;
   // Iterate over subcategory checkboxes setting each on local variable.
   for (var i = 0; i < numberSubcategoryCheckboxes; i++){
    var subcategoryCheckbox = subcategoryCheckboxes[i];
    // Determine if subcategory checkbox is checked. If yes, expression evaluates to boolean true.
    if (subcategoryCheckbox.checked){
     // Subcategory checkbox is checked. Increment count of number of subcategory checkboxes that are checked.
     numberSubcategoryCheckboxesChecked++;
    }
   }
   // Determine if number of subcategory checkboxes that are checked equals number of subcategory checkboxes. If yes, expression evaluates to boolean true.
   if (numberSubcategoryCheckboxesChecked === numberSubcategoryCheckboxes){
    // Number of subcategory checkboxes that are checked equals number of subcategory checkboxes. Check category checkbox.
    categoryCheckbox.checked = true;
   } else {
    // Number of checked subcategory checkboxes is less than number of subcategory checkboxes. Uncheck category checkbox.
    categoryCheckbox.checked = false;
   }
   // 3b.) Sync checkboxes from bottom to top of hierarchy; that is, if clicked (descendant) item checkbox, apply (descendant/child) all checkboxes all checked/unchecked status to (ancestor/parent) supercategory radio button. More specifically: 1.) if clicked item checkbox is now unchecked and now all of the all checkboxes are unchecked, then uncheck usage radio button; 2.) if clicked item checkbox is now checked, then uncheck usage radio button; 3.) if clicked item checkbox is now unchecked, then uncheck all privileges radio button. and 4.) if clicked item checkbox is now checked and now all of the all checkboxes are checked, then check all privileges radio button.
   // Get reference to item checkboxes's (ancestor) supercategory radio buttons. Done above.
   // Get reference to all checkboxes. Done above.
   // Determine number of all checkboxes. Done above.
   // Initialize local variable to count number of all checkboxes that are checked.
   var numberAllCheckboxesChecked = 0;
   // Iterate over all checkboxes setting each on local variable.
   for (var i = 0; i < numberAllCheckboxes; i++){
    var allCheckbox = allCheckboxes[i];
    // Determine if all checkbox is checked. If yes, expression evaluates to boolean true.
    if (allCheckbox.checked){
     // All checkbox is checked. Increment count of number of all checkboxes that are checked.
     numberAllCheckboxesChecked++;
    }
   }
   // Determine if number of all checkboxes that are checked equals zero. If yes, expression evaluates to boolean true.
   if (numberAllCheckboxesChecked === 0){
    // Number of all checkboxes that are checked equals zero. Check usage radio button.
    usageRadioBtn.checked = true;
   // Determine if number of all checkboxes that are checked is greater than zero and less than number of all checkboxes. If yes, expression evaluates to boolean true.
   } else if ((numberAllCheckboxesChecked > 0) && (numberAllCheckboxesChecked < numberAllCheckboxes)){
    // Number of all checkboxes that are checked is greater than zero and less than number of all checkboxes. Uncheck all privileges radio button and usage radio button.
    allPrivilegesRadioBtn.checked = false;
    usageRadioBtn.checked = false;
   // Determine if number of all checkboxes that are checked equals number of all checkboxes. If yes, expression evaluates to boolean true.
   } else if (numberAllCheckboxesChecked === numberAllCheckboxes){
    // Number of all checkboxes that are checked equals number of all checkboxes. Check all privileges radio button.
    allPrivilegesRadioBtn.checked = true;
   }
  }
 },

 // Get reference to elements in order to register event handlers.
 // NOTE: Elements (including the form element) accept events from child elements (including child elements of the form element) via event bubbling.
 selectUserAccntDropdown: document.getElementById("selectUserAccntDropdown"),
 window: window,
 userAccntPrivsFormResetBtn: document.getElementById("userAccntPrivsFormResetBtn"),
 userAccntPrivsForm: document.getElementById("userAccntPrivsForm")
};

// Register event handlers.
// Register User Accounts page | edit user account privileges | select user account form dropdown change event to handler that clicks User Accounts page | edit user account privileges | select user account form hidden submit button.
EditUserAccntPrivsUtil.selectUserAccntDropdown.addEventListener("change", EditUserAccntPrivsUtil.clickSelectUserAccntFormHiddenSubmitBtn, false);
// Register web browser window load event to handler that checks User Accounts page | edit user account privileges | user account privileges form radio button/checkboxes that correspond to selected user account privileges.
EditUserAccntPrivsUtil.window.addEventListener("load", EditUserAccntPrivsUtil.checkUserAccntPrivsFormRadioBtnCheckboxes, false);
// Register User Accounts page | edit user account privileges | user account privileges form reset button click event to handler that clicks User Accounts page | edit user account privileges | select user account form hidden submit button.
// NOTE: The User Accounts page | edit user account privileges | user account privileges form reset button default action unchecks, not resets, the user account privileges form radio button/checkboxes. Hence, the need to register the reset button click event to the same handler that checks the User Accounts page | edit user account privileges | user account privileges form radio button/checkboxes that correspond to selected user account privileges in the first place.
EditUserAccntPrivsUtil.userAccntPrivsFormResetBtn.addEventListener("click", EditUserAccntPrivsUtil.clickSelectUserAccntFormHiddenSubmitBtn, false);
// Register User Accounts page | edit user account privileges | user account privileges form click event to handler that coordinates User Accounts page | edit user account privileges | user account privileges form radio button/checkboxes checked/unchecked hierarchy per user clicks.
EditUserAccntPrivsUtil.userAccntPrivsForm.addEventListener("click", EditUserAccntPrivsUtil.coordinateCheckedUncheckedHierarchy, false);
