<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$items =$news->getNews();
if($items === false) :
    $errMsg = "Произошла ошибка при выводе новостей ленты";
 elseif(!count($items)):
    $errMsg="Новостей нет ";
 else:
     foreach ($items as $item):
         $newsData = date("d-m-y H:i:s", $item["datetime"]);
         $description =nl2br($item["description"]);
          echo <<<EOL
                 <h3>{$item['title']}</h3>
                  <p>
                        $description<br />{$item['category']} @$newsData 
                  </p>
                  <p aling='right'>
                  <a href='news.php?del={$item["id"]}'>Удалить</a>
                  </p>
         EOL;
     endforeach;
endif;

