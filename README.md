# html5_audioplayer
A simple single file php/jQuery/html5 audioplayer. Works in all major browsers on pc and phone. Less than 15Kb download. Simple and fast.

This is how the player looks on a Samsung Galaxy S5 NEO Android phone.
<img src="screenshots/phone_panorama.png" width="600">

### You will need:
- A working Apache webserver with PHP enabled.<br>
Actual versions should not matter that much but the player is developed against Apache 2.4 and PHP 7.
- A folder with MP3s, OGGs and/or WAV files.

### Easy setup:
With Apache 2.4, easiest setup is to symlink `htdocs` to the folder where you keep your music files.
<br>Copy `index.php` to this folder and you should be good to go.

The player will provide a simple interface with a browser area on the left and a playlist on the right.

Navigate to and click on a file in the left pane to add it to the playlist.
<br>If there are already songs in the playlist the clicked song will be added to the end of the playlist.
<br>If the player is paused the clicked song starts playing immediately.


### Disclaimer:
- The used icons are from [material.io](https://material.io/tools/icons/?style=baseline).
- Uses [Google Roboto font](https://fonts.google.com/specimen/Roboto)
- Uses [jQuery 3.3.1](https://code.jquery.com/jquery-3.3.1.js).

### Some screenshots:
#### Android Samsung S5 NEO Chrome landscape
<img src="screenshots/phone_panorama.png" width="400">

#### Android Samsung S5 NEO Chrome portrait
<img src="screenshots/phone_portrait.png" width="200">

#### PC Linux Mint Chrome
<img src="screenshots/pc_linux_chrome.png" width="700">

#### PC Windows 10 Edge
<img src="screenshots/pc_windows_edge.png" width="700">

#### Iphone Safari landscape
<img src="screenshots/iphone_safari_landscape.png" width="400">

#### Iphone Safari portrait
<img src="screenshots/iphone_safari_portrait.png" width="200">
