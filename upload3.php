<html>
    <head>
        <meta charset="utf-8">
    <body>
<?php
require_once('Connections/bmks.php'); 
require_once('Connections/bmksl.php'); 
require_once './simple_html_dom.php';
function showimage($file_name_image) {
    $pic = 'docx/word/' . $file_name_image;

    $picture = base64_encode(file_get_contents($pic));
   // echo '<img src="' . $pic . '">';
   // exit();
//    
////    echo $zip_file_original = $zip_file_original;
////    $file_name_image = 'docx/word/' . $file_name_image . ''; // getting the image in the zip using its name
////    //echo "<br/>";
////    $z_show = new ZipArchive();
////    if ($z_show->open($zip_file_original) !== true) {
////        echo "File not found.";
////        return false;
////    }
////
////    $stat = $z_show->statName($file_name_image);
////    $fp = $z_show->getStream($file_name_image);
////    if (!$fp) {
////        echo "Could not load image.";
////        return false;
////    }
//
//    header('Content-Type: image/jpeg');
//    header('Content-Length: ' . $stat['size']);
//    $image = stream_get_contents($fp);
//    $picture = base64_encode($image);
    return $picture; //return the base62 string for the current image.
//    fclose($fp);
}
  function delete_directory($dirname) {
                        if (is_dir($dirname))
                            $dir_handle = opendir($dirname);
                        if (!$dir_handle)
                            return false;
                        while ($file = readdir($dir_handle)) {
                            if ($file != "." && $file != "..") {
                                if (!is_dir($dirname . "/" . $file))
                                    unlink($dirname . "/" . $file);
                                else
                                    delete_directory($dirname . '/' . $file);
                            }
                        }
                        closedir($dir_handle);
                        rmdir($dirname);
                        return true;
                    }
  $xmlFile2 = "Docx/word/_rels/document.xml.rels";
                    $reader2 = new XMLReader;
                    $reader2->open($xmlFile2);
                    $repo = array();
                    while ($reader2->read()) {
                        $node_loc = $reader2->localName;
                        if ($reader2->nodeType == XMLREADER::ELEMENT && $reader2->localName == 'Relationship') {
                            $read_content_id = $reader2->getAttribute("Id");
                            $read_content_name = $reader2->getAttribute("Target");
                            $repo[$read_content_id] = $read_content_name;
                        }
                    }
// set location of docx text content file
$reader2->close();
$xmlFile = "docx/word/document.xml";
$reader = new XMLReader;
$reader->open($xmlFile);

// set up variables for formatting
$text = '';
$formatting['bold'] = 'closed';
$formatting['italic'] = 'closed';
$formatting['underline'] = 'closed';
$formatting['header'] = 0;

$for_image = $xmlFile2;
$status = false;
// loop through docx xml dom
while ($reader->read()) {

    // look for new paragraphs
    if ($reader->nodeType == XMLREADER::ELEMENT && $reader->name === 'w:p') {
        // set up new instance of XMLReader for parsing paragraph independantly
        $paragraph = new XMLReader;
        $p = $reader->readOuterXML();
        $paragraph->xml($p);

        // search for heading
        preg_match('/<w:pStyle w:val="(Heading.*?[1-6])"/', $p, $matches);
        switch ($matches[1]) {
            case 'Heading1': $formatting['header'] = 1;
                break;
            case 'Heading2': $formatting['header'] = 2;
                break;
            case 'Heading3': $formatting['header'] = 3;
                break;
            case 'Heading4': $formatting['header'] = 4;
                break;
            case 'Heading5': $formatting['header'] = 5;
                break;
            case 'Heading6': $formatting['header'] = 6;
                break;
            default: $formatting['header'] = 0;
                break;
        }

        // open h-tag or paragraph
        $text .= ($formatting['header'] > 0) ? '<h' . $formatting['header'] . '>' : '<p>';
        $status = false;

        // loop through paragraph dom
        while ($paragraph->read()) {

            $node_loc = $paragraph->localName;
            // look for elements
            if ($paragraph->nodeType == XMLREADER::ELEMENT && $paragraph->name === 'w:r') {
                $node = trim($paragraph->readInnerXML());
                //echo $node;
                // add <br> tags
                if (strstr($node, '<w:br '))
                    $text .= '<br>';

                // look for formatting tags                    
                $formatting['bold'] = (strstr($node, '<w:b/>')) ? (($formatting['bold'] == 'closed') ? 'open' : $formatting['bold']) : (($formatting['bold'] == 'opened') ? 'close' : $formatting['bold']);
                $formatting['italic'] = (strstr($node, '<w:i/>')) ? (($formatting['italic'] == 'closed') ? 'open' : $formatting['italic']) : (($formatting['italic'] == 'opened') ? 'close' : $formatting['italic']);
                $formatting['underline'] = (strstr($node, '<w:u ')) ? (($formatting['underline'] == 'closed') ? 'open' : $formatting['underline']) : (($formatting['underline'] == 'opened') ? 'close' : $formatting['underline']);
                $str = $paragraph->expand()->textContent;
                // build text string of doc
                $text .= (($formatting['bold'] == 'open') ? '<strong>' : '') .
                        (($formatting['italic'] == 'open') ? '<em>' : '') .
                        (($formatting['underline'] == 'open') ? '<u>' : '') .
                        //htmlentities(iconv('UTF-8', 'ASCII//TRANSLIT',$paragraph->expand()->textContent)).
                        htmlentities($str, ENT_QUOTES, "utf-8") .
                        (($formatting['underline'] == 'close') ? '</u>' : '') .
                        (($formatting['italic'] == 'close') ? '</em>' : '') .
                        (($formatting['bold'] == 'close') ? '</strong>' : '');

                // reset formatting variables
                foreach ($formatting as $key => $format) {
                    if ($format == 'open')
                        $formatting[$key] = 'opened';
                    if ($format == 'close')
                        $formatting[$key] = 'closed';
                }
            }
            if ($node_loc == 'blip' && !$status) {
                $attri_r = $paragraph->getAttribute("r:embed");
                $current_image_name = $repo[$attri_r];
                $image_stream = "" . showimage($current_image_name) . ""; //return the base64 string 
                //echo $image_stream;


                $text.="" . '<img height="150" width="200" src="data:image/jpg;base64,' . $image_stream . '">';
                $text.=$image_stream;
                $status = true;
            }
        }
        $text .= ($formatting['header'] > 0) ? '</h' . $formatting['header'] . '>' : '</p>';
    }
}

$text = str_replace("<p></p>", "", $text);


//echo $text;


$html = new simple_html_dom();

$html->load($text);



$arr_all = array();


foreach ($html->find('p') as $element) {
    //echo $i." ".$element->plaintext . '<br>';
    $arr_all [] = $element->plaintext;
}

//print_r($arr_all);

$num_que = count($arr_all) / 5;

//echo $num_que;

$que = 0;
$ans_a = 1;
$ans_b = 2;
$ans_c = 3;
$ans_d = 4;
//$ans = 5;
//$obj = 6;


//mysql_connect($dbhost, $dbuser, $dbpass) or die('cannot connect to the server');
//mysql_select_db($dbname) or die('database selection problem');

$check_a = false;
$check_b = false;

for ($i = 1; $i <= $num_que; $i++) {
    if(empty(substr($arr_all[$que], 3)){
        $check_a = TRUE;
        break;
    }ELSE{
        if (empty(substr($arr_all[$ans_a])) || empty(substr($arr_all[$ans_b])) || empty(substr($arr_all[$ans_c])) || empty(substr($arr_all[$ans_d]))) {
           $check_b = TRUE;
        }
    }
    # code...
}
IF($check_a ==FASLE && $check_b  == FASLE){
for ($i = 1; $i <= $num_que; $i++) {
    ?>

    <!--<div class="col-md-6 col-md-offset-3 col-md-offset-3">

    <?php echo "ข้อที่" . $i . ". " . substr($arr_all[$que], 3) . "<BR>"; ?>

    </div>-->

    <?php
    //echo $arr_all[$que]."<BR>";
    //echo $arr_all[$ans_a]."<br>";
    //echo $arr_all[$ans_b]."<br>";
    //echo $arr_all[$ans_c]."<br>";
    //echo $arr_all[$ans_d]."<br>";
    //echo $arr_all[$ans]."<br>";
    //echo "<br><br>";
   
    $subject = $_GET['subject'];
   
    mysql_query("Set names 'utf8'");
    $sql = "INSERT INTO test (IDtest,id_course,num,text1,c1,c2,c3,c4,ans,obj) 
      VALUES ('','$subject','" . $i . "','" . substr($arr_all[$que], 3) . "','" . substr($arr_all[$ans_a], 3) . "','" . substr($arr_all[$ans_b], 3) . "','" . substr($arr_all[$ans_c], 3) . "','" . substr($arr_all[$ans_d], 3) . "'
        ,'" . substr($arr_all[$ans], 5) . "','" . substr($arr_all[$obj], 5) . "')";
    
    mysql_query($sql);
    echo $sql."<BR>";

    $que += 5;
    $ans_a += 5;
    $ans_b += 5;
    $ans_c += 5;
    $ans_d += 5;
    //$ans += 7;
    //$obj += 7;
}else{
    echo "File ERROR";
}
}


mysql_close();

$reader->close();

//unlink($folder.$file_name);
//delete_directory("Docx");
//mkdir("Docx");
//mkdir("Docx/word");
//mkdir("Docx/word/media");
//echo 'UPLOAD AND CONVERT SUCCESS';
// if $res === TRUE
// if btn-upload
?>

<script type="text/javascript">
  // window.location.replace('new_test.php')
</script>
</body>
</html>