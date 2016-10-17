<?php
namespace MyCode\UploadFile;
use finfo;
class UploadFile{
    //上传最大值
    private $maxSize;
    //上传类型
    private $type;
    //文件对象
    private $upfile;
    public function __construct($upfile,$max = 1000000, $type = [  'jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif' ])
    {
        $this->upfile = $upfile;
        $this->maxSize = $max;
        $this->type = $type;
    }

    public function upload(){
        header('Content-Type: text/plain; charset=utf-8');
        try{
            switch ($this->upfile['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }
            //上传文件大小是否超过最大值
            if ($this->upfile['size'] > $this->maxSize) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $finfo->file($this->upfile['tmp_name']),
                    $this->type,
                    true
                )) {
                throw new RuntimeException('Invalid file format.');
            }
            if (move_uploaded_file($this->upfile['tmp_name'], $this->upfile['tmp_name'])) {
                echo "File is valid, and was successfully uploaded.\n";
            } else {
                echo "Possible file upload attack!\n";
            }
        }catch (RuntimeException $e){
            echo $e->getMessage();
        }
    }
}
$a = new UploadFile($_FILES['upfile']);
$a->upload();

