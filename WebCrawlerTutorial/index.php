<?php
  //download every page from dictionery
  $start = "http://localhost:8000/test.html";
  echo "Start URL: ".$start."\n";
  $already_crawled = array();

  function get_details($url){
    $options = array('http'=>array('method'=>"GET", 'headers'=>'User-Agent: howbot/0.1\n'));

    $context = stream_context_create($options);

    $doc = new DOMDocument();
    @$doc->loadHTML(@file_get_contents($url, false, $context));

    $title = $doc->getElementsByTagName("title");
    $title = $title->item(0)->nodeValue;
      //echo $title."\n";
    $description = "";
    $keywords = "";
    $metas = $doc->getElementsByTagName("meta");

    for($i = 0; $i<$metas->length; $i++){
      $meta = $metas->item($i);

      if($meta->getAttribute("name") == strtolower("description")){
        $description = $meta->getAttribute("content");
      }
      if($meta->getAttribute("name") == strtolower("keywords")){
        $keywords = $meta->getAttribute("content");
      }
    }
    echo $description."\n";
  }

  function follow_links($url){
    global $already_crawled;

    $options = array('http'=>array('method'=>"GET", 'headers'=>'User-Agent: howbot/0.1\n'));

    $context = stream_context_create($options);

    $doc = new DOMDocument();
    @$doc->loadHTML(@file_get_contents($url, false, $context));
    $linklist = $doc->getElementsByTagName("a");

    foreach ($linklist as $link) {
      $l = $link->getAttribute("href");
      //echo "old one: ".$l."\n";
      if(substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//"){
        $l = parse_url($url)["scheme"]."://".parse_url($url)["host"].$l;
      }elseif (substr($l, 0, 2) == "//") {
        $l = parse_url($url)["scheme"].":".$l;
      }elseif (substr($l, 0, 2) == "./") {
        $l = parse_url($url)["scheme"]."://".parse_url($url)["host"].dirname(parse_url($url)["path"]).substr($l,2);
      }elseif (substr($l, 0, 1) == "#") {
        $l = parse_url($url)["scheme"]."://".parse_url($url)["host"].parse_url($url)["path"].$l;
      }elseif (substr($l, 0, 3) == "../") {
        $l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
      }elseif (substr($l, 0, 11) == "javascript:") {
        continue;
      }elseif (substr($l, 0, 4) != "http") {
        $l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
      }

      if(!in_array($l, $already_crawled)){
        $already_crawled[] = $l;
        echo get_details($l);
        echo $l."\n";
      }
    }

    //echo $l."\n"; // immer nur der letzte bleibt da

  }

  follow_links($start);
  print_r($already_crawled);
