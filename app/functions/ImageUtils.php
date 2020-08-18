<?php


function ConvertBase64ToBlob($img, $valorMax = false)
{
    $blobData = base64_decode($img);
    $type = @explode('/', getimagesizefromstring($blobData)['mime'])[1];
    if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
        throw new Exception('Formato de Arquivo Invalido');
    }
    if ($blobData === false) {
        throw new Exception('base64_decode failed');
    }
    file_put_contents('../../public/tmp/img.' . $type, $blobData);
    if($valorMax) { /* Checa para ver se é maior que 4mb*/
        if (filesize('../../public/tmp/img.' . $type) > 4000000) {
            unlink('../../public/tmp/img.' . $type);
            throw new Exception("Imagem Com mais de 4 Megabytes(mb). Diminua usando o tinypng.");
        }
    } else {
        if (filesize('../../public/tmp/img.' . $type) > 2000000) {
            unlink('../../public/tmp/img.' . $type);
            throw new Exception("Imagem Com mais de 2 Megabytes(mb)");
        }
    }
    
    //REDIMENCIONA E DIMINUI A QUALIDADE

    if(!$valorMax) {
        $info = getimagesize('../../public/tmp/img.' . $type);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg('../../public/tmp/img.' . $type);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng('../../public/tmp/img.' . $type);
        }
        $height = 600;
        $width = 600;
        $ratio_orig = $info[0]/$info[1];

        if ($width/$height > $ratio_orig) 
        $width = $height*$ratio_orig;
        else 
        $height = $width/$ratio_orig;
        

        $image_p = imagecreatetruecolor($width, $height);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
        imagejpeg($image_p, '../../public/tmp/imgLow.'.$type, 70);
        $img = file_get_contents('../../public/tmp/imgLow.' . $type);

        
        //DESTROI AS IMAGENS
        unlink('../../public/tmp/imgLow.' . $type);
    }
    else{
        $img = file_get_contents('../../public/tmp/img.'.$type);
    }
    unlink('../../public/tmp/img.' . $type);

    return $img;
}
function ConvertBlobToBase64($img, $comSlashes = false)
{

    $imgtemp = base64_encode($img);

    return $imgtemp;
}
