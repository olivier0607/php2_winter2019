<?php
// used for updates or one-off assignments
$person['firstName'] = 'Andrew';
$person['lastName']  = 'Caya';
$person['email']     = 'andrewscaya@yahoo.ca';
var_dump($person);

// used for initial assignments
$person = ['firstName' => 'Andrew',
            'lastName'  => 'Caya',
            'email'     => 'andrewscaya@yahoo.ca'];
var_dump($person);

// automatic assignment
$person   = [];
$person[] = 'Andrew';
$person[] = 'Caya';
$person[] = 'andrewscaya@yahoo.ca';
var_dump($person);

// used for updates or one-off assignments
$person['firstName'] = 'Olivier';
$person['lastName']  = 'Ngo';
$person['email']     = 'ngo.olivier.nam@gmail.com';

// use {} to identify a variable you want interpolated
echo "Name:\n First: {$person['firstName']} \nLast: {$person['lastName']}\n";