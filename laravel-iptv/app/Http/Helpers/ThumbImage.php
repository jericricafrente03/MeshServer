<?php
namespace App\Http\Helpers;


class ThumbImage
{
    private $source;
    
    public function createThumb($destImagePath, $thumbWidth=100)
    {
        $sourceImage = imagecreatefromjpeg($this->source);
        $orgWidth = imagesx($sourceImage);
        $orgHeight = imagesy($sourceImage);
        $thumbHeight = floor($orgHeight * ($thumbWidth / $orgWidth));
        $destImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $orgWidth, $orgHeight);
        imagejpeg($destImage, $destImagePath);
        imagedestroy($sourceImage);
        imagedestroy($destImage);
    }

    function makeThumb( $filename , $thumbSize=100,$thumb_path_to_file){
      
       /* Set Filenames */
        $srcFile = $filename;
        $thumbFile = $thumb_path_to_file;
       /* Determine the File Type */
        $type = substr( $filename , strrpos( $filename , '.' )+1 );
       /* Create the Source Image */
        switch( strtolower($type) ){
          case 'jpg' : case 'jpeg' :
            $src = imagecreatefromjpeg( $srcFile ); break;
          case 'png' :
            $src = imagecreatefrompng( $srcFile ); break;
            
          case 'gif' :
            $src = imagecreatefromgif( $srcFile ); break;
          case 'webp' :
              $src = imagecreatefromwebp( $srcFile ); break;
        }
       /* Determine the Image Dimensions */
        $oldW = imagesx( $src );
        $oldH = imagesy( $src );
        /* Calculate the New Image Dimensions */
   $limiting_dim = 0;
   if( $oldH > $oldW ){
    /* Portrait */
     $limiting_dim = $oldW;
   }else{
    /* Landscape */
     $limiting_dim = $oldH;
   }
  /* Create the New Image */
   $new = imagecreatetruecolor( ($thumbSize*2), ($thumbSize*2) );
  /* Transcribe the Source Image into the New (Square) Image */
   imagecopyresampled( $new , $src , 0 , 0 , ($oldW-$limiting_dim )/2 , ( $oldH-$limiting_dim )/2 , ($thumbSize*2) , ($thumbSize*2) , $limiting_dim , $limiting_dim );
        switch( strtolower($type) ){
          case 'jpg' : case 'jpeg' :
            $src = imagejpeg( $new , $thumbFile ); break;
          case 'png' :
            //$black = imagecolorallocate($new, 0, 0, 0);
            // Make the background transparent
           // imagecolortransparent($new, $black);
            $src = imagepng( $new , $thumbFile ); break;
            
          case 'gif' :
            $src = imagegif( $new , $thumbFile ); break;
          case 'webp' :
            $src = imagewebp( $new , $thumbFile ); break;
        }
           // dd($src);   
       //imagedestroy( $new );
       //imagedestroy( $src );
  }

    
}