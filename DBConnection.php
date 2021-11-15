<?php
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
if(!defined('db_file')) define('db_file',__DIR__.'./db/carrito_compras.db');
if(!defined('tZone')) define('tZone',"America/Bogota");
if(!defined('dZone')) define('dZone',ini_get('date.timezone'));
function my_udf_md5($string) {
    return md5($string);
}

Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        $this->open(db_file);
        $this->createFunction('md5', 'my_udf_md5');
        $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `admin_list` (
            `admin_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 
        $this->exec("CREATE TABLE IF NOT EXISTS `user_list` (
            `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `email` TEXT NOT NULL,
            `contact` TEXT NOT NULL,
            `address` TEXT NOT NULL,
            `department` TEXT NOT NULL,
            `type` TEXT NOT NULL,
            `level_section` TEXT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 

        $this->exec("CREATE TABLE IF NOT EXISTS `category_list` (
            `category_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `name` INTEGER NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `sub_category_list` (
            `sub_category_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `category_id` INTEGER NOT NULL,
            `name` INTEGER NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`category_id`) REFERENCES `category_list`(`category_id`) ON DELETE CASCADE
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `book_list` (
            `book_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `sub_category_id` INTEGER NOT NULL,
            `isbn` TEXT NOT NULL,
            `title` INTEGER NOT NULL,
            `author` TEXT NOT NULL,
            `description` TEXT NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`sub_category_id`) REFERENCES `sub_category_list`(`sub_category_id`) ON DELETE CASCADE
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `cart_list` (
            `user_id` INTEGER NOT NULL,
            `book_id` INTEGER NOT NULL,
            FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`) ON DELETE CASCADE,
            FOREIGN KEY(`book_id`) REFERENCES `book_list`(`book_id`) ON DELETE CASCADE
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `borrowed_list` (
            `borrowed_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `user_id` INTEGER NOT NULL,
            `transaction_code` TEXT NOT NULL,
            `due_date` DATE NULL DEFAULT NULL,
            `status` INTEGER NOT NULL Default 0,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`) ON DELETE CASCADE
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `borrowed_items` (
            `borrowed_id` INTEGER NOT NULL,
            `book_id` INTEGER NOT NULL,
            FOREIGN KEY(`borrowed_id`) REFERENCES `borrowed_list`(`borrowed_id`) ON DELETE CASCADE,
            FOREIGN KEY(`book_id`) REFERENCES `book_list`(`book_id`) ON DELETE CASCADE
        )");

        $this->exec("CREATE TRIGGER IF NOT EXISTS updatedTime_book AFTER UPDATE on `book_list`
        BEGIN
            UPDATE `book_list` SET date_updated = CURRENT_TIMESTAMP where book_id = book_id;
        END
        ");

        $this->exec("INSERT or IGNORE INTO `admin_list` VALUES (1,'Administrator','configuroweb',md5('1234abcd..'),1, CURRENT_TIMESTAMP)");

    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();