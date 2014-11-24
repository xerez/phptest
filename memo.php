<?php

Class Memo{
  //dataディレクトリ以下を検索
  function getFileList($dir) {
      $i = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
   
      //ファイルかどうかを調べるにはisFile()、ディレクトリかどうかを調べるにはisDir()
      foreach ($i as $fileinfo) { 
          if ($fileinfo->isFile()) {
              $filelist[] = ltrim($fileinfo->getPathname(), $dir .'/' );
          }
      }

      return $filelist;
  }

  //リンクを貼る
  function getLinkFileList($dir){
   
    $a = $this -> getFileList($dir);
  
    //ファイルリストをまわして、ファイル名にリンクを貼る
    foreach ($a as $filename) {
      $filelink[] = "<a href=\"".$dir."/".$filename."\">{$filename}</a>";
    }
  
    return $filelink;
  }

}
/**
$memo = new Memo();
$filelink = $memo -> getLinkFileList("data");
var_dump($filelink);
**/
