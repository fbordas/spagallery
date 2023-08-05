<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
  <title> <?php // Titles the window with the selected folder, if there is any
    echo !isset($_GET['folder']) ? "index" : $_GET['folder'];
?> </title>
  <!-- CSS contains inline PHP to add a rule that hides the scrollbar if media content is being displayed
     Additional PHP is executed if media is being shown to handle displaying the "sidebar" menu -->
  <style type="text/css">
    html body {
      background: black;
      color: white;
      margin: 0px !important;
      padding: 0px !important;
      font-size: 0;
      <?php if(isset($_GET['selected'])) {
        echo "overflow:hidden;";
      } ?>
    }

    a {
      color: #CCCCCC;
    }

    a:hover {
      color: green;
    }

    #content {
      font-size: 20pt;
    }

    hr {
      margin: 5px;
      padding: 0px;
    }

    h2 {
      margin: 0px !important;
    }

    #media {
      margin-right: 40px;
    }

    .navitem {
      width: 19%;
      border: 1px solid white;
      margin: 0px;
      padding: 0px;
      height: 40px;
      text-align: center;
      vertical-align: middle;
      display: inline-block;
      font-size: 20pt;
    }

    <?php if(isset($_GET['selected'])) {
      echo "#nav{font-size:0;padding:0px;position:fixed;
left: calc(100% - 40px);
      width: 100vh;
      transform-origin: left top;
      background: black;
      opacity: 0.2;
      transform: rotate(90deg) translateY(-100%);
      height: 40px;
      margin: 0px;
    }

    #nav:hover {
      opacity: 1.0;
    }

    "; } ?>
  </style>
  <script type="text/javascript">
    // Resizes viewport contents on window resize
    window.onload = window.onresize = function() {
      resize();
    }
    
    // Handles key press events
    window.onkeydown = function(e) {
      keypresses();
    }
    
    // Manages specific key presses
    function keypresses() {
      if (shiftPressed()) {
        return;
      }
      var key = window.event.keyCode;
      var tgt = "";
      switch (key) {
        case 37: // left arrow; previous media
          tgt = "prev";
          break;
        case 38: // up arrow; folder contents
          tgt = "index";
          break;
        case 39: // right arrow; next media
          tgt = "next";
          break;
        case 83: // "s" video play/pause
          tgt = "playpause";
          break;
        case 107: // "num +" video vol up
          tgt = "volup";
          break;
        case 109: // "num -" video vol down
          tgt = "voldn";
          break;
        case 219: // "[" character; first media in list
          tgt = "firstpic";
          break;
        case 221: // "]" character; last media in list
          tgt = "lastpic";
          break;
        case 191: // "/" (slash) character; random pic from list
          randomlink();
          return;
        case 220: // "\" (backslash) character; return to gallery menu
          window.location = window.location.origin + window.location.pathname;
          return;
      }
      var media = document.getElementById(tgt);
      if (media != null) {
        if (media.hasAttribute("href") == true) {
          window.location = media.getAttribute("href");
        }
      } else {
        var md = document.getElementById("media");
        if (tgt == "volup") {
          if (md.muted == true) {
            md.muted = false;
            return;
          }
          if (md.volume < 1.0) {
            md.volume = md.volume + 0.1;
          }
        }
        if (tgt == "voldn") {
          if (md.volume > 0.0) {
            if (md.volume.toPrecision(1) == 0.1) {
              md.muted = "muted";
              return;
            }
            md.volume = md.volume - 0.1;
          }
        }
        if (tgt == "playpause" && md.hasAttributes("seekable")) {
          playpause();
        }
      }
    }
    
    // If the Shift key is held while pressing another key, show an alert
    // with the keycode (for debugging or adding more shortcuts) and do
    // nothing else
    function shiftPressed() {
      var key;
      var isShift;
      if (window.event) {
        key = window.event.keyCode;
        isShift = !!window.event.shiftKey; // typecast to boolean
      } else {
        key = ev.which;
        isShift = !!ev.shiftKey;
      }
      if (isShift && key != 16) {
        alert(key);
        return true;
      } else {
        return false;
      }
    }
    
    // Name is self-explanatory
    function playpause() {
      var vid = document.getElementById("media");
      if (vid.paused) {
        vid.play();
      } else {
        vid.pause();
      }
    }
    
    // Name is self-explanatory
    function randomlink() {
      var elems;
      var pics;
      var vids;
      elems = document.getElementsByClassName("folderlink");
      if (elems.length == 0) {
        pics = Array.prototype.slice.call(document.getElementsByClassName("piclink"));
        vids = Array.prototype.slice.call(document.getElementsByClassName("vidlink"));
        elems = pics.concat(vids);
        if (elems.length == 0) {
          alert("nothing to show");
          return;
        }
      }
      var rnd = Math.floor((Math.random() * elems.length) + 1);
      var loc = elems[rnd - 1].getAttribute("href").substr(2);
      window.location = window.location.origin + window.location.pathname + loc;
    }
    
    // Name is self-explanatory
    function resize() {
      if (document.getElementById("media") == null) {
        return;
      }
      var img = document.getElementById("media");
      if (document.getElementById("imgw").value == "") {
        document.getElementById("imgw").value = img.width;
      }
      if (document.getElementById("imgh").value == "") {
        document.getElementById("imgh").value = img.height;
      }
      var owidth = document.getElementById("imgw").value; // console.log(owidth);
      var oheight = document.getElementById("imgh").value; // console.log(oheight);
      if (window.innerHeight < window.innerWidth) {
        img.height = window.innerHeight;
        img.width = matchWidth(owidth, oheight, img.height);
      } else {
        img.width = window.innerWidth - 43;
        img.height = matchHeight(owidth, oheight, img.width);
      }
    }

    // Name is self-explanatory
    function matchWidth(width, height, newHeight) {
      return (width * newHeight) / height;
    }

    // Name is self-explanatory
    function matchHeight(width, height, newWidth) {
      return (height * newWidth) / width;
    }
  </script>
</head>
<body style="padding:0px 10px;" align="center">
  <div id="nav">
    <div class="navitem">
      <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">home</a>
    </div> <?php if(!isset($_GET['folder'])) { echo "
		<hr />";  } ?> <?php
    // below: safeguards for individual exclusions
    // additional exclusions can be added by adding elements
    // to the $excl array
    $excl = array("System Volume Information"
        ,"\$RECYCLE.BIN"
      //, ""
    );
    if(isset($_GET['folder'])) { $curfolder = htmlspecialchars($_GET['folder']); }
    if(!isset($curfolder))
    {
        $dh = opendir("."); $activefolders = 0;
        while (false !== ($folder = readdir($dh)))
        { 
            if(!is_dir($folder)) { continue; }
            if ( $folder != "." && $folder != ".." && $folder != "Thumbs.db"
            && $folder != "player.swf" && $folder != "index.php"
            && !in_array($folder, $excl) )
            { $folderlist[] = $folder; $activefolders++; } 
        }
        if(isset($folderlist)) { sort($folderlist); }
        if(!isset($folderlist)) { die("no folders to load"); }
        closedir($dh);
        ?> <h2 align="center" id="content">
      <a href="javascript:void(0);" onclick="randomlink();">???</a> | <?php
        $inactive = false;
        for($folderit = 0; $folderit < sizeof($folderlist); $folderit++)
        { ?> <a class="folderlink" href="./?folder=
				<?php echo $folderlist[$folderit];
                ?>"> <?php $folderlist[$folderit]; ?> </a> <?php if($folderit + 1 < sizeof($folderlist))
                { echo " | "; } 
                ?> <?php }  ?>
    </h2> <?php
    }
    else
    {
        $dh = opendir("$curfolder");
        while (false !== ($file = readdir($dh)))
        { 
            if ( $file != "." && $file != ".." && $file != "Thumbs.db"
                && preg_match('/.(jpg|gif|png|jpeg)$/i', $file) && !is_dir($file) )
            { $filelist[] = $file; } 
        }
        closedir($dh);
        $dh = opendir("$curfolder");
        while (false !== ($file = readdir($dh)))
        { 
            if ( $file != "." && $file != ".." && $file != "Thumbs.db"
                && preg_match('/.(mp4|flv|webm|mov|avi)$/i', $file) && !is_dir($file) )
            { $filelist2[] = $file; } 
        }
        closedir($dh);
        if(isset($filelist)) { sort($filelist); }
        if(isset($filelist2))
        {
            sort($filelist2);
            for($fit = 0; $fit < sizeof($filelist2); $fit++)
            { $filelist[] = $filelist2[$fit]; }
        }
        ?> <div class="navitem">
      <a href="./">list</a>
    </div> <?php if(!isset($_GET['selected'])) { echo "
		<hr />";  } ?> <?php
        if(!isset($_GET['selected']) || !in_array($_GET['selected'], $filelist))
        {
            $videos = isset($filelist2) ? sizeof($filelist2) : 0;
            echo '
		<div id="content">
			<a href="javascript:void(0);" onclick="randomlink();">???</a> | ';
            for($it = 0; $it < sizeof($filelist) - $videos; $it++)
            {?> <a class="piclink" href="./?folder=
				<?php echo $curfolder;
                ?>&amp;selected=
				<?php echo $filelist[$it]; ?>" <?php if($it == 0)
                { echo ' id="firstpic"'; } else if($it == sizeof($filelist) - 1)
                { echo ' id="lastpic"'; } ?>> <?php echo ($it + 1); ?> </a> <?php
                if($it + 1 < sizeof($filelist) - $videos)
                { echo " | "; }
            }
            if(isset($filelist2) && sizeof($filelist2) > 0)
            {
                echo "
			<hr />";
                for($it = 0; $it < sizeof($filelist2); $it++)
                {?> <a class="piclink" href="./?folder=
				<?php echo $curfolder;
                    ?>&amp;selected=
				<?php echo $filelist2[$it]; ?>" <?php if($it == 0)
                    { echo ' id="firstpic"'; } else if($it == sizeof($filelist2) - 1)
                    { echo ' id="lastpic"'; } ?>> <?php echo ($it + 1); ?> </a> <?php 
                    if($it + 1 < sizeof($filelist2)) { echo " | "; }
                }
            }
            echo "
		</div>";
        }
        else
        {
            $current = $_GET['selected'];
            $key = array_search($current, $filelist);
            ?> <div class="navitem">
      <a <?php
            if($key > 0)
            {
                echo ' href="./?folder=' . $curfolder . '&amp;selected=' . 
                $filelist[$key - 1] . '"'; 
            } ?> id="prev">prev </a>
    </div>
    <div class="navitem">
      <a href="./?folder=
				<?php 
            echo $curfolder; ?>" id="index">index </a>
    </div>
    <div class="navitem">
      <a <?php if($key < sizeof($filelist) - 1)
            {
                echo ' href="./?folder=' . $curfolder . '&amp;selected=' . $filelist[$key + 1] . '"';
            } ?> id="next">next </a>
    </div>
  </div> <?php $vidext = strtolower(substr($current, strlen($current)-4));
            
            if($vidext != ".mp4" && $vidext != "webm" && $vidext != ".avi"
                && $vidext != ".mov"  && $vidext != ".wmv" && $vidext != ".flv" ) { ?> <img id="media" name="media" src="
		<?php echo $curfolder . "/" . $_GET['selected']; ?>" /> <?php } else {
                switch($vidext)
                { 
                    case ".mp4":
                    case "webm":
                    case ".avi":
            ?> <video id="media" width="800" height="600" controls loop autoplay>
    <source src="
				<?php echo $curfolder . "/" . $current; ?>" type="video/
				<?php echo $vidext == "webm" ? $vidext : substr($vidext, 1) ?>">
  </video> <?php 
                break;
                default: 
                    die("error loading/displaying video");
                }
            }
        }
    }
?> <form action="self" style="display:none;">
    <input type="hidden" id="imgw" value="" />
    <input type="hidden" id="imgh" value="" />
  </form>
</body>
</html>
