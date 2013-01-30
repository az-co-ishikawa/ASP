<?PHP
include_once './include/base/WebAPIConnection.php';

	/*******************************************************************************************************
	 * <PRE>
	 * 
	 * �Z������ܓx�E�o�x���擾����N���X
     *     Google Maps��Yahoo�n�}�T�[�r�X�𗘗p���Ĉܓx�E�o�x���擾���܂��B
     *     ���p����ꍇ�́uGoogleAPI Key�v�ƁuYahoo�A�v���P�[�V����ID�v�̂ǂ��炩���K�v�ɂȂ�܂��B
     *
     * �O���ݒ�p�����[�^(conf���Őݒ�
     *   $WEBAPI_GEOCODEER_SERVICE = "yahoo";//geocoder�Ɏg�p����T�[�r�X
     *   $YAHOO_APP_ID = ""; 
	 * 
	 * @author �g�� �K��Y
	 * @version 1.0.0
	 * 
	 * </PRE>
	 *******************************************************************************************************/

class WebAPIMaps extends WebAPIConnection{
    //yahoo|google
    var $service='yahoo';
    var $apiid=null;
    
    var $survey_land = 'jp';//wld
    
    //�R���X�g���N�^
    function WebAPIMaps(){
        global $WEBAPI_GEOCODEER_SERVICE;
        
        if(isset($WEBAPI_GEOCODEER_SERVICE))
            $this->service = $WEBAPI_GEOCODEER_SERVICE;
    }
    
    function getGeocode($address){
        global $YAHOO_APP_ID;
        global $GOOGLE_API_KEY;
        
        switch( $this->service ){
            case 'yahoo':
                $uri = "/LocalSearchService/V1/LocalSearch?appid=";
                $uri .= $YAHOO_APP_ID;
                $uri .= "&p=";
                $uri .= urlencode(mb_convert_encoding($address,'UTF-8','SJIS'));
                $this->request("api.map.yahoo.co.jp",$uri,'get');
                $xml = $this->getXMLObject();

                if((int)$xml->Count){
                    switch($this->survey_land){
                        case 'jp':
                            if(isset($xml->Item[0]->DatumTky97))
                                return $xml->Item[0]->DatumTky97;
                            break;
                        case 'wld':
                            if(isset($xml->Item[0]->DatumWgs84))
                                return $xml->Item[0]->DatumWgs84;
                            break;
                    }
                }else{
                    return false;
                }
                break;
        }
        return false;
    }
}

?>