<?php
if (isset($_GET["folder"]))
{
    $path = htmlspecialchars($_GET["folder"]);

    if ( strpos( $path, ".." ) !== false ) die();         //no folder traversing

    if ( substr( $path, 0, 1 ) === "/" ) $path = '';      //no root folder access

    if ($path <> '') {
      $path = $path.'/';
      echo '<div><p id="upLink">BACK</p></div>';
    }

    foreach (glob($path . "*", GLOB_ONLYDIR) as $filename)
    {
        $pieces = explode('/',$filename);
        echo '<div><p class="folderLink">', $pieces[count($pieces)-1], '</p></div>';
    }

    $files = $path . "*.{mp3,ogg,wav,MP3,OGG,WAV}";

    foreach (glob($files, GLOB_BRACE) as $filename)
    {
        $pieces = explode('/',$filename);
        echo '<div><p class="fileLink">', $pieces[count($pieces)-1], '</p></div>';
    }
    die();
}
// icons are found at https://material.io/tools/icons/?icon=delete_outline&style=baseline
if (isset($_GET["icon"]))
{
    $icon = htmlspecialchars($_GET["icon"]);
    if ($icon == "delete")
    {
        header( "Content-Type: image/svg+xml" );
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4z"/><path fill="none" d="M0 0h24v24H0V0z"/></svg>';
        die();
    }
}
if ( count($_GET) ) die('ERROR unknown request.');
?><!doctype HTML>
<html lang="en">
<head>
<title>Music</title>
<meta charset="utf-8">
<link rel="icon" href="data:;base64,iVBORw0KGgo=">  <!--prevent favicon requests-->
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<style>

html{
    height: 100%;
    width: 100%;
    margin:0;
    padding:0;
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
.vertical-center{
    background-color:red;
    margin: 0;
    position: absolute;
    top: 50%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}
.deleteButton{
    background-color:red;
    margin:0 15px;
    width:24px;
    height:24px;
    display:inline-block;
}
audio {
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
<audio controls autoplay id="player">
Your browser does not support the audio element.
</audio>
<script>
$( document ).ready( function()
{
    const scriptUrl = '?folder=';
    var currentFolder = '';
    var currentSong = 0;

    $('#navList, #playList').css({"bottom":$('#player').height()});

    function updatePlayList()
    {
        $('.playListLink').css('background-color', 'grey');
        $('.playListLink').eq(currentSong).css('background-color', 'black');
    }

    $( '#navList').load( scriptUrl );

    $('body').on('click','.folderLink',function()
    {
        if ( currentFolder )
             currentFolder += '/';
        currentFolder += $(this).text();
        $('#currentPath').html(currentFolder);
        $( '#navList').load( scriptUrl + encodeURI(currentFolder) );
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
        currentSong++;
        player.src = $('.playListLink').eq( currentSong ).data('path') + '/' + $('.playListLink').eq( currentSong ).text();
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

});
</script>
</body>
</html>
