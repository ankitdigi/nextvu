<!DOCTYPE html>
<html class="js">
	<head>
		<title>NWL Serum Test Result</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro.woff"); ?>') format("woff");font-weight:400;font-style:normal;font-display:swap}
		@font-face{font-family:'MarkPro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff"); ?>') format("woff");font-weight:500;font-style:normal;font-display:swap}
		@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff"); ?>') format("woff");font-weight:700;font-style:normal;font-display:swap}
		@page{margin:0}
		*{margin:0;padding:0;box-sizing:border-box;font-family:'Mark Pro'}
		img{max-width:100%}
		html{scroll-behavior:smooth}
		body{font-family:'Mark Pro'}
		table{font-family:'Mark Pro'}
		div{font-family:'Mark Pro'}
		.header th{text-align:left}
		.bargraph{list-style:none;width:100%;position:relative;margin:0;padding:0}
		.bargraph li{position:relative;height:21px;margin-bottom:6px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
		.bargraph li.grey{background:#ccc}
		.bargraph li.red{background:red}
		.bargraph li span{display:block}
		.foodbargraph{list-style:none;width:100%;position:relative;margin:0;padding:0}
		.foodbargraph li{position:relative;height:19.6px;margin-bottom:5px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
		.foodbargraph li.grey{background:#ccc}
		.foodbargraph li.red{background:red}
		.foodbargraph li span{display:block}
		</style>
	</head>
	<body bgcolor="#fff">