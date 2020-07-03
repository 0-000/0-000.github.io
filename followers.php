<?php


function find_str_in_array($array,$find)

    {
        foreach ($array as $key => $v)
        {
            if($v==$find)
            {
                return $key;
            }
        }
    }
function getData($url){
    /**
        *〈Get url content information through get method〉
        * @param [$url] [Required url]
        * @return [Contents]
    **/
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba");
	//curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
	//curl_setopt($ch, CURLOPT_PROXYPORT, 10809);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

	 function getFilter($begin,$end){
     return '|'.$begin.'([^^]*?)'.$end.'|u';
  }
	/*
$config = [
	'zhihu'=>'fvhin',
	'bilibili'=>'471946749',
	'twitter'=>'tjcnxb',
	'github'=>'cnxb'
];*/
function getfollowers($platform,$account){
    /**
        *〈Get the number of followers according to the specified platform and account〉
        * @param [$platform] [Required platform]
        * @param [$account] [Required account]
        * @return [followers_num]
    **/
    if (empty($account)){
        http_response_code(400);
    }
    switch($platform){
        case "zhihu":
            //print_r(get_meta_tags("https://www.zhihu.com/people/".$account));
            //return getData("https://www.zhihu.com/people/".$account);
            preg_match(getFilter('<strong','</strong>'), getData("https://www.zhihu.com/people/".$account),$zhihu);
            return $zhihu[1];
        case "bilibili":
            return json_decode(getData("https://api.bilibili.com/x/relation/stat?vmid=".$account),true)['data']['follower'];
        case "twitter":
            preg_match(getFilter('<a href="/'.$account.'/followers">','</div>'), str_replace(",","",getData("https://twitter.com/".$account)), $twitter);
            preg_match("/(\d+\.?\d+)/",$twitter[1],$twitter);
            return $twitter[1];
        case "github":
            return json_decode(getData("https://api.github.com/users/".$account),true)['followers'];
        default:
            http_response_code(400);
    }
}
//route
$route_all=explode("/",str_replace($_SERVER["SCRIPT_NAME"],"", $_SERVER['PHP_SELF']));
echo getfollowers($route_all[1],$route_all[2]);
/*
$ii=array();
$ii[1]=strstr(
    str_replace(
        $_SERVER["SCRIPT_NAME"]."/",
        "", 
        $_SERVER['PHP_SELF']
    ),
    '/'
);
cut($ii[1],1);
function cut($str,$num){
$ii[$num]=strstr(
        $str,
        '/'
);
    cut($ii[$num],$num+1);

}
print_r($ii)
*/



// | {sth} ([0-9]*?) {/sth} |u