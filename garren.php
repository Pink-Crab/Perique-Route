<?php

$values = [ '\\', '/', '|', '-' , '#', '¬', '~', ':', PHP_EOL];

for ($i=0; $i < 12000000; $i++) { 
    echo $values[rand(0,(count($values)-1))];
}