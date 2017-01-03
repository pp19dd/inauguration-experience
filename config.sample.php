<?php

$key = "enter-key-here";
$lang = "en";

// rest is solid until we add some more languages
$text_all = json_decode(file_get_contents("text.json"), false);
$text = $text_all->$lang;
