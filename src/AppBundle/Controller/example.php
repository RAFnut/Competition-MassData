



$api_key='6584ad53b0c66534b5fa13ecfbbece93'; //To get your API visit datumbox.com, register for an account and go to your API Key panel: http://www.datumbox.com/apikeys/view/

$DatumboxAPI = new DatumboxAPI($api_key);


        foreach ($query->getTweet() as $t) {
           echo $DatumboxAPI->SentimentAnalysis($t->getText()) . "     " . $t->getText() . "<br>";
  
        }

unset($DatumboxAPI);

var_dump($sdffsd);