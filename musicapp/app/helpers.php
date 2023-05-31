<?php
function pr($data){
    echo "<pre>";
    print_r($data);
    exit();
}
function cacheclear()
{
    return time();
}
function getDateFormateView($date){
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
}
function getLogoUrl(){
	return asset('frontend/img/logo.png');
}
function addPageJsLink($link){
    return asset('backend/page')."/".$link.'?'.time();
}
function generateReferralCode()
{    
    $length_of_string = 8;
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'; 
    $generate_code = substr(str_shuffle($str_result), 0, $length_of_string);                    
    $code_exists = App\User::where('referral_code', '=', $generate_code)->first();
    if(!isset($code_exists->id)){
        return $generate_code;exit();
    }
    else{
        generateReferralCode();
    }       
}
?>