<?php
/**
 * interface INewsDB
 * содержит основные методы для работы с новостной лентой
 *
*/

interface INewsDB
{/**
 *добавление новой записи в новостную ленту
 *
 * @param string title - заголовок новости
 * @param string $category - заголовок новости
 * @param string $description - текст новости
 *@param string $sourse - источник новости
 *
 * @return boolean - результат успех/ошибка
 *
 */
 function  saveNews($title, $category, $description, $sourse);

 /**
  * Выборка всех частей из новостной ленты
  *
  * @return array -результат выборки вввиде массива
  */
   function getNews();
   /**
    * Удаление записи из новостной ленты
    * @param integer $id - идентификатор удаляемой записи
    * @return boolean - результат успех/ошибка
    */
   function  daleteNews($id);
}