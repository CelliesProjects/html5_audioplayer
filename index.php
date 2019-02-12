<?php
if (isset($_GET["folder"]))
{
    $path = htmlspecialchars($_GET["folder"]);

    if ( strpos( $path, ".." ) !== false ) die();         //no folder traversing

    if ( substr( $path, 0, 1 ) === "/" ) $path = '';      //no root folder access


    if ($path <> '') {
      $path = $path.'/';
      if (!file_exists($path))
      {
          header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
          echo "Requested resource could not be found.";
          die();
      }
      echo '<div id="upLink"><img class="folderIcon" src="?icon=folderup"></div>';
    }

    foreach (glob($path . "*", GLOB_ONLYDIR) as $filename)
    {
        $pieces = explode('/',$filename);
        echo '<div class="folderLink"><img class="folderIcon" src="?icon=folderdown">', $pieces[count($pieces)-1], '</div>';
    }

    $files = $path . "*.{mp3,ogg,wav,MP3,OGG,WAV}";

    foreach (glob($files, GLOB_BRACE) as $filename)
    {
        $pieces = explode('/',$filename);
        echo '<div class="fileLink"><img class="folderIcon saveButton" src="?icon=save">', $pieces[count($pieces)-1], '</div>';
    }
    die();
}

if (isset($_GET["icon"]))
{
    $icon = htmlspecialchars($_GET["icon"]);
    if ($icon == "delete")
    {
        header( "Content-Type: image/svg+xml" );
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4z"/><path fill="none" d="M0 0h24v24H0V0z"/></svg>';
        // This icon is found at https://material.io/tools/icons/?icon=delete_outline&style=baseline
        die();
    }
    if ($icon == "folderup")
    {
        header( "Content-Type: image/svg+xml" );
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M11 9l1.42 1.42L8.83 14H18V4h2v12H8.83l3.59 3.58L11 21l-6-6 6-6z"/></svg>';
        // This icon is found at https://material.io/tools/icons/?icon=subdirectory_arrow_left&style=baseline
        die();
    }
    if ($icon == "folderdown")
    {
        header( "Content-Type: image/svg+xml" );
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M19 15l-6 6-1.42-1.42L15.17 16H4V4h2v10h9.17l-3.59-3.58L13 9l6 6z"/></svg>';
        // This icon is found at https://material.io/tools/icons/?icon=subdirectory_arrow_right&style=baseline
        die();
    }
    if ($icon == "save")
    {
        header( "Content-Type: image/svg+xml" );
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>';
        // This icon is found at https://material.io/tools/icons/?icon=save&style=baseline
        die();
    }
}
if ( count($_GET) ) die('ERROR unknown request.');
?><!doctype HTML>
<html lang="en">
<head>
<title>Music</title>
<meta charset="utf-8">
<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=.7, maximum-scale=.7, user-scalable=no">
<link rel="icon" href="data:;base64,iVBORw0KGgo=">  <!--prevent favicon requests-->
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">  <!-- https://fonts.google.com/specimen/Roboto?selection.family=Roboto -->
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<style>
html{
    height: 100%;
    width: 100%;
    margin:0;
    padding:0;
    font-family: 'Roboto', sans-serif;
    font-size:x-large;
}
body{
    height:100%;
    min-height:100%;
    margin:0;
    padding:0;
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
    bottom:50px;
    width:50%;
    overflow-y:scroll;
}
#playList{
    position:absolute;
    top:40px;
    right:0;
    bottom:50px;
    width:50%;
    overflow-y:scroll;
}
#upLink, .fileLink, .folderLink{
    cursor:pointer;
    margin:5px;
    padding:5px;
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
    margin:5px;
    padding:5px;
    background-color:grey;
    color:white;
    white-space: nowrap;
    overflow:hidden;
    display: flex;
    align-items: center; /* align vertical */
}
.deleteButton, .folderIcon{
    background-color:red;
    margin:0 15px;
    min-width:40px;
    display:inline-block;
}
#player {
    position: absolute;
    bottom: 0;
    width: 100%;
    margin:0;
    padding:0;
}
</style>
</head>
<body>
<div id="currentPath"></div>
<div id="navList"></div>
<div id="playList"></div>
<audio controls autoplay id="player">Your browser does not support the audio element.</audio>
<script>
$( document ).ready( function()
{
    const scriptUrl = '?folder=';
    var currentFolder = '';
    var currentSong = undefined;

    function updatePlayList()
    {
        $('.playListLink').css('background-color', 'grey');
        if ( currentSong !== undefined )
            $('.playListLink').eq(currentSong).css('background-color', 'black');
    }

    $('#navList, #playList').css({"bottom":$('#player').height()});

    $( '#navList').load( scriptUrl );

    $('body').on('click','.folderLink',function()
    {
        var oldFolder = currentFolder; //save currentFolder for .fail callback
        if ( currentFolder ) currentFolder += '/';
        currentFolder += $(this).text();
        console.log("currentFolder: "+currentFolder);
        $.get( scriptUrl + encodeURI(currentFolder), function() {
          //alert( "success" );
        })
          .done(function(data) {
            $('#currentPath').html(currentFolder);
            $('#navList').html(data);
          })
          .fail(function() {
            $('#currentPath').html("ERROR! Unable to access "+currentFolder);
            currentFolder = oldFolder;
          })
          .always(function() {
            //alert( "finished" );
          });
    });

    $('body').on('click','.fileLink',function()
    {
        $('#playList').append('<p class="playListLink" data-path="'+currentFolder+'"><img class="deleteButton" src="?icon=delete">'+$(this).text()+'</p>');
        if ( player.paused )
        {
            if ( currentFolder )
                 player.src = currentFolder + '/' + $(this).text();
            else
                 player.src = $(this).text();

            currentSong = $('.playListLink').length-1;
            updatePlayList();
        }
    });

    $('body').on('click','#upLink',function()
    {
        if ( currentFolder.includes('/') )
            currentFolder = currentFolder.split( '/' ).slice( 0, -1 ).join( '/' );
        else
            currentFolder = '';
        $('#navList').load( scriptUrl + encodeURI(currentFolder) );
        $('#currentPath').html(currentFolder);
    });

    $('body').on('click','.playListLink',function()
    {
        currentSong = $(this).index('.playListLink');
        player.src = encodeURI( $(this).data('path') + '/' + $(this).text() );
        updatePlayList();
    });

    player.addEventListener('ended',function()
    {
        if ( currentSong < $('.playListLink').length-1 )
        {
            currentSong++;
            player.src = $('.playListLink').eq( currentSong ).data('path') + '/' + $('.playListLink').eq( currentSong ).text();
            updatePlayList();
            return;
        }
        player.src='';
        currentSong = undefined;
        updatePlayList();
    });

    $('body').on('click','.deleteButton',function(event)
    {
        event.stopPropagation();
        if ( currentSong == $(this).parent().index() )
        {
            player.pause();
            player.src = '';
            currentSong = undefined;
        }
        $(this).parent().remove();
        if ( currentSong > $(this).parent().index() )
        {
            currentSong--;
        }
        updatePlayList;
    });

    $('body').on('click','.saveButton',function(event)
    {
        const a = document.createElement("a");
        a.style.display = "none";
        document.body.appendChild(a);
        a.href = currentFolder+'/'+$(this).parent().text();
        a.setAttribute("download", $(this).parent().text());
        a.click();
        window.URL.revokeObjectURL(a.href);
        document.body.removeChild(a);        
        event.stopPropagation();
    });

});
</script>
</body>
</html>
