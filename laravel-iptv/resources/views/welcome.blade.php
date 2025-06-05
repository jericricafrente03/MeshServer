<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to MeshTV-IPTV</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!--favicon-->
	<link rel="icon" href="/source-images/images/meshtv_logo.png" sizes="32x32" />
	<link rel="icon" href="/source-images/images/meshtv_logo.png" sizes="192x192" />
	<link rel="apple-touch-icon" href="/source-images/images/meshtv_logo.png" />
  <style>
    body {
      /* background-color: #fff; */
      background-image: url("/source-images/images/whitespace.jpg");
      text-align:center;
    }

    .center-logo {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 550px;
    }

    .center-logo {
      display: flex;
      flex-direction: column;  /* Stack items vertically */
      justify-content: center; /* Center items horizontally */
      align-items: center;     /* Center items vertically */
      height: 100vh;           /* Full viewport height */
    }

    /* Logo image styles */
    .center-logo img {
        max-width: 100%;   /* Ensure the logo fits within its container */
        height: auto;      /* Maintain aspect ratio */
    }

    .center-logo .btn {
      margin-top: 20px; /* Adjust space between the logo and button as needed */
    }

    .btn-custom-login{
        background-color: #4d4d4d; 
        border-color: #4d4d4d;
        color: white;
    }
    .btn-custom-login:hover  {
        background-color: #ba1c24; 
        border-color: #ba1c24;
        color: white;
    }

  </style>
</head>
<body>

  <div class="center-logo">
      <img src="/source-images/images/meshtv_logo.png" alt="Logo" width="500" />
      <a href="/admin" class="btn btn-custom-login btn-lg" tabindex="-1" role="button" aria-disabled="true">IPTV Dashboard Login</a>
  </div>
   

</body>
</html>
