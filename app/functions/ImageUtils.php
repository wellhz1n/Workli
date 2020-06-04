<?php


function ConvertBase64ToBlob($img) {
     $blobData= base64_decode($img); 
     $type = @explode('/',getimagesizefromstring($blobData)['mime'])[1];
    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
        throw new Exception('Formato de Arquivo Invalido');
    }
    if ($blobData === false) {
        throw new Exception('base64_decode failed');
    }
    file_put_contents('../../public/tmp/img.'.$type, $blobData);
    if(filesize('../../public/tmp/img.'.$type) > 2000000){
        unlink('../../public/tmp/img.'.$type);
        throw new Exception("Imagem Com mais de 2 Megabytes(mb)");
    }
    $img = file_get_contents('../../public/tmp/img.'.$type);
    // $fp = fopen('../../public/tmp/img.'.$type, 'rb');  // open a file handle of the temporary file
    // $img  = fread($fp, filesize("../../public/tmp/img".$type)); // read the temp file
    unlink('../../public/tmp/img.'.$type);
    return $img;
}
function ConvertBlobToBase64($img,$comSlashes = false){
  
    $imgtemp = base64_encode($img);

    return $imgtemp;
}
