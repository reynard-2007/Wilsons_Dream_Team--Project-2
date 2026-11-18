<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="DTShop, PC components, careers, COS10026">
    <meta name="author" content="Wilson's Dream Team">

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles/styles.css">

    <title><?php echo $title; ?></title>
    <?php
    if (isset($embedded_css)) {
        echo "<style>" . $embedded_css . "</style>";
    }
    ?>
</head>
<body>
<header>
    <a href="index.php"><img src="images/logo.png" alt="DTShop logo - Digital Tech Components" width="200"></a>
    <?php require_once("nav.inc"); ?>
</header>