<?php

require_once "INewsDB.php";

class NewsDB implements INewsDB
{
    const DB_NAME = "news.db";
    const ERR_PROPERTY = "Worng property name";
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
        return $this->_dataBase->exec($sql);// true or false

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
}

