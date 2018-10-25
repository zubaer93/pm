<?php

function lang($status = null, $file = null, $action = null){
    if(!empty($_GET['lang'])){
        if(!empty($file) && $_GET['lang'] == 'JMD'){
            try{
                if($status == 'error'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/JMD/error.php');
                    return sprintf($response[$file], $file);

                } else if ($status == 'notfound'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/JMD/notfound.php');
                    return sprintf($response['message']. '%s.', $file);

                } else if ($status == 'fail'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/JMD/fail.php');
                    return sprintf('%s '.$response['message']. '%s. ' . 'please try again.', $file, $action);

                } else{
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/JMD/success.php');
                    return sprintf('%s '.$response['message']. ' %s' . ' successfully', $file, $action);
                }
            } catch ( \Exception $e){
                $e->getMessage();
            }
        } else {
            try{
                if($status == 'error'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/USD/error.php');
                    return sprintf($response[$file], $file);

                } else if ($status == 'notfound'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/USD/notfound.php');
                    return sprintf($response['message']. '%s.', $file);

                } else if ($status == 'fail'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/USD/fail.php');
                    return sprintf('%s '.$response['message']. '%s. ' . 'please try again.', $file, $action);

                } else{
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/USD/success.php');
                    return sprintf('%s '.$response['message']. ' %s' . ' successfully', $file, $action);
                }
            } catch ( \Exception $e){
                $e->getMessage();
            }
        }
    } else {
        if(!empty($file && $status )){
            try{
                if($status == 'error'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/en/error.php');
                    return sprintf($response[$file], $file);

                } else if ($status == 'notfound'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/en/notfound.php');
                    return sprintf($response['message']. '%s.', $file);

                } else if ($status == 'fail'){
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/en/fail.php');
                    return sprintf('%s '.$response['message']. '%s. ' . 'please try again.', $file, $action);

                } else{
                    $response =  include_once (ROOT.'/plugins/Language/resources/lang/en/success.php');
                    return sprintf('%s '.$response['message']. ' %s' . ' successfully', $file, $action);
                }
            } catch ( \Exception $e){
                $e->getMessage();
            }
        } else {
            return include_once (ROOT.'/plugins/Language/resources/lang/en/request.php');
        }
    }
}




