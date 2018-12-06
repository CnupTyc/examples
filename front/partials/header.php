<?
define("P_IMAGES", "images/");
define("P_PICTURES", "pictures/");
define("P_PARTIALS", "partials/");
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="cmsmagazine" content="2c2951bb57cffc1481be768a629d3a6e" />
    <meta name="format-detection" content="telephone=no">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css') ?>">
</head>
<body data-page-type="<?=$pageType?>" class="<?=$pageClass?>-page">
<? require('partials/main/update-content/fixed-content.php'); ?>
<div class="js-fullpage">
