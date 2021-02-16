<?php
$title = $news->escape($_POST["title"]);
$category = abs((int)$_POST["category"]);
$description = $news->escape($_POST["description"]);
$sourse = $news->escape($_POST["source"]);

if(empty($title) or empty($description)){

    $errMsg="Заполните все поля формы!";

}else{
    if (!$news->saveNews($title, $category, $description, $sourse)){
        $errMsg="Произошла ошибка при добовление новости";
    }else{
        header("Location:news.php");
        exit;
    }
}

