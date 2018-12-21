<?php
/**
 * Created by PhpStorm.
 * User: christianheiler
 * Date: 18/12/2018
 * Time: 17:03
 */

require_once("include/render_class.php");

$render = new render_class();
"<html>\n";
echo $render->get_header("Testpage");




$myJson = new stdClass();
$myJson->title = "Test 1";
$myJson->title_link = "../test1/index.php";
$myJson->menu_position = 0;
$myJson->menu = array();
$myJson->menu[0] = new stdClass();
$myJson->menu[0]->text = "Menu item 1";
$myJson->menu[0]->link = "../test1/index2.php";
$myJson->menu[1] = new stdClass();
$myJson->menu[1]->text = "Menu item 2";
$myJson->menu[1]->link = "../test1/index2.php";
$myJson->menu[2] = new stdClass();
$myJson->menu[2]->text = "Menu item 2";
$myJson->menu[2]->link = "../test1/index2.php";

//print_r($myJson);

//echo json_encode($myJson);



//$render->create_html_menu("hello");

//print_r($render->get_menu_data());
echo "<body>\n";
echo $render->create_html_menu();



echo "</body>\n</html>";
echo "\n\n";