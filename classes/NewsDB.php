<?php

require_once "INewsDB.php";

class NewsDB implements INewsDB
{
    const DB_NAME = "news.db";
    const ERR_PROPERTY = "Worng property name";
    const RSS_NAME ="rss.xml";
    const RSS_TITLE = "Новостная лента";
    const RSS_LINK = "http://localhost:63342/15.02/index.php";
    private $_dataBase;

    function __construct()
    {

        $this->_dataBase = new SQLite3(self::DB_NAME, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        if (!filesize(self::DB_NAME)) {
            try {
                $sql = "CREATE TABLE msgs(id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT,
                    category INTEGER,
                    description TEXT,
                    source TEXT,
                    datetime INTEGER)";
               if (!$this->_dataBase->exec($sql))
                   throw new Exception("Error CREATE TABLE msgs");
               $sql = "CREATE TABLE category(
                    id INTEGER,
                    name TEXT)";
                if (!$this->_dataBase->exec($sql))
                    throw new Exception("Error CREATE TABLE category");
                $sql = "INSERT INTO category(id, name)
                    SELECT 1 as id, 'PHP-Hypertext Preprocessor' as name
                     UNION SELECT 2 as id, 'JS-JavaScript' as name
                     UNION SELECT 3 as id, 'SQL-Structured Query Language' as name";
                if (!$this->_dataBase->exec($sql))
                    throw new Exception("Error INSERT INTO category");
            }catch (Exception $e){
                die($e->getMessage());
            }

        }
    }

    function __destruct()
    {
        unset($this->_dataBase);
    }

    function __get($name)
    {
        if ($name == "db") {
            return $this->_dataBase;
            throw new Exception(self::ERR_PROPERTY);
        }
    }

    function __set($name, $value)
    {
        throw new Expection(self::ERR_PROPERTY);
    }

    function saveNews($title, $category, $description, $sourse)
    {$dateTime= time();
        $sql ="INSERT INTO msgs(title, category, description, source, datetime)
                          VALUES('$title', $category , '$description', '$sourse', $dateTime)";
        $result = $this->_dataBase->exec($sql);// true or false
        if(!$result)
            return false;
        $this->createRss();
        return true;
    }
    function DataBaseArray($data){
      $DataBaseArray=[];
      while( $row =$data->fetchArray(SQLITE3_ASSOC)) {
          $DataBaseArray[]=$row;
      }
      return $DataBaseArray;
    }
    function getNews()
    {
        $sql ="SELECT msgs.id as id, title,
                      category.name as category,
                      description, source, datetime
               FROM msgs, category 
               WHERE category.id = msgs.category
               ORDER BY msgs.id DESC";
        $items = $this->_dataBase->query($sql);
        if(!$items)
              return false;
        return $this->DataBaseArray($items);

    }

    function daleteNews($id)
    {
        $sql="DELETE FROM msgs WHERE id=$id";
        $this->_dataBase->exec($sql);
    }
    function escape($data){

        return $this->_dataBase->escapeString(trim(strip_tags($data)));
    }
    private function createRss(){
        $dom = new DOMDocument("1.0", "utf-8");/*<?xml version="1.0" encoding="utf-8"*/
        $dom->formatOutput = true; //форматирование документа
        $dom->preserveWhiteSpace=false; // форматирование  не в одну строку
        $rss = $dom->createElement("rss"); //создаем как в rss.txt
        $version = $dom->createAttribute("version");
        $version->value = '2.0';
        $rss->appendChild($version);
        $dom->appendChild($rss);
        //<rss version="2.0">

        $channel = $dom->createElement("channel");
        $title = $dom->createElement("title", self::RSS_TITLE);
        $link = $dom->createElement("link", self::RSS_LINK);
        //создаем элементы
        $channel->appendChild($title);
        $channel->appendChild($link);
        $rss->appendChild($channel);
        //вкладываем элементы
        //<channel>
        //  <title></title>
        //<link>http://///index.php</link>
        $lineNews = $this->getNews();
        if(!$lineNews) return false;
        // else
        foreach ($lineNews as $news) {
            $item = $dom->createElement("item");
            $title = $dom->createElement("title", $news['title']);
            $category = $dom->createElement("category", $news['category']);
            //создаем элементы
            $description = $dom->createElement("description");
            $cdata = $dom->createCDATASection($news['description']);
            $description->appendChild($cdata);
            //сщздаем и вкладываем
            $linkText = self::RSS_LINK . '?id=' . $news['id'];
            $link = $dom->createElement("link", $linkText);
            //создаем элементы
            $dateTime = date('r', $news['datetime']);
            $pubDate = $dom->createElement("pubDate", $dateTime);
            $item->appendChild($title);
            $item->appendChild($link);
            $item->appendChild($description);
            $item->appendChild($pubDate);
            $item->appendChild($category);
            $channel->appendChild($item);
        }
           $dom->save(self::RSS_NAME);




    }
}

