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
		echo '<div><p class="folderLink">', $filename, '</p></div>';
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
<style>
body, html{
height:100%;
margin:0;
padding:0;
}
#navList{
position:absolute;
top:20px;
padding:0 0 80px;
width:100%;
}
#currentPath{
position:fixed;
text-align:center;
background:red;
width:100%;
height:20px;
top:0px;
z-index:100;
}
#playerArea{
height:80px;
width:100%;
clear:both;
position:fixed;
bottom:0;
}
#songTitle{
height:20px;
background:red;
margin:0;
padding:0;
text-align:center;
}
#player{
height:60px;
background:#6cf;
width:100%;
margin:0;
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
</style>
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
</head>
<body>
<script>
$( document ).ready( function()
{
	const scriptUrl = '?folder=';
    var currentFolder = '';

    $( '#navList').load( scriptUrl );

	$('body').on('click','.folderLink',function()
	{
		$( '#navList').load( scriptUrl + encodeURI($(this).text()) );
		currentFolder = $(this).text();
		$('#currentPath').html(currentFolder);
		console.log('Folder clicked - current path: '+ currentFolder);
	});

	$('body').on('click','.fileLink',function()
	{
		var fileToPlay = currentFolder + '/' + $(this).text();
		$('#songTitle').html(fileToPlay);
		$('#player').attr('src', fileToPlay );
		console.log('Starting to play: ',fileToPlay);
    });

	$('body').on('click','#upLink',function()
	{
	    if ( currentFolder.includes('/') )
	    {
		    currentFolder = currentFolder.split( '/' ).slice( 0, -1 ).join( '/' );
		}
		else
		{
			currentFolder = '';
		}

		$('#navList').load( scriptUrl + encodeURI(currentFolder) );
		$('#currentPath').html(currentFolder);
		console.log('Uplink clicked - current path: '+ currentFolder);
	});
});
</script>
<div id="currentPath"></div>
<div id="navList"></div>
<div id="playerArea">
<p id="songTitle"></p>
<audio controls autoplay id="player">
Your browser does not support the audio element.
</audio>
</div>
</body>
</html>
