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
    private $footer_lines = array();
    private $log = array();
    private $inRoot = false;
    private $rootDir = "../";



    public function __construct()
    {
        $this->meta_lines[] = '<meta http-equiv="Content-Type" charset="utf-8"/>';
        $this->meta_lines[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>';
        $this->meta_lines[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';

        $this->stylesheet_lines[] = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.0/dist/semantic.min.css">';
        $this->stylesheet_lines[] = '<link rel="stylesheet" type="text/css" href="./css/style.css">';

        $this->javascript_lines[] = '<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>';
        $this->javascript_lines[] = '<script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.0/dist/semantic.min.js"></script>';
        $this->javascript_lines[] = '<script type="text/javascript" src="./js/script.js"></script>';

        //Make sure Render is included at the right level
        if(file_exists("./include/render_class.php")){
            $this->inRoot = true;
            $this->rootDir = "./";
        }

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
        foreach($this->javascript_lines as $line){
            $send .= "\t$line\n";
        }



        $send .= "</head>\n";

        return $send;
    }


    public function add_header_meta($line){
        $this->meta_lines[] = $line;
    }

    public function add_header_js($line){
        $this->javascript_lines[] = $line;
    }

    public function add_header_stylesheet($line){
        $this->stylesheet_lines[] = $line;
    }

    public function set_title($title){
        $this->title = $title;
    }

    public function add_footer_js($line){
        $this->footer_lines[] = $line;
    }


    private function get_menu_data(){
        $data = array();
        foreach(glob($this->rootDir.'*/add_menu.json') as $datalocation) {
            //echo $datalocation."\n";
            $data[] = json_decode(file_get_contents($datalocation));
        }
        //clean the data

        $clean = array();

        foreach($data as $entry){
            //if the slot is already taken assign a large random number...
            //TODO: Improve slot generator...

            if(!isset($clean[$entry->menu_position])){
                $position = $entry->menu_position;
            } else {
                $position = $entry->menu_position;
                if($position == 0){
                    $position = 1;
                }
                $position = ($position+1)*rand(10,40);
            }
            $clean[$position] = $entry;
        }
        ksort($clean);
        return $clean;
    }

    public function get_html_menu($current = ""){
        //setting the scene:
        $html = "<body>\n";
        $html .= '<div class="ui container">'."\n";
        $html .= "\t".'<div class="ui stackable container menu">'."\n";
        $html .= "\t".'<div class="header item"><a href="'.$this->rootDir.'index.php"><i class="home icon"></i></a></div>'."\n";

        //loop through data array...
        $data = $this->get_menu_data();
        foreach($data as $entry){

            //check if Item has drop down...
            if(isset($entry->menu) && is_array($entry->menu)){
                //use dropdown logic
                $html .= "\t".'<div class="ui dropdown item">'."\n";
                if(isset($entry->title_link)){
                    //Create link in title
                    $html .= "\t\t".'<a href="'.$entry->title_link.'" class="item" target="_blank">'.$entry->title.'</a>'."\n";

                } else {
                    $html .= "\t\t".$entry->title."\n";
                }
                $html .= "\t\t".'<i class="dropdown icon"></i>'."\n";
                //loop through menu
                $html .= "\t\t".'<div class="menu">'."\n";
                foreach($entry->menu as $item){
                    $html .= "\t\t\t".'<div class="item"><a href="'.$item->link.'">'.$item->text.'</a></div>'."\n";

                }//END foreach entry->menu

                //close menu DIV
                $html .= "\t\t".'</div>'."\n";
                //close dropdown DIV
                $html .= "\t".'</div>'."\n";

            } else {
                //just create Top line
                //check if link is present
                if(isset($entry->title_link)){
                    $html .= "\t".'<a href="'.$entry->title_link.'" class="item" target="_blank">'.$entry->title.'</a>'."\n";
                } else {
                    $html .= "\t".'<p>'.$entry->title.'</p>'."\n";
                }

            }

        }//END foreach $data

        //close complete menu
        $html .= '</div>'."\n";

        //open the body for the display:
        $html .= "<div class=\"ui container\">\n";

        return $html;

    }//END function create_html_menu


    private function clean_menu_position($clean, $data){
        $asking_position = $data->menu_position;

        if(isset($clean[$asking_position])){
            $data->menu_position +=1;
            $this->clean_menu_position($clean, $data);
        }
        return $asking_position;

    }

    public function add_log($log_line){
        $this->log[] = $log_line;
    }

    /**
     * @return array
     */
    public function get_log(){
        return $this->log;
    }

    public function get_footer($include_log = false){
        $html = "<!-- footer -->\n";
        $html .= "</div><br>\n";
        foreach($this->footer_lines as $line){
            $html .= "\t$line\n";
        }
        //Logs?
        if($include_log){
            $html .= "<!-- LOGGING DATA: \n\n";

            foreach($this->log as $log_data){
                if(is_string($log_data)){
                    $html .= $log_data."\n";
                } elseif(is_array($log_data) || is_object($log_data)){
                    $html .= json_encode($log_data, JSON_PRETTY_PRINT);
                    $html .= "\n";
                }
            }

            $html .= "\n\n END LOGGING DATA-->\n\n";
        }

        $html .= "</body>\n\n</html>";

        return $html;
    }




}