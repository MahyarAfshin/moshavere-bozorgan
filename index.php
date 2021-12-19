<?php
    $question = 'این یک پرسش نمونه است';
    $msg = 'این یک پاسخ نمونه است';
    $en_name = 'hafez';
    $fa_name = 'حافظ';
    
    $question='';

    $afrad=file_get_contents("people.json");
    $dictionary=json_decode($afrad,true);

    $file=fopen('messages.txt', 'r');
    $messageArray=array();
    while(!feof($file)){
        $message=fgets($file);
        array_push($messageArray,$message);

    }
    fclose($file);

    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $title='پرسش:';
        $question = $_POST["question"];
        $en_name=$_POST['person'];
        $fa_name=$dictionary[$en_name];
        $msg=$messageArray[hexdec(hash('adler32', $fa_name.$question))%count($messageArray)];
        $firstpart="/^آیا/iu";
        $endpart="/\?$/i";
        $endpart1="/؟$/u";
        if(!preg_match($firstpart,$question)){
            $msg="سوال درستی پرسیده نشده";
        }
        if(!(preg_match($endpart,$question) || preg_match($endpart1,$question)) ){
            $msg="سوال درستی پرسیده نشده";
        }
    }
    else{   
        $title='';
        $msg='سوال خود را بپرس!';
        $keysArray=array_keys($dictionary);
        $en_name=$keysArray[array_rand($keysArray)];
        $fa_name=$dictionary[$en_name];
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label"><?php echo $title ?></span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
                /*
                 * Loop over people data and
                 * enter data inside `option` tag.
                 * E.g., <option value="hafez">حافظ</option>
                 */
                foreach($dictionary as $key => $value){
                    if($key==$en_name){
                        echo "<option value='$key' selected>$value</option>";
                    }
                    else{
                        echo "<option value='$key'>$value</option>";

                    }
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>