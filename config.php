<?php

session_start();

$conn = new mysqli(
    "localhost",
    "tymahchukg",
    "RP93wQMDB!34WXD4",
    "tymahchukg"
);

if ($conn->connect_error) {
    die("Ошибка подключения к БД");
}

$conn->set_charset("utf8");