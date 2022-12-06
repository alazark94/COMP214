<?php

// Testing

require 'functions.php';
require 'Database.php';

$config = require('config.php');

$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

dd($db->query('SELECT * FROM users')->fetchAll());
