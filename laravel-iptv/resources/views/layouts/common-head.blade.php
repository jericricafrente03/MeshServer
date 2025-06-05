<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<!--favicon-->
	<link rel="icon" href="/source-images/images/meshtv_logo.png" sizes="32x32" />
	<link rel="icon" href="/source-images/images/meshtv_logo.png" sizes="192x192" />
	<link rel="apple-touch-icon" href="/source-images/images/meshtv_logo.png" />
	
	<!--plugins-->
	<link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="/assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
	<link href="/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
	<link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!--wysiwyg-->
    <!-- <script src="https://cdn.tiny.cloud/1/{{config('app.tiny_mce_api_key')}}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> -->
	<!-- loader-->
	<link href="/assets/css/pace.min.css" rel="stylesheet" />
	<script src="/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="/assets/css/app.css" rel="stylesheet">
	<link href="/assets/css/icons.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="/assets/css/dark-theme.css" />
	<link rel="stylesheet" href="/assets/css/semi-dark.css" />
	<link rel="stylesheet" href="/assets/css/header-colors.css" />
	<link rel="stylesheet" href="/css/avatars.css?t=<?=time()?>" />
	<link rel="stylesheet" href="/css/custom.css?t=<?=time()?>" />
	<link rel="stylesheet" href="/css/documents.css?t=<?=time()?>" />
	<link rel="stylesheet" href="/css/folders.css?t=<?=time()?>" />

    <!-- Fancy File Upload -->
    <link href="/assets/plugins/fancy-file-uploader/fancy_fileupload.css" rel="stylesheet" />

	<!-- DataTable CSS -->
	<link rel="stylesheet" href="/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" />
    <link href="/assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css" rel="stylesheet" />

    <link href="/assets/plugins/bs-stepper/css/bs-stepper.css" rel="stylesheet" />

    <!-- Calendar Table -->
    <link href="/assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

    <!-- Signature Pad -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-signature/1.2.2/jquery.signature.min.css"> --}}
    <link ref="stylesheet" type="text/css" href="/assets/plugins/keith-wood/css/jquery.signature.css">

	<!-- Summernote css -->
	<link href="/assets/plugins/summernote-0.8.18/summernote-lite.css" rel="stylesheet">

	<title>MeshTV | IPTV</title>
</head>

<body>