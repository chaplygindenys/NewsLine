<?php
 $id = abs((int)$_GET["del"]);
 if($id){
     if(!$news->daleteNews($id)){
         $errMsg="Произошла ошибка при удалении новости";
     }else{
         header("Location:index.php");
         exit;
     }
 }
