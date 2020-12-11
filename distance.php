<?php 
    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        //if longitudes and latitudes are the same, the distance will be 0 km
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
            else {
            //calculate distance between longitude and latitude in miles
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;

            //checks if system has to convert miles to another unit (Example -> KM, N)
            $unit = strtoupper($unit);
                
            //convert miles to kilometers
            if ($unit == "KM") {
                return number_format(($miles * 1.609344), 1);
            }
        }
    }

    //function to get longitude and latitude of a postal code
    function get_Longitude_And_Latitude($userPostcode){
        //replace the " " in user input with "+" -> url requires a "+" between the 4 digits and 2 letters (example -> "4207 JG" -> "4207+JG")
        $postcode = str_replace(" ", "+", $userPostcode);
        //geonames calculates longitude and latitude for given postal codes
        $url='http://www.geonames.org/postalcode-search.html?q='.$postcode.'&country=NL';
        // using file() function to get content in an array
        $lines_array=file($url);
        //count lines
        $count = 0;
        //array for storing specific html lines
        $lineArr = array();
        //loop through every line in html file (from URL)
        foreach($lines_array as $line){
            //add html line to array
            if($count == 145){
                array_push($lineArr, $line);
            }
            $count++;
        }
        //htmlspecialchars() converts special chars to html entities -> it will show html tags like <a></a> and <tr></tr> in the string
        //explode() splits string by "small" and put every (piece of) string in an array
        $pieces = explode("small", htmlspecialchars($lineArr[0]));
        //grab the 4th string in the array with latitude and longitude (example -> ">51.833/4.994</" )
        $piece = $pieces[3];                                    
        //substr($piece, 4) will cut the ">" from the string                                                                            EXAMPLE -> input: ">51.833/4.994</" -> output: "51.833/4.994</"
        //substr(substr($piece, 4), 0, -5)) will cut the "</" from the string                                                           EXAMPLE -> input: "51.833/4.994</" -> output: "51.833/4.994"
        //explode("/", substr(substr($piece, 4), 0, -5)) will split the string by "/" and put every (piece of) string in an array       EXAMPLE -> input: "51.833/4.994" -> Array(""51.833", "4.994")
        $piece = explode("/", substr(substr($piece, 4), 0, -5));                                                                                    //Array[0] is latitude and Array[1] is longitude
        //return array with longitude latitude and longitude
        return $piece;
    }
    
    function getDistance($postalcode1, $postalcode2){
        $user1 = get_Longitude_And_Latitude($postalcode1);   //get latitude and longitude array from user 1
        $user2 = get_Longitude_And_Latitude($postalcode2);   //get latitude and longitude array from user 2

        // "$user1[0]      -> latitude user 1";
        // "$user1[1]      -> longitude user 1";
        // "$user2[0]      -> latitude user 2";
        // "$user2[1]      -> longitude user 2";
        // (float) converts string to float
        return distance((float)$user1[0], (float)$user1[1], (float)$user2[0], (float)$user2[1], "KM") . " KM";
    }
?>