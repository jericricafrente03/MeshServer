<?php
namespace App\Http\Helpers;

class Helper{

    public static function maskName(string $value) : string
    {
        $maskedName = '';
        foreach (str_split($value) as $char) {
            $maskedName .= rand(0, 1) ? '*' : $char; // Replace with '*' randomly
        }
        return $maskedName;
    }

    public function ProperNamingCase($string) 
    {
        $word_splitters = array(' ', '-', "O'", "L'", "D'", 'St.', 'Mc');
        $lowercase_exceptions = array('the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'of', 'and', "l'", "d'");
        $uppercase_exceptions = array('III', 'IV', 'VI', 'VII', 'VIII', 'IX');

        $string = strtolower($string);
        foreach ($word_splitters as $delimiter)
        { 
            $words = explode($delimiter, $string); 
            $newwords = array(); 
            foreach ($words as $word)
            { 
                if (in_array(strtoupper($word), $uppercase_exceptions))
                    $word = strtoupper($word);
                else
                if (!in_array($word, $lowercase_exceptions))
                    $word = ucfirst($word); 

                $newwords[] = $word;
            }

            if (in_array(strtolower($delimiter), $lowercase_exceptions))
                $delimiter = strtolower($delimiter);

            $string = join($delimiter, $newwords); 
        } 
        return $string; 
    }

    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 

    public static function truncate($s, $l) {
        if(strlen($s) <= $l) return $s;
        return ($p = strrpos(substr($s, 0, $l), ' ')) ? substr($s, 0, $p) . '...' : substr($s, 0, $l) . '...';
    }

    public function function_template(){
		//Detect Domain
		$domain = explode('.', request()->getHost())[0];  
	 
	 //Check if Domains is not Cakedino,
	  if($domain == 'cakedino'){ 
		  //if its cakedino, we read for the /username
		  $username = explode('.', request()->segment(1))[0];
		  $site = Site::where('url', '=',$username)->first(); 
					
		  if($site != null){ 

			

		  }else{
			echo 'site not found';
			abort(404);
		  }

	  }else{
		  //Its a FULL domain site
		  if($domain != 'cakedino'){
			 $site = Site::where('url', '=',$domain)->first();  
			
			if($site != null){ 
				  
				  $site->setRelation('reviews', $site->reviews()->paginate(5));

				  
		   }else{
			  echo 'site not found';
			  abort(404);
			}


		  }else{
			echo 'cakedino has no category page!';
			abort(404);
		  }


	  }

  }


    function dd_session() {
        $sessionData = session()->all(); // Get all session data
        $table = '<table>';
        $table .= '<thead><tr><th>Key</th><th>Value</th></tr></thead>';
        $table .= '<tbody>';
        foreach ($sessionData as $key => $value) {
            $table .= '<tr><td>' . $key . '</td><td>' . print_r($value, true) . '</td></tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';

        dd($table); // Use dd to halt and display the table
    }



}

?>