<?php
require_once '../src/Config/database.php'; 
require_once '../src/Controllers/BillController.php'; 

use Src\Controllers\BillController; 

header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="bill_export.xml"');

// Initialize the BillController
$billController = new BillController($connection);

$billId = $_GET['bill_id'] ?? null;
if (!$billId) {
    echo "<error>Bill ID is required</error>";
    exit;
}

// Retrieve the bill using the BillController
$bill = $billController->getBillById($billId);
if (!$bill) {
    echo "<error>Bill not found</error>";
    exit;
}

// Create XML document
$xml = new SimpleXMLElement('<bill/>');

// Populate XML with bill data
foreach ($bill as $key => $value) {
    $xml->addChild($key, htmlspecialchars($value));
}

// Output the XML
echo $xml->asXML();
?>
