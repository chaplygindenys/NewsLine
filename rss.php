<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "classes/NewsDB.php";
$rssNews = new NewsDB();
$errMsg="";
$rssNews->getRss()
