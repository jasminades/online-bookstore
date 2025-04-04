<?php

require_once 'Database.php';
require_once 'UsersDAO.php';

UsersDAO::create("John Doe", "john@example.con", "password", "customer");


print_r(UsersDAO::getALL());

print_r(UsersDAO::getById(1));



