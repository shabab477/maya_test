<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests;

class MainController extends Controller
{
    /**
     * $appId
     * @var [int]
     */
    protected $appId;
    /**
     * [$appSecret description]
     * @var [string]
     */
   protected $appSecret;
   /**
    * [$tokenExchangeUrl description]
    * @var [type]
    */
   protected $tokenExchangeUrl;
   /**
    * [$endPointUrl description]
    * @var [type]
    */
   protected $endPointUrl;
   /**
    * [$userAccessToken description]
    * @var [type]
    */
   public $userAccessToken;
   /**
    * [$refreshInterval description]
    * @var [type]
    */
   protected $refreshInterval;
   /**
    * [__construct description]
    */
   public function __construct()
   {
      $this->appId            = env('FB_APP_ID');
      //WARNING!!! SSL CERTIFICATION SET TO FALSE. 
      //DO THE HARD WORK FOR PRODUCTION RELEASE!.
      $this->client           = new GuzzleHttpClient(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false ),'verify' => false));
      $this->appSecret        = env('FB_APP_SECRET');
      $this->endPointUrl      = env('END_POINT');
      $this->tokenExchangeUrl = env('TOKEN_EXCHANGE');
   }
    /**
     * [login description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function login(Request $request)
    {
        $url = $this->tokenExchangeUrl.'grant_type=authorization_code'.
                '&code='. $request->get('code').
                "&access_token=AA|$this->appId|$this->appSecret";

        //var_dump($url);
        $apiRequest = $this->client->request('GET', $url);
        $body = json_decode($apiRequest->getBody());
        $this->userAccessToken = $body->access_token;
        $this->refreshInterval = $body->token_refresh_interval_sec;
        return $this->getData();
    }
    public function getData()
    {
        $request = $this->client->request('GET', "https://graph.accountkit.com/v1.1/me?access_token=" . $this->userAccessToken);
        $data = json_decode($request->getBody());
        $userId = $data->id;
        $userAccessToken = $this->userAccessToken;
        $refreshInterval = $this->refreshInterval;
        $phone = isset($data->phone) ? $data->phone->number : '';
        $email = isset($data->email) ? $data->email->address : '';
        return view('pages.user', compact('phone', 'email', 'userId', 'userAccessToken', 'refreshInterval'));
    }

}
