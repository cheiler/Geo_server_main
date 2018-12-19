<?php
/**
 * Created by PhpStorm.
 * User: christianheiler
 * Date: 18/12/2018
 * Time: 17:36
 */

class render_class
{

    private $header_lines = array();
    private $meta_lines = array();
    private $title = "Geo Server";
    private $stylesheet_lines = array();
    private $javascript_lines = array();



    public function __construct()
    {
        $this->meta_lines[] = '<meta http-equiv="Content-Type" charset="utf-8"/>';
        $this->meta_lines[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>';
        $this->meta_lines[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';

        $this->stylesheet_lines[] = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.0/dist/semantic.min.css">';
        $this->stylesheet_lines[] = '<link rel="stylesheet" type="text/css" href="../css/style.css">';

        $this->javascript_lines[] = '<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>';
        $this->javascript_lines[] = '<script type="text/javascript" src="../js/script.js"></script>';

    }


    public function get_header(){
        $send = "<head>\n";
        foreach($this->meta_lines as $line){
            $send .= "\t$line\n";
        }
        $send .= "\t<title>$this->title</title>\n";
        foreach($this->stylesheet_lines as $line){
            $send .= "\t$line\n";
        }
        $send .= "</head>\n";

        return $send;
    }


    public function add_header($line){
        $this->header_lines[] = $line;
    }

    public function add_stylesheet($line){
        $this->stylesheet_lines[] = $line;
    }

    public function set_title($title){
        $this->title = $title;
    }


    public function get_menu_data(){
        $data = array();
        foreach(glob('./*/add_menu.json') as $datalocation) {
            //echo $datalocation."\n";
            $data[] = json_decode(file_get_contents($datalocation));
        }
        //clean the data

        $clean = array();

        if(!isset($clean[$data->menu_position])){
            $postion =
        }


        return $data;
    }

    public function create_html_menu($data){
        //setting the scene:
        $html = '<div class="ui container">'."\n";
        $html .= "\t".'<div class="ui stackable container menu">'."\n";
        $html .= "\t".'<div class="header item"><a href="index.php"><i class="home icon"></i></a></div>'."\n";


        echo $html;

    }


    private function clean_menu_position($clean, $data){
        $asking_position = $data->menu_position;

        if(isset($clean[$asking_position])){
            $data->menu_position +=1;
            $this->clean_menu_position($clean, $data);
        } else {
            return $asking_position;
        }
    }





}