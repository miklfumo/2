<?php
/**
 * HTML head and opening tags
 */
$config = get_conference_config();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($config['title']) ?> &laquo;<?= e($config['name']) ?>&raquo; — <?= e($config['dates']) ?>, <?= e($config['venue']) ?>">
    <meta name="theme-color" content="#0a0c14">
    <meta name="robots" content="index, follow">
    <title><?= e($config['title']) ?> &laquo;<?= e($config['name']) ?>&raquo;</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
