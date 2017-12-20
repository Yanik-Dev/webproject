<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("display_errors", 1);

require '../services/autoload.php';

$session = SessionService::getActiveSession("user");
?>
<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title><?= $title ?> </title>
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css" />
  <script src="./assets/lib/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script> -->

    <link rel="stylesheet" href="./assets/lib/semantic-ui/semantic.min.css" />
    <link rel="stylesheet" href="./assets/lib/lightbox/css/lightbox.min.css" />
    <link rel="stylesheet" href="./assets/css/styles.css" />
    <script src="./assets/lib/jquery-3.2.1.min.js"></script>
    <script src="./assets/lib/lightbox/js/lightbox.min.js"></script>
    <script src="./assets/lib/debounce.js"></script>
    <script src="./assets/lib/mustache.min.js"></script>
    <script src="./assets/lib/semantic-ui/semantic.min.js"></script>
<body class="">
<div class="ui app-level nag">
  <span class="title">
    You are offline
  </span>
  <i class="close icon"></i>
</div>