<?php
if (isset($_GET["folder"]))
{
    $path = htmlspecialchars($_GET["folder"]);

    if ( strpos( $path, ".." ) !== false ) die();

    if ( substr( $path, 0, 1 ) === "/" ) $path = '';

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
?>
<!doctype HTML>
<html lang="en">
<head>
<title>Music</title>
<meta charset="utf-8">
<link rel="icon" href="data:;base64,iVBORw0KGgo=">  <!--prevent favicon requests-->
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<style>
body{
    height:100%;
    margin:0;
    padding:0;
}
#currentPath{
    position:absolute;
    top:0;
    left:0;
    right:0;
    height:20px;
    background-color:#8c1b1b;
    margin:5px;
    padding:5px;
    color:yellow;
}
#listContainer{
    position:absolute;
    top:20px;
    bottom:0;
    width:100%;
    margin:0;
    padding:0;
    overflow:hidden;
}
#navList{
    position:absolute;
    top:20px;
    left:0;
    bottom:80px;
    width:50%;
    overflow-y:scroll;
    float:left;
}
#playList{
    position:absolute;
    top:20px;
    right:0;
    bottom:80px;
    width:50%;
    overflow-y:scroll;
    float:left;
}
#playerArea{
    position:absolute;
    bottom:0;
    height:80px;
    left:0;
    right:0;
}
#songTitle{
    height:20px;
    background:red;
    margin:0;
    padding:0;
    text-align:center;
}
audio{
    margin:0;
    padding:0;
    width:100%;
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
}
</style>
</head>
<body>
<div id="currentPath"></div>
<div id="listContainer">
    <div id="navList"></div>
    <div id="playList"></div>
</div>
<div id="playerArea">
    <p id="songTitle"></p>
    <audio controls autoplay id="player">
    Your browser does not support the audio element.
    </audio>
</div>
<script>
$( document ).ready( function()
{
    const scriptUrl = '?folder=';
    var currentFolder = '';
    var currentSong = 0;

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
        $('#playList').append('<p class="playListLink">'+$(this).text()+'</p><p class="location" style="display:none;">' + currentFolder + '</p>');
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
        player.src = $('.location' ).eq(currentSong).text() + '/' + $('.playListLink' ).eq(currentSong).text();
        updatePlayList();
    });

    player.addEventListener('ended',function(e)
    {
        currentSong++;
        player.src = $('.location').eq( currentSong ).text() + '/' + $('.playListLink').eq( currentSong ).text();
        updatePlayList();
    });
});
</script>
</body>
</html>
