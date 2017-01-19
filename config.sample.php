<?php

# define("DEV", true);

// can't be blank
$admin_password = "";

// touch the file at launch
$data_file = "inauguration.json";

// no trailing slash
$url = "http://localhost";

$key = "enter-key-here";
$lang = "en";

$version = "0.1";

// rest is solid until we add some more languages
$text_all = json_decode(file_get_contents("text.json"), false);
$text = $text_all->$lang;
