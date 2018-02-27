<?php

#open file with MAC to IRR definiton
$file = fopen("mac-irr", "r");
echo "defining AS-SETs to MAC matching\n";
$irrs = array();
$irrset = array();
while (($line = fgetcsv($file)) !== FALSE){
	$irrs[$line[0]]=$line[1];
	$irrset[] = $line[1];
	$macs[] = $line[0];
        $irrfiles = "file_$line[0]";
        $$irrfiles =  file_get_contents($line[1]);

}

#load each of the IRR files
echo "loading the IRR data into memory. \n loading data for : ( ";

foreach ($irrset as $value) {
	echo " $value ";
}
echo " ).\n";

#open IRR data

#function to check if IP is in subnet. returns true - 1 if true nothing or false if not.

function cidr_match($ip, $cidr)
{
    list($subnet, $mask) = explode('/', $cidr);

    if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet))
    { 
        return true;
    }

    return false;
}

#Open the file thats piped in and start processing
$seperator = "\n";

echo "collecting flow data.....\n";

while($f = fgets(STDIN)){
	if(preg_match("/^FLOW,/", $f)){
		if(preg_match("/:/", $f)){
			#don't support IPv6
		}else{
		 	$array = array();
		 	$array = str_getcsv($f);
				if($array[2] == "36"){
					foreach ($macs as $mac){
						if($array[4] == $mac){
							$b = "file_$mac";
							$line = strtok($$b, $seperator);
							$match = '';
							while ($line !== FALSE){
								if(cidr_match($array[9], $line)){
									$match = "true";
								}
							$line = strtok($seperator);
							}
						        if($match == "true"){
								#echo ".";
								#yay no spoofed packets
                                                                echo "matches IRR:\tSource:\t$array[9],\t\tMAC:\t$mac,\tIRR SET:\t$irrs[$mac]\n";
                                                        }else{  
                                                                echo "no match irr:\tSource:\t$array[9],\t\tDestination:\t$array[10]\tMAC:\t$mac,\tIRR SET:\t$irrs[$mac]\n";
                                                        }   
						}	
	 				}				
				}
			}
		}
}
