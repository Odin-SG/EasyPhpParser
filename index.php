<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="" method="POST">
    <input type="text" name="word">
    <input type="submit" value="Вывести">
</form>

<?php
require_once 'derivedParser.php';

$parser = new DerivedParser($_POST['word'], 0, 18);

while($element = $parser->searchMorePage('div', 'class="organic__url-text"')){
    echo $element;
}
?>
</body>
</html>
