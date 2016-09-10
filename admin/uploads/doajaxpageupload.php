<?php

function imageresize($outfile,$infile,$percents,$quality) {
    $im=imagecreatefromjpeg($infile);

    $h=48;
    $percents = $h / imagesy($im) * 100;
    $w=imagesx($im)*$percents/100;
    //imagesy($im)*$percents/100;
    $im1=imagecreatetruecolor($w,$h);
    imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));

    imagejpeg($im1,$outfile,$quality);
    imagedestroy($im);
    imagedestroy($im1);
}


function resize($image, $w_o = false, $h_o = false) {
    if (($w_o < 0) || ($h_o < 0)) {
        echo "Некорректные входные параметры";
        return false;
    }
    list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
    $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
    $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
    if ($ext) {
        $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
        $img_i = $func($_SERVER['DOCUMENT_ROOT']."/content/".$image); // Создаём дескриптор для работы с исходным изображением
    } else {
        echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
        return false;
    }
    /* Если указать только 1 параметр, то второй подстроится пропорционально */
    if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
    if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
    $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
    imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
    $func = 'image'.$ext; // Получаем функция для сохранения результата
    return $func($img_o, $_SERVER['DOCUMENT_ROOT']."/thumb/".$image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
}

$error = "";
$msg = "";
$fileElementName = 'fileToUpload';

if(!empty($_FILES[$fileElementName]['error']))
{
    switch($_FILES[$fileElementName]['error'])
    {

        case '1':
            $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            break;
        case '2':
            $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            break;
        case '3':
            $error = 'The uploaded file was only partially uploaded';
            break;
        case '4':
            $error = 'No file was uploaded.';
            break;

        case '6':
            $error = 'Missing a temporary folder';
            break;
        case '7':
            $error = 'Failed to write file to disk';
            break;
        case '8':
            $error = 'File upload stopped by extension';
            break;
        case '999':
        default:
            $error = 'No error code avaiable';
    }
}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
{
    $error = 'No file was uploaded..';
}else
{
    //$msg .= " <span> File Name: " . $_FILES['fileToUpload']['name'] . "</span><br/> ";
    $msg .= " <div class='imgcontent'><p><b>Размер файла:</b> " . @filesize($_FILES['fileToUpload']['tmp_name']);
    //формируем имя уникальное файла
    $fname =  $_FILES['fileToUpload']['name'];
    $ext = substr($_FILES['fileToUpload']['name'], 1 + strrpos($_FILES['fileToUpload']['name'], "."));
    //$ext = '.jpg';
    $apend=date('YmdHis').rand(100,1000).".$ext";
    //for security reason, we force to remove all uploaded file
    //@unlink($_FILES['fileToUpload']);
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/content/'.$apend);
    /*
    if($ext=='jpg' || $ext=='jpeg') {
        //imageresize($_SERVER['DOCUMENT_ROOT']."/content/thumbs/".$apend,$_SERVER['DOCUMENT_ROOT']."/content/".$apend,30,75);
        //resize($apend, 150);
    }
    */
    $msg .= " <b>Имя файла:</b> /content/$apend</p>";
    $msg .= '</div>';
}
echo "{";
echo			 "error: '" . $error . "',\n";
echo			 "msg: '" . $msg . "',\n";
echo      "name: '".$apend."'\n";
echo "}";
?>