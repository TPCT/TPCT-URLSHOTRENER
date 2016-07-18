<?php
if (!is_dir('short')){mkdir('short');}
$shorten_url = '';
$Main_url = '';
function Encryption($text = null, $int = 0,$login=false)
{
    if(!$login) {
        if ($int <= 10) {
            $encrypted_Text = hash('SHA256', '$TPC$');
            $Shipper = round($int * (2) * (2 / 3) * (5 / 2) * (1 / 3) + 10);
            foreach (str_split($text) as $c) {
                $salt = ((ord($c) * 2)) + (1 / 2) - $Shipper;
                $pepper = round($salt + $int);
                $encrypted_Text .= chr($pepper) . chr(239) . rand(0, 100000) . chr(217);
            }
        } else {
            $encrypted_Text = hash('SHA256', '$TPC$');
            $Shipper = round($int * (1 / 2) * (2 / 3) * (5 / 2) * (1 / 3) * (1 / 5) - 5);
            foreach (str_split($text) as $c) {
                $salt = ((ord($c) * 2)) + (1 / 2);
                $pepper = round($salt + $int * (1 / $int - 1)) - $Shipper;
                $encrypted_Text .= chr($pepper) . chr(239) . rand(0, 100000) . chr(217);
            }
        }
        return ($encrypted_Text);
    }else{
        if ($int <= 10) {
            $encrypted_Text = '';
            $Shipper = round($int * (2) * (2 / 3) * (5 / 2) * (1 / 3) + 10);
            foreach (str_split($text) as $c) {
                $salt = ((ord($c) * 2)) + (1 / 2) - $Shipper;
                $pepper = round($salt + $int);
                $encrypted_Text .= chr($pepper);
            }
        } else {
            $encrypted_Text = '';
            $Shipper = round($int * (1 / 2) * (2 / 3) * (5 / 2) * (1 / 3) * (1 / 5) - 5);
            foreach (str_split($text) as $c) {
                $salt = ((ord($c) * 2)) + (1 / 2);
                $pepper = round($salt + $int * (1 / $int - 1)) - $Shipper;
                $encrypted_Text .= chr($pepper);
            }
        }
        return ($encrypted_Text);
    }
}
function Decryption($text = null, $int = 0,$login=false)
{
    $Decrypted_Text = '';
    if(!$login) {
        if ($int <= 10) {
            $Shipper = round($int * (2) * (2 / 3) * (5 / 2) * (1 / 3) + 10);
            foreach (explode(chr(239), $text) as $item) {
                foreach (explode(chr(217), $item) as $key => $text) {
                    if ($key % 2 != 0 or sizeof(explode(chr(217), $item)) == 1) {
                        foreach (str_split($item) as $c) {
                            $c = (ord(($c)));
                            $salt = ((($c) - $int)) - 0.5 + $Shipper;
                            $pepper = round(($salt / 2));
                            $Decrypted_Text .= (chr($pepper));
                            if (chr($pepper) == '>') {
                                $Decrypted_Text .= "\n";
                            }
                        }
                    }
                }
            }
            $Decrypted_Text = substr($Decrypted_Text, 0, -1);
        } else {
            $Shipper = round($int * (1 / 2) * (2 / 3) * (5 / 2) * (1 / 3) * (1 / 5) - 5);
            foreach (explode(chr(239), $text) as $item) {
                foreach (explode(chr(217), $item) as $key => $text) {
                    if ($key % 2 != 0 or sizeof(explode(chr(217), $item)) == 1) {
                        foreach (str_split($text) as $c) {
                            $c = (ord($c));
                            $salt = ((($c) - $int * (1 / $int - 1))) - 0.5 + $Shipper;
                            $pepper = round(($salt / 2));
                            $Checked = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', chr($pepper));
                            $Decrypted_Text .= $Checked;
                            if (chr($pepper) == '>') {
                                $Decrypted_Text .= "\n";
                            }
                        }
                    }
                }
            }
        }
        return $Decrypted_Text;
    }else{
        if ($int <= 10) {
            $Shipper = round($int * (2) * (2 / 3) * (5 / 2) * (1 / 3) + 10);
            foreach (str_split($text) as $c) {
                $c = (ord(($c)));
                $salt = ((($c) - $int)) - 0.5 + $Shipper;
                $pepper = round(($salt / 2));
                $Decrypted_Text .= (chr($pepper));
                if (chr($pepper) == '>') {
                    $Decrypted_Text .= "\n";
                }
            }
        }
        else {
            $Shipper = round($int * (1 / 2) * (2 / 3) * (5 / 2) * (1 / 3) * (1 / 5) - 5);
            foreach (str_split($text) as $c) {
                $c = (ord($c));
                $salt = ((($c) - $int * (1 / $int - 1))) - 0.5 + $Shipper;
                $pepper = round(($salt / 2));
                $Checked = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', chr($pepper));
                $Decrypted_Text .= $Checked;
                if (chr($pepper) == '>') {
                    $Decrypted_Text .= "\n";
                }
            }
        }
        return $Decrypted_Text;
    }
}
function getCr(){
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $parts = explode('/',$actual_link);
    $parts[count($parts) - 1] = "";
    $actual_link = implode('/',$parts);
    return $actual_link;
}
function return_splitter($search = Null, $data= Null, $splitter= '->'){
    if (isset($search) and isset($data)){
        $data = explode("\n", $data);
        foreach($data as $da){
            if (strpos($search, explode($splitter, $da)[0]) !== false){
                return explode($splitter, $da)[1];
            }
        }
    }
    return Null;
}
echo '<style>input{outline:None;}</style>';
if (isset($_GET['submit']) and isset($_GET['url'])){
    $r = @get_headers($_GET['url']);
    if(filter_var($_GET['url'], FILTER_VALIDATE_URL)) {
        if ($r){
            $Main_url = $_GET['url'];
            $temp = '<?php 
            session_start();
            $user_agent = preg_match_all(\'/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i\', $_SERVER[\'HTTP_USER_AGENT\']); if ($user_agent !== 0 and preg_match(\'/Google|msnbot|Rambler|Yahoo|AbachoBOT|Accoona|AcoiRobot|FB|ASPSeek|CrocCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|Altavista|IDBot|eStyle|Scrubby|facebookexternalhit/si\', $_SERVER[\'HTTP_USER_AGENT\']) == false){
             session_destroy();
             header("location: '.$Main_url.'");}else{echo "<link rel=\"icon\" href=\"https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png\"/>";
             echo "<html><head><title>Enter For Reading</title></head><body><div>Please Open This Link From Any Browser Except Facebook<script>setTimeout(function(){
             window.open(\"'.$Main_url.'\", \"_self\");}, 1000);</script><div></body></html>";}?>               
';
            $file = @file_get_contents('1511254adasff.ext5');
            if (@strpos($file, $Main_url) !== false){
                $shorten_url = getCr().(return_splitter($Main_url, $file, '->'));
                $data = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>TPCT URL SHORTEN TOOL</title>
    <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png"/>
    <style>
        form{
            
        }
        body{
            text-align: center;
            background-size: cover;
            background: url("http://fastpayads.s3.amazonaws.com/blog/wp-content/uploads/2016/02/Hackers.jpg");
        }
        #url{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        #submit{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        fieldset{
            position: absolute;
            top: 50%;
            margin-top: -50px;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
            text-align: center;
            background: black;
        }
        legend{
            text-align: center;
            margin-left: 32%;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>
            Shorten Url Form
        </legend>
         <form method="get" action="">
                <label>Url To Shorten: <input name="url" id="url" type="url"/></label> 
                <input type="submit" id="submit" name="submit" value="Shorten"/>
         </form>
         <label>'.$shorten_url.'<label>
    </fieldset>
</body>
</html>';
                echo $data;
            }
            else{
                a:
                $s = substr(str_shuffle(str_repeat((string)(rand(0, PHP_INT_MAX)), 27)),0, 5).substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 27)), 0, 7). substr(str_shuffle(str_repeat((string)(rand(0, PHP_INT_MAX)), 27)),0, 5);
                $s = substr(str_shuffle(str_repeat($s, 27)),0,rand(0, strlen($s)+1));
                $file = @file_get_contents('1511254adasff.ext5');
                if (@strpos($file, $Main_url) !== false){
                    goto a;
                }
                else{
                    fwrite(fopen('1511254adasff.ext5', 'a+'), ($Main_url.'->'.$s)."\n");
                    mkdir('short/'.$s,0777);
                    file_put_contents('short/'.$s.'/index.php', $temp);
                    $shorten_url = getCr().'short/'.$s;
                }
                $data = '<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TPCT URL SHORTEN TOOL</title>
    <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png"/>
    <style>
        form{
            
        }
        body{
            text-align: center;
            background-size: cover;
            background: url("http://fastpayads.s3.amazonaws.com/blog/wp-content/uploads/2016/02/Hackers.jpg");
        }
        #url{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        #submit{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        fieldset{
            position: absolute;
            top: 50%;
            margin-top: -50px;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
            text-align: center;
            background: black;
        }
        legend{
            text-align: center;
            margin-left: 32%;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>
            Shorten Url Form
        </legend>
         <form method="get" action="">
                <label>Url To Shorten: <input name="url" id="url" type="url"/></label> 
                <input type="submit" id="submit" name="submit" value="Shorten"/>
         </form>
         <label>'.$shorten_url.'<label>
    </fieldset>
</body>
</html>';
                echo $data;
            }
        }
        else{
            $data = '<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TPCT URL SHORTEN TOOL</title>
    <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png"/>
    <style>
        form{
            
        }
        body{
            text-align: center;
            background-size: cover;
            background: url("http://fastpayads.s3.amazonaws.com/blog/wp-content/uploads/2016/02/Hackers.jpg");
        }
        #url{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        #submit{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        fieldset{
            position: absolute;
            top: 50%;
            margin-top: -50px;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
            text-align: center;
            background: black;
        }
        legend{
            text-align: center;
            margin-left: 32%;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>
            Shorten Url Form
        </legend>
          <form method="get" action="">
                <label>Url To Shorten: <input name="url" id="url" type="url"/></label> 
                <input type="submit" id="submit" name="submit" value="Shorten"/>
         </form>
         <label>Invalid URL To Shorten<label>
    </fieldset>
</body>
</html>';
            echo $data;
        }
    }
    else{
        if ($r){
            $Main_url = $_GET['url'];
            $temp = '<?php 
            session_start();
            $user_agent = preg_match_all(\'/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i\', $_SERVER[\'HTTP_USER_AGENT\']); if ($user_agent !== 0 and preg_match(\'/Google|msnbot|Rambler|Yahoo|AbachoBOT|Accoona|AcoiRobot|FB|ASPSeek|CrocCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|Altavista|IDBot|eStyle|Scrubby|facebookexternalhit/si\', $_SERVER[\'HTTP_USER_AGENT\']) == false){
             session_destroy();
             header("location: '.$Main_url.'");}else{echo "<link rel=\"icon\" href=\"https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png\"/>";
             echo "<html><head><title>Enter For Reading</title></head><body><div>Please Open This Link From Any Browser Except Facebook<script>setTimeout(function(){
             window.open(\"'.$Main_url.'\", \"_self\");}, 1000);</script><div></body></html>";}?>               
';
            $file = @file_get_contents('1511254adasff.ext5');
            if (@strpos($file, $Main_url) !== false){
                $shorten_url = getCr().(return_splitter($Main_url, $file, '->'));
                $data = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>TPCT URL SHORTEN TOOL</title>
    <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png"/>
    <style>
        form{
            
        }
        body{
            text-align: center;
            background-size: cover;
            background: url("http://fastpayads.s3.amazonaws.com/blog/wp-content/uploads/2016/02/Hackers.jpg");
        }
        #url{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        #submit{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        fieldset{
            position: absolute;
            top: 50%;
            margin-top: -50px;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
            text-align: center;
            background: black;
        }
        legend{
            text-align: center;
            margin-left: 32%;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>
            Shorten Url Form
        </legend>
         <form method="get" action="">
                <label>Url To Shorten: <input name="url" id="url" type="url"/></label> 
                <input type="submit" id="submit" name="submit" value="Shorten"/>
         </form>
         <label>'.$shorten_url.'<label>
    </fieldset>
</body>
</html>';
                echo $data;
            }
            else{
                m:
                $s = substr(str_shuffle(str_repeat((string)(rand(0, PHP_INT_MAX)), 27)),0, 5).substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 27)), 0, 7). substr(str_shuffle(str_repeat((string)(rand(0, PHP_INT_MAX)), 27)),0, 5);
                $s = substr(str_shuffle(str_repeat($s, 27)),0,rand(0, strlen($s)+1));
                $file = @file_get_contents('1511254adasff.ext5');
                if (@strpos($file, $Main_url) !== false){
                    goto m;
                }
                else{
                    fwrite(fopen('1511254adasff.ext5', 'a+'), ($Main_url.'->'.$s)."\n");
                    mkdir('short/'.$s,0777);
                    file_put_contents('short/'.$s.'/index.php', $temp);
                    $shorten_url = getCr().'short/'.$s;
                }
                $data = '<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TPCT URL SHORTEN TOOL</title>
    <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png"/>
    <style>
        form{
            
        }
        body{
            text-align: center;
            background-size: cover;
            background: url("http://fastpayads.s3.amazonaws.com/blog/wp-content/uploads/2016/02/Hackers.jpg");
        }
        #url{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        #submit{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        fieldset{
            position: absolute;
            top: 50%;
            margin-top: -50px;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
            text-align: center;
            background: black;
        }
        legend{
            text-align: center;
            margin-left: 32%;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>
            Shorten Url Form
        </legend>
         <form method="get" action="">
                <label>Url To Shorten: <input name="url" id="url" type="url"/></label> 
                <input type="submit" id="submit" name="submit" value="Shorten"/>
         </form>
         <label>'.$shorten_url.'<label>
    </fieldset>
</body>
</html>';
                echo $data;
            }
        }
        else{
            $data = '<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TPCT URL SHORTEN TOOL</title>
    <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png"/>
    <style>
        form{
            
        }
        body{
            text-align: center;
            background-size: cover;
            background: url("http://fastpayads.s3.amazonaws.com/blog/wp-content/uploads/2016/02/Hackers.jpg");
        }
        #url{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        #submit{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        fieldset{
            position: absolute;
            top: 50%;
            margin-top: -50px;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
            text-align: center;
            background: black;
        }
        legend{
            text-align: center;
            margin-left: 32%;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>
            Shorten Url Form
        </legend>
          <form method="get" action="">
                <label>Url To Shorten: <input name="url" id="url" type="url"/></label> 
                <input type="submit" id="submit" name="submit" value="Shorten"/>
         </form>
         <label>Invalid URL To Shorten<label>
    </fieldset>
</body>
</html>';
            echo $data;
        }
    }
}
else{
    $data = '<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TPCT URL SHORTEN TOOL</title>
    <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/large-glossy-icons/512/Spy.png"/>
    <style>
        form{
            
        }
        body{
            text-align: center;
            background-size: cover;
            background: url("http://fastpayads.s3.amazonaws.com/blog/wp-content/uploads/2016/02/Hackers.jpg");
        }
        #url{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        #submit{
            background-color: black;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
        }
        fieldset{
            position: absolute;
            top: 50%;
            margin-top: -50px;
            color: lawngreen;
            border: 1px greenyellow solid;
            border-radius: 5px;
            text-align: center;
            background: black;
        }
        legend{
            text-align: center;
            margin-left: 32%;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>
            Shorten Url Form
        </legend>
         <form method="get" action="">
                <label>Url To Shorten: <input name="url" id="url" type="url"/></label>                
                <input type="submit" id="submit" name="submit" value="Shorten"/>
         </form>
    </fieldset>
</body>
</html>';
    echo $data;
}
echo "<script>
function center() {
    var f = document.getElementsByTagName('fieldset')[0],
     width = f.offsetWidth,
     dwidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
  if (f.style.left != Math.floor((dwidth-width)/2)){
    f.style.left = Math.floor((dwidth-width)/2);
    setTimeout(\"center()\", 200);}
  else{
    setTimeout(\"center()\", 200);
  }
}
center();
</script>";
