<?php
//error_reporting(E_ALL);
if(isset($_GET["folder"]))
{
  $path=rawurldecode($_GET["folder"]);

  if(strpos($path,"..")!==false)die();         //no folder traversing

  if(substr($path,0,1)==="/")$path = '';      //no root folder access

  if($path<>'')
  {
    $path=$path.'/';
    if(!file_exists($path))
    {
      header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true,404);
      echo "Requested resource could not be found.";
      die();
    }
    echo '<div id="upLink"><img class="folderIcon" src="?icon=folderup"></div>';
  }

  foreach(glob($path."*",GLOB_ONLYDIR)as$filename)
  {
    echo '<div class="folderLink">';
    $pieces=explode('/',$filename);
    if(glob($filename.'/*.{mp3,ogg,wav,MP3,OGG,WAV}',GLOB_BRACE))
      echo '<img class="folderIcon addFolder" src="?icon=addfolder">';
    else
      echo '<img class="folderIcon" src="">';

    echo $pieces[count($pieces)-1].'</div>';
  }

  $files = $path."*.{mp3,ogg,wav,MP3,OGG,WAV}";

  foreach(glob($files,GLOB_BRACE)as$filename)
  {
    $pieces=explode('/',$filename);
    echo '<div class="fileLink"><img class="folderIcon saveButton" src="?icon=save">'.$pieces[count($pieces)-1].'</div>';
  }
  die();
}

if(isset($_GET["icon"]))
{
  $icon=htmlspecialchars($_GET["icon"]);
  $header="Content-Type: image/svg+xml";
  if($icon=="delete")
  {
    header($header);
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4z"/><path fill="none" d="M0 0h24v24H0V0z"/></svg>';
    //https://material.io/tools/icons/?icon=delete_outline&style=baseline
    die();
  }
  if($icon=="folderup")
  {
    header($header);
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M11 9l1.42 1.42L8.83 14H18V4h2v12H8.83l3.59 3.58L11 21l-6-6 6-6z"/></svg>';
    //https://material.io/tools/icons/?icon=subdirectory_arrow_left&style=baseline
    die();
  }
  if($icon=="save")
  {
    header($header);
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>';
    //https://material.io/tools/icons/?icon=save&style=baseline
    die();
  }
  if($icon=="addfolder")
  {
    header($header);
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M14 10H2v2h12v-2zm0-4H2v2h12V6zm4 8v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM2 16h8v-2H2v2z"/></svg>';
    //https://material.io/tools/icons/?icon=playlist_add&style=baseline
    die();
  }
}
if(count($_GET)) die('ERROR unknown request.');
?><!doctype HTML>
<html lang="en">
<head>
<title><?php $_SERVER['HTTP_HOST'] ?></title>
<meta charset="utf-8">
<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=.7, maximum-scale=.7, user-scalable=no">
<link rel="icon" href="data:;base64,iVBORw0KGgo=">  <!--prevent favicon requests-->
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">  <!-- https://fonts.google.com/specimen/Roboto?selection.family=Roboto -->
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<style>
html{
  width: 100%;
  margin:0;
  padding:0;
  font-family: 'Roboto', sans-serif;
  font-size:x-large;
}
body{
  min-height:100%;
  margin:0;
  padding:0;
  background:darkgray;
}
#currentPath{
  position:absolute;
  top:0;
  left:0;
  right:0;
  height:30px;
  background-color:#8c1b1b;
  padding:5px 15px;
  color:yellow;
  display: flex;
  align-items: center; /* align vertical */
  font-size:larger;
}
#navList{
  position:absolute;
  top:40px;
  left:0;
  width:50%;
  overflow-y:auto;
}
#playList{
  position:absolute;
  top:40px;
  right:0;
  width:50%;
  overflow-y:auto;
}
#upLink, .fileLink, .folderLink{
  cursor:pointer;
  margin:5px 0;
  background-color:grey;
  color:yellow;
  white-space: nowrap;
  overflow:hidden;
  display: flex;
  align-items: center; /* align vertical */
}
.fileLink{
  color:white;
}
#upLink:hover, .fileLink:hover, .folderLink:hover{
  background-color:black;
}
.playListLink{
  position:relative;
  cursor:pointer;
  margin:5px 0;
  background-color:grey;
  color:white;
  white-space: nowrap;
  overflow:hidden;
  display: flex;
  align-items: center; /* align vertical */
}
.deleteButton, .folderIcon{
  background-color:red;
  margin:0 15px 0 0;
  min-width:40px;
  min-height:40px;
}
#player {
  position: absolute;
  bottom: 0;
  width: 100%;
  margin:0;
  padding:0;
}
.noselect {
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
}
</style>
</head>
<body>
<div id="currentPath" class="noselect"></div>
<div id="navList" class="noselect"></div>
<div id="playList" class="noselect"></div>
<audio controls autoplay id="player">Your browser does not support the audio element.</audio>
<script>
$(document).ready( function()
{
  const scriptUrl='?folder=';
  var currentFolder='';
  var tempFolder;
  var currentSong=undefined;
  var player=document.getElementById('player');

  function updatePlayList()
  {
    $('.playListLink').css('background-color','grey');
    if( currentSong!==undefined)
      $('.playListLink').eq(currentSong).css('background-color','black');
  }

  function updateNavList()
  {
    $.get(scriptUrl+currentFolder,function(){
      //alert("success");
    })
      .done(function(data){
        $('#currentPath').html(currentFolder);
        $('#navList').html(data).scrollTop(0);
      })
      .fail(function(){
        $('#currentPath').html("ERROR! Unable to access "+currentFolder);
        currentFolder = tempFolder;
      })
      .always(function(){
        //alert( "finished" );
      });
  };

  $('#navList, #playList').css({"bottom":$('#player').height()});

  $('#navList').load( scriptUrl );

  $('body').on('click','#upLink',function()
  {
    $('#currentPath').html("Loading...");
    if(currentFolder.includes('/'))
      currentFolder=currentFolder.split('/').slice(0, -1).join('/');
    else
      currentFolder='';

    updateNavList();
  });

  $('body').on('click','.folderLink',function()
  {
    $('#currentPath').html("Loading...");
    tempFolder=currentFolder; //save currentFolder for .fail callback
    if(currentFolder) currentFolder+='/';
    currentFolder+=$(this).text();
    updateNavList();
  });

  $('body').on('click','.fileLink',function()
  {
    $('#playList').append('<p class="playListLink" data-path="'+currentFolder+'"><img class="deleteButton" src="?icon=delete">'+$(this).text()+'</p>');
    if(player.paused)
    {
      if(currentFolder)
        player.src=currentFolder+'/'+$(this).text();
      else
        player.src=$(this).text();

      currentSong=$('.playListLink').length-1;
      updatePlayList();
    }
  });

  $('body').on('click','.playListLink',function()
  {
    currentSong=$(this).index('.playListLink');
    player.src=encodeURI($(this).data('path')+'/'+$(this).text());
    updatePlayList();
  });

  player.addEventListener('ended',function()
  {
    if(currentSong<$('.playListLink').length-1)
    {
      currentSong++;
      player.src=encodeURI($('.playListLink').eq(currentSong).data('path')+'/'+$('.playListLink').eq(currentSong).text());
      updatePlayList();
      return;
    }
    player.src='';
    currentSong=undefined;
    updatePlayList();
  });

  $('body').on('click','.deleteButton',function(event)
  {
    event.stopPropagation();
    if(currentSong==$(this).parent().index())
    {
      player.pause();
      player.src='';
      currentSong=undefined;
    }
    if(currentSong>$(this).parent().index())
      currentSong--;
    $(this).parent().remove();
    updatePlayList;
  });

  $('body').on('click','.saveButton',function(event)
  {
    const a=document.createElement("a");
    a.style.display="none";
    document.body.appendChild(a);
    a.href=currentFolder+'/'+$(this).parent().text();
    a.setAttribute("download",$(this).parent().text());
    a.click();
    window.URL.revokeObjectURL(a.href);
    document.body.removeChild(a);
    event.stopPropagation();
  });

  $('body').on('click','.addFolder',function(event)
  {
    var folderToAdd='';
    if(currentFolder) folderToAdd=currentFolder+'/';
    folderToAdd+=$(this).parent().text();
    $.get(scriptUrl+folderToAdd,function(data){
      //alert( "success" );
    })
      .done(function(data){
        //make an invisible navList
        const nList=document.createElement("div");
        nList.style.display="none";
        nList.id="invisFolder";
        document.body.appendChild(nList);
        $(nList).html(data);
        //add each .fileLink from the invisible navList
        var songBeforeAdd=$('.playListLink').length;
        $("#invisFolder .fileLink").each(function(index){
          $('#playList').append('<p class="playListLink" data-path="'+folderToAdd+'"><img class="deleteButton" src="?icon=delete">'+$(this).text()+'</p>');
        });
        document.body.removeChild(nList);
        if(player.paused) $('.playListLink').eq(songBeforeAdd).click();
      })
      .fail(function(){
        $('#currentPath').html("ERROR! Unable to preview "+folderToAdd);
      })
      .always(function(){
        //alert( "finished" );
      });
    event.stopPropagation();
  });
});
</script>
</body>
</html>
