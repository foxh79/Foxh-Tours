<?php

function connect()
{
    $conn = new mysqli("localhost", "root", "", "otrsphp");
    if (!$conn) die("Database is being upgraded!");
    return $conn;
}
$conn = connect();
if (!$conn) die("Under Construction!");

