<?php
//error_reporting(E_ALL);
$showBitrate=true; //set to true to show bitrate,and install 'exiftool',check the README.md
$versionString="v1.0.1";
if(isset($_GET["folder"])){
  $path=rawurldecode($_GET["folder"]);
  if(strpos($path,"..")!==false)die();//no folder traversing
  if(substr($path,0,1)==="/")$path='';//no root folder access

  if($path<>''){
    $path=$path.'/';
    if(!file_exists($path)){
      header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found",true,404);
      die("Requested resource could not be found.");
    }
    echo '<div id="upLink"><img class="folderIcon" src="?icon=folderup"></div>';
  }
  $validFiles="*.{[Mm][Pp]3,[Oo][Gg][Gg],[Ww][Aa][Vv]}";
  foreach(glob($path."*",GLOB_ONLYDIR)as$filename){
    echo '<div class="folderLink">';
    $pieces=explode('/',$filename);
    if(glob($filename.'/'.$validFiles,GLOB_BRACE))
      echo '<img class="folderIcon addFolder" src="?icon=addfolder">';
    else
      echo '<img class="folderIcon" src="">';
    echo $pieces[count($pieces)-1].'</div>';
  }
  foreach(glob($path.$validFiles,GLOB_BRACE)as$filename){
    $pieces=explode('/',$filename);
    echo '<div class="fileLink"><img class="folderIcon saveButton" src="?icon=save">'.$pieces[count($pieces)-1].'</div>';
  }
  die();
}
if(isset($_GET["bitrate"])){
  system("exiftool -AudioBitrate -NominalBitrate -s -s -s './".rawurldecode($_GET["bitrate"])."'");
  die();
}
if(isset($_GET["icon"])){
  if(in_array($_GET["icon"],array('delete','folderup','save','addfolder','play','pause','previous','next','clearlist'))){
    header("Content-Type:image/svg+xml");
    switch($_GET["icon"]){
      case 'delete'://https://material.io/tools/icons/?icon=delete_outline&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4z"/><path fill="none" d="M0 0h24v24H0V0z"/></svg>');
      case 'folderup'://https://material.io/tools/icons/?icon=subdirectory_arrow_left&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M11 9l1.42 1.42L8.83 14H18V4h2v12H8.83l3.59 3.58L11 21l-6-6 6-6z"/></svg>');
      case 'save'://https://material.io/tools/icons/?icon=save&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>');
      case 'addfolder'://https://material.io/tools/icons/?icon=playlist_add&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M14 10H2v2h12v-2zm0-4H2v2h12V6zm4 8v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM2 16h8v-2H2v2z"/></svg>');
      case 'play'://https://material.io/tools/icons/?icon=play_arrow&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
      case 'pause'://https://material.io/tools/icons/?icon=pause&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
      case 'previous'://https://material.io/tools/icons/?icon=skip_previous&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
      case 'next'://https://material.io/tools/icons/?icon=skip_next&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
      case 'clearlist'://https://material.io/tools/icons/?icon=delete_sweep&style=baseline
        die('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15 16h4v2h-4zm0-8h7v2h-7zm0 4h6v2h-6zM3 18c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V8H3v10zM14 5h-3l-1-1H6L5 5H2v2h12z"/><path fill="none" d="M0 0h24v24H0z"/></svg>');
    }
  }
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  die();
}
if(count($_GET))die('ERROR unknown request.');
?><!doctype HTML>
<html lang="en">
<head>
<title><?php $_SERVER['HTTP_HOST'] ?></title>
<meta charset="utf-8">
<meta name="viewport" content="minimal-ui,width=device-width,initial-scale=0.7,user-scalable=no">
<link rel="icon" href="data:;base64,iVBORw0KGgo=">  <!--prevent favicon requests-->
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">  <!-- https://fonts.google.com/specimen/Roboto?selection.family=Roboto -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<style>
html{
  width:100%;
  margin:0;
  padding:0;
  font-family:'Roboto',sans-serif;
  font-size:x-large;
}
body{
  min-height:100%;
  min-width:400px;
  margin:0;
  padding:0;
  background:darkgray;
  color:white;
  opacity:0.85;
}
a{
  color:white;
  text-decoration:none;
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
  display:flex;
  align-items:center;
  white-space:nowrap;
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
#upLink,.fileLink,.folderLink{
  cursor:pointer;
  margin:5px 0;
  background-color:grey;
  color:yellow;
  white-space:nowrap;
  overflow:hidden;
  display:flex;
  align-items:center;
}
.fileLink{
  color:white;
}
#upLink:hover,.fileLink:hover,.folderLink:hover{
  background-color:black;
}
.playListLink{
  position:relative;
  cursor:pointer;
  margin:5px 0;
  background-color:grey;
  color:white;
  white-space:nowrap;
  overflow:hidden;
  display:flex;
  align-items:center;
}
.folderIcon,.actionIcon{
  background-color:red;
  margin:0 15px 0 0;
  min-width:40px;
  min-height:40px;
}
.actionIcon{
  background-color:#8c1b1b;
  cursor:pointer;
  margin:0 15px;
}
#player{
  display:none;
}
#playerControls{
  position:absolute;
  bottom:0;
  width:100%;
  margin:0;
  padding:0;
  background-color:#8c1b1b;
}
#slider{
  -webkit-appearance:none;
  width:100%;
  height:10px;
  border-radius:5px;
  background:#d3d3d3;
  outline:none;
  opacity:0.7;
  -webkit-transition:opacity .15s ease-in-out;
  transition:opacity .15s ease-in-out;
}
@media screen and (max-width:600px){
  #slider{display:none;}
  #currentTime{margin:0 0 0 auto;}
}
@media screen and (min-width:600px){
  #slider{display:inline-block;}
  #currentTime{margin:0 0 0 15px;}
}
#currentPlaying{
  display:flex;
  padding:0 15px;
  overflow:hidden;
  white-space:nowrap;
  color:white;
}
#controlArea{
  display:flex;
  align-items:center;
}
.noselect{
  -webkit-touch-callout:none; /* iOS Safari */
    -webkit-user-select:none; /* Safari */
     -khtml-user-select:none; /* Konqueror HTML */
       -moz-user-select:none; /* Firefox */
        -ms-user-select:none; /* Internet Explorer/Edge */
            user-select:none; /* Non-prefixed version,currently
                                  supported by Chrome and Opera */
}
</style>
</head>
<body>
<div id="currentPath" class="noselect"></div>
<div id="navList" class="noselect"></div>
<div id="playList" class="noselect"></div>
<div id="playerControls" class="noselect">
<div id="currentPlaying"><a href="https://github.com/CelliesProjects/html5_audioplayer" target="_blank">html5_audioplayer <?php echo $versionString ?></a></div>
<div id="controlArea"><img id="previousButton" class="actionIcon" src="?icon=previous"><img id="playButton" class="actionIcon" src="?icon=play"><img id="nextButton" class="actionIcon" src="?icon=next"><input type="range" min="0" max="0" value="0" class="" id="slider"><p id="currentTime"></p><img id="clearList" class="actionIcon" src="?icon=clearlist"></div>
<audio controls autoplay id="player">Your browser does not support the audio element.</audio>
<script>
const folderUrl='?folder=';
var currentFolder='';
var currentSong=undefined;
var player=document.getElementById('player');
var scrollPos=[]; //array to keep track of nested folders
$(document).ready(function(){
  $('#navList,#playList').css({"bottom":$('#playerControls').height()});
  updateNavList('',true);
});
function updatePlayList(){
  $('.playListLink').css('background-color','grey');
  if(currentSong!==undefined)
    $('.playListLink').eq(currentSong).css('background-color','black');
}
function updateNavList(folder,restoreScroll){
  $.get(folderUrl+encodeURIComponent(folder),function(){
  })
  .done(function(data){
    $('#currentPath').html(folder);
    currentFolder=folder;
    $('#navList').html(data).scrollTop(0);
  })
  .fail(function(){
    $('#currentPath').html("ERROR! Unable to access "+folder);
  })
  .always(function(){
    if(restoreScroll)
      $('#navList').scrollTop(scrollPos.pop()); //pop from stack
    $('#navList').css({"opacity":1});
  });
};
function resetPlayer(){
  player.src='';
  slider.value=0;
  $('#currentPlaying').html('&nbsp;');
  $('#currentTime').html('');
}
$('body').on('click','#upLink',function(){
  $('#navList').css({"opacity":0.5});
  $('#currentPath').html("Loading...");
  if(currentFolder.includes('/'))currentFolder=currentFolder.split('/').slice(0,-1).join('/');
  else currentFolder='';
  updateNavList(currentFolder,true);
});
$('body').on('click','.folderLink',function(){
  $('#currentPath').html("Loading...");
  $('#navList').css({"opacity":0.5});
  scrollPos.push($('#navList').scrollTop()); //push on stack
  var newFolder=currentFolder;
  if(newFolder)newFolder+='/';
  newFolder+=$(this).text();
  updateNavList(newFolder);
});
$('body').on('click','.fileLink',function(){
  $('#playList').append('<p class="playListLink" data-path="'+currentFolder+'"><img class="deleteButton folderIcon" src="?icon=delete">'+$(this).text()+'</p>');
  if(player.paused){
    currentSong=$('.playListLink').length-1;
    $('.playListLink').eq(currentSong).click();
    updatePlayList();
  }
});
$('body').on('click','.playListLink',function(){
  currentSong=$(this).index('.playListLink');
  player.src=encodeURI($(this).data('path')+'/'+$(this).text());
  updatePlayList();
});
$('body').on('input','#slider',function(){
  if(player.paused)return;
  player.currentTime=this.value;
});
$('body').on('click','.deleteButton',function(e){
  if(currentSong==$(this).parent().index()){
    player.pause();
    resetPlayer();
    currentSong=undefined;
  }
  if(currentSong>$(this).parent().index())currentSong--;
  $(this).parent().remove();
  updatePlayList;
  e.stopPropagation();
});
$('body').on('click','.saveButton',function(e){
  const a=document.createElement("a");
  a.style.display="none";
  document.body.appendChild(a);
  a.href=currentFolder+'/'+$(this).parent().text();
  a.setAttribute("download",$(this).parent().text());
  a.click();
  window.URL.revokeObjectURL(a.href);
  document.body.removeChild(a);
  e.stopPropagation();
});
$('body').on('click','.addFolder',function(e){
  var folderToAdd='';
  if(currentFolder)folderToAdd=currentFolder+'/';
  folderToAdd+=$(this).parent().text();
  $.get(folderUrl+encodeURIComponent(folderToAdd))
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
      $('#playList').append('<p class="playListLink" data-path="'+folderToAdd+'"><img class="deleteButton folderIcon" src="?icon=delete">'+$(this).text()+'</p>');
    });
    document.body.removeChild(nList);
    if(player.paused)$('.playListLink').eq(songBeforeAdd).click();
  })
  .fail(function(){
    $('#currentPath').html("ERROR! Unable to add "+folderToAdd);
  });
  e.stopPropagation();
});
$('body').on('click','#playButton',function(){
  if(player.paused&&currentSong!==undefined)player.play();
  else player.pause();
});
$('body').on('click','#previousButton',function(){
  if(currentSong>0){
    currentSong--;
    updatePlayList();
    $('.playListLink').eq (currentSong).click();
  }
});
$('body').on('click','#nextButton',function(){
  if(currentSong<$('.playListLink').length-1){
    currentSong++;
    updatePlayList();
    $('.playListLink').eq (currentSong).click();
  }
});
$('body').on('click','#clearList',function(){
  player.pause();
  resetPlayer();
  currentSong=undefined;
  $('#playList').html('');
});

$('body').keypress(function(e){
  if(e.key===" "){
    playButton.click();
    e.preventDefault();
  }
});
player.addEventListener('ended',function(){
  $('#currentTime').html();
  if(currentSong<$('.playListLink').length-1){
    currentSong++;
    updatePlayList();
    $('.playListLink').eq(currentSong).click();
    return;
  }
  currentSong=undefined;
  resetPlayer();
  updatePlayList();
});
player.addEventListener('play',function(){
  if(player.currentTime!==0)return;
  $('#currentPlaying').html($('.playListLink').eq(currentSong).text());
  slider.max=player.duration;
  var thisSong;
  var nowPlaying=player.src;
  if($('.playListLink').eq(currentSong).data('path')!=undefined)thisSong=encodeURIComponent($('.playListLink').eq(currentSong).data('path'))+'/';
  thisSong+=encodeURIComponent($('.playListLink').eq(currentSong).text());
<?php if($showBitrate):?>
  $.get('?bitrate='+thisSong)
  .done(function(data){if(data&&nowPlaying==player.src)$('#currentPlaying').append(' - '+data);})
  .fail(function(){console.log('error getting bitrate');});
<?php endif;?>
});
player.addEventListener('playing',function(){
  $('#playButton').attr("src","?icon=pause");
});
player.addEventListener('pause',function(){
  $('#playButton').attr("src","?icon=play");
});
player.addEventListener('timeupdate',function(){
  if(player.paused)return;
  slider.value=player.currentTime;
  var now=new Date(null);
  now.setSeconds(player.currentTime);
  var currentTime=now.toISOString().substr(11,8);
  var total=new Date(null);
  total.setSeconds(player.duration);
  var duration=total.toISOString().substr(11,8);
  $('#currentTime').html(currentTime+"/"+duration);
});
</script>
</body>
</html>
