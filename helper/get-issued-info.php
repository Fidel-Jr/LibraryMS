<?php
require_once '../config/database.php';
require_once '../models/Issue.php';

$issue = new Issue($pdo);
$id = $_GET['id'] ?? null;

if (!$id) {
  echo json_encode(null);
  exit;
}

$data = $issue->getIssuedDetails($id); // You should have this method
echo json_encode($data);
