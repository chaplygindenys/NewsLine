<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "classes/NewsDB.php";
$news = new NewsDB();
$errMsg="";
if($_SERVER["REQUEST_METHOD"] == "POST")
    require_once "save_news.inc.php";
if(isset($_GET["del"]))
    require_once "deleteNews.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        Новостная лента
    </title>
    <meta charset="utf-8"/>
</head>
<body>
<h1>последние новости</h1>
<?php
if($errMsg){
    echo "<h3>$errMsg</h3>";
}
?>
<form action="news.php" method="post">
       Заголовок новости:<br/>
    <label>
    <input type="text" name="title"/><br />
    Выберете категорию:<br />
    <select name ="category">
        <option value ="1">PHP</option>
        <option value ="2">JS</option>
        <option value ="3">SQL</option>
    </select>
    <br />
    Текст новости:<br/>
    <textarea name="description" cols="50"
              rows="5"></textarea><br />
    Источник<br />
    <input type="text" name="source"/><br />
    <input type="submit" value="Добавить" >
    </label>
</form>
<?php
require_once "get_news.inc.php";
?>
</body>
</html>
