<?php
/**
 * Header part.
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="An intelligent Bot who has learned to talk like Yoda">
	<meta name="author" content="Dan Anghel">

	<title><?= !empty($title) ? $title : CoreConfig::$app->name ?></title>

	<link rel="icon" type="image/png" href="<?= get_url('favicon.ico') ?>">
	<meta name="robots" content="noindex, nofollow">

	<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet">

	<link href="<?= get_url('assets/vendor/normalize.min.css?ver=3.0.2') ?>" rel="stylesheet">
	<link href="<?= get_url('assets/vendor/skeleton.min.css?ver=2.0.4') ?>" rel="stylesheet">
	<link href="<?= get_url('assets/app/custom.css?ver=' . CoreConfig::$app->ver) ?>" rel="stylesheet">
</head>

<body class="<?= is_mobile() ? 'mobile' : 'desktop' ?>">
