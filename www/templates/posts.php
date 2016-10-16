<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Posts | My blog</title>
  <meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link href="./assets/css/style.css" rel="stylesheet">
</head>
<body>
  <div id="main">
    <header id="header">
      <h1>#random</h1>
    </header>
    <ul class="message"></ul>
  </div>
  <script src="./assets/js/jquery-3.0.0.min.js"></script>
  <script src="./assets/js/app.js"></script>
  <script>
    $(function() {
      var messages = <?php echo json_encode($messages); ?>;
      App.init(messages);
    });
  </script>
</body>
</html>
