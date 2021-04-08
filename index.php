<!DOCTYPE html>
<html>

<head>
  <title>Prototype PHP API</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
  <style>
    #result {
      margin-top: 20px;
    }
  </style>
  <h1>Prototype PHP API. Input is URL of podcast. Output is an array of strings, the URLs of possible mp4s, mp3s to download. </h1>
  <form id="form" action="">
    <input name="url" id="url" required />
    <button>Submit</button>
  </form>

  <button id="spotify-login">Login with spotify</button>

  <div id="result"></div>
  <script src="./assets/js/main.js" ></script>
</body>

</html>