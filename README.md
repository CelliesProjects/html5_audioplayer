# html5_audioplayer
A simple single file html5 audio player.

### You will need:
- A working webserver with PHP enabled. 
<br>( Apache2.4, PHP > 5 )
- A folder with MP3s, OGGs and/or WAV files.

Just drop `index.php` in a folder containing MP3s, OGG or WAV files.
<br>
<br>It will provide a simple player with a browser area on the left and a playlist on the right.
<br>Navigate to and click on a song in the left pane to add it to the playlist.
<br>If there are already songs in the playlist the clicked song will be added to the end of the playlist.
<br>If no song is playing the clicked song start playing immediately.

### Easy setup:
With Apache 2.4, easiest setup is to symlink `htdocs` to the folder where you keep your music files.
<br>Copy `index.php` to this folder and you should be good to go.

### Disclaimer:
- The used icons are from [https://material.io/tools/icons/?style=baseline](material.io).
- Uses [https://fonts.google.com/specimen/Roboto](Google Roboto font)
- Uses [https://code.jquery.com/jquery-3.2.1.js](jQuery 3.2.1.)
