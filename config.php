<?php
/******************************************************
------------------Required Configuration---------------
Please edit the following variables so the forum can
work correctly.
******************************************************/

//We log to the DataBase
$link=mysql_connect('127.0.0.1', 'root', '');
mysql_select_db('mlmdb');

//Username of the Administrator
$admin='mlmdb';
$mycount=0;
/******************************************************
-----------------Optional Configuration----------------
******************************************************/

//Forum Home Page
//$url_home = 'lead_page.php?link=2';

//Design Name
$design = 'default';


/******************************************************
----------------------Initialization-------------------
******************************************************/
//include('init.php');
?>