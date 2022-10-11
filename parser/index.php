<?php declare(strict_types=1);

use Parser\HtmlTagCounter;
use Parser\HtmlTokenizer;

require_once __DIR__ . '/../vendor/autoload.php';

$url = $_GET['url'] ?? 'https://example.com';

$text = @file_get_contents($url);

$tokenizer = new HtmlTokenizer($text);
$tokens = $tokenizer->getTokens();

$htmlTagCounter = new HtmlTagCounter($tokens);
$tagCounts = $htmlTagCounter->getCounts();

?>

<html>
<head>
  <title>Tag Counter</title>
  <style type="text/css">
      form {
          margin: 0;
      }
      .form {
          padding: 10px;
      }
      .form, .result-block {
          display: inline-block;
          margin: 10px;
          border: 1px solid black;
      }
      .btn-submit {
          margin-top: 5px;
      }
      .result-block .header {
          border-bottom: 1px solid black;
          margin: 5px;
          font-weight: bold;
      }
      .result-block .content {
          margin: 5px;
      }
      .result-table th {
          border-bottom: 1px solid black;
      }
  </style>
</head>
<body>
<h1 style="margin: 10px; display: inline">HTML Tag Counter</h1><br>
<div class="form">
  <form>
    <div class="form-group">
      <input
        type="text"
        class="form-control"
        id="url"
        name="url"
        value="<?= htmlspecialchars($url) ?>"
        aria-describedby="urlHelp"
        placeholder="Enter URL"
      >
      <small id="urlHelp" class="form-text text-muted">Enter URL to HTML file.</small>
    </div>
    <button type="submit" class="btn-submit">Submit</button>
  </form>
</div><br>
<div class="result-block">
  <div class="header"><span>Result</span></div>
  <div class="content">
    <span>URL: <?= htmlspecialchars($url) ?></span>
    <table class="result-table">
      <tr>
        <th>Tag name</th>
        <th>Count</th>
      </tr>
      <?php foreach ($tagCounts as $name => $count): ?>
        <tr>
          <td><?= htmlspecialchars($name) ?></td>
          <td style="text-align: center"><?= htmlspecialchars($count) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<body>
</html>
