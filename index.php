<?php
  //download every page from dictionery
  $start = "https://de.langenscheidt.com/deutsch-arabisch/";

  function follow_links($url){
    $doc = new DOMDocument();
    $doc->loadHTML(file_get_contents($url));
    $linklist = $doc->getElementsByTagName("a");
    echo $url."\n";
    foreach ($linklist as $link) {
      $l = $link->getAttribute("href");

      if(substr($l, 19,1) == "/" && substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//" && parse_url($l, PHP_URL_HOST) == NULL){
        //$l = "The Ones: ".$l;
        $lin2 = $l;//substr($l, -2, -1);
        echo $lin2."\n";

        echo substr($lin2, 18)."\n";

      if(substr($lin2, 18, 1) != 'Z'){
        //$lin2 = str_replace(substr($lin2, 18), "B/", $lin2);
        $lin2 = "https://de.langenscheidt.com".$lin2;
        follow_links($lin2);
        //$i = "s";
        //echo $lin2;
      }
    }
    }
    echo $lin2; // immer nur der letzte

  }

  follow_links($start);
