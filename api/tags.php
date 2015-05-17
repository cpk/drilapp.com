<?php

require_once 'dependencies.php';

$tagService = new TagService($conn);


$tagList  = array("Frazálne slovesá", "Frazalne slovesa", "Škola");

$tagService->createTags($tagList, 1 ,1 );