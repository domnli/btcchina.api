<?php
include 'btcchina.php';
if(isset($_GET['type'])){
	$func = $_GET['type'];	
}else{
	exit;
}

if(function_exists($func)){
	echo call_user_func($func);
}else{
	exit;
}

function buy(){
	if(!isset($_POST['price']) || !isset($_POST['amount']) ){
		return '0';
	}
	$param = array(floatval($_POST['price']), floatval($_POST['amount']));
	$retJSON = request('buyOrder', $param);
	$retArr = json_decode($retJSON, true);
	if(isset($retArr['error']) || !isset($retArr['result'])){
		return '0';
	}
	if($retArr['result']){
		return '1';
	}
	return $retJSON;;
}

function sell(){
	if(!isset($_POST['price']) || !isset($_POST['amount']) ){
		return '0';
	}
	$param = array(floatval($_POST['price']), floatval($_POST['amount']));
	$retJSON = request('sellOrder', $param);

	$retArr = json_decode($retJSON, true);
	if(isset($retArr['error']) || !isset($retArr['result'])){
		return '0';
	}
	if($retArr['result']){
		return '1';
	}
	return $retJSON;
}
function getorders(){
	$param = array(
				isset($_POST['openonly'])?$_POST['openonly']:false
			);
	if(isset($_POST['openonly'])){
		if($_POST['openonly']){
			$param = array();
		}else{
			$param = array(false);
		}
	}else{
		$param = array(false);
	}
	$retJSON = request('getOrders', $param);
	//return $retJSON;
	$retArr = json_decode($retJSON, true);
	if(isset($retArr['error']) || !isset($retArr['result']['order'])){
		return '0';
	}
	return json_encode($retArr['result']['order']);
}
function cancelorder(){
	if(!isset($_POST['id']) ){
		return '0';
	}
	$param = array(intval($_POST['id']));
	$retJSON = request('cancelOrder',$param);
	return $retJSON;
	$retArr = json_decode($retJSON, true);
	if(isset($retArr['error']) || !isset($retArr['result'])){
		return '0';
	}
	if($retArr['result']){
		return '1';
	}
	return '0';
}

function getticker(){
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 
            'Mozilla/4.0 (compatible; BTC China Trade Bot; '.php_uname('a').'; PHP/'.phpversion().')'
            );
    curl_setopt($ch, CURLOPT_URL, 'https://data.btcchina.com/data/ticker');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $res = curl_exec($ch);
    return $res;
}