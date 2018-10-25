<?php 
 $question = $_GET['question'];
	$question_request = json_encode(array('question'=> $question));
	$kbID = "b7f56c5f-a608-468b-a167-a451f3de296c";
	$subscriptionKey = "ae7919dd8cdf4111bff76e53642973af"; 

    $ch = curl_init('https://westus.api.cognitive.microsoft.com/qnamaker/v2.0/knowledgebases/'.$kbID.'/generateAnswer');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
    curl_setopt($ch, CURLOPT_POSTFIELDS, $question_request);   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                               
    'Content-Type: application/json ;charset=utf-8', 
    'cache-control: no-cache',
    'ocp-apim-subscription-key:'.$subscriptionKey

      )                                                                       
    ); 
    
    $result = curl_exec($ch);
    curl_close($ch);
    $response_array = json_decode($result,true);
    echo $response_array['answers'][0]['answer']; 

?>