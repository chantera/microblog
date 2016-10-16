<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>My blog</title>
  <meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link href="./assets/css/style.css" rel="stylesheet">
</head>
<body>
  <div id="main">
    <p><?= h($message) ?> This is my blog.</p>
    <ul>
      <li><a href="./about">About Me</a></li>
      <li><a href="./posts">Posts</a> <mark>new</mark></li>
    </ul>
    <footer>
      <p class="copyright">© Copyright <?php echo date('Y'); ?> <?= h($author) ?>. All rights reserved.</p>
    </footer>
  </div>
</body>
</html>
