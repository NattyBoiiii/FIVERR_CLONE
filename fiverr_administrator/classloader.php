<?php  
require_once 'classes/Database.php';
require_once 'classes/Offer.php';
require_once 'classes/Proposal.php';
require_once 'classes/User.php';
require_once 'classes/Categories.php';

$databaseObj = new Database();
$offerObj = new Offer();
$proposalObj = new Proposal();
$userObj = new User();
$categoriesObj = new Categories();

$userObj->startSession();
?>