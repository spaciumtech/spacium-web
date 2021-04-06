<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Common
{
    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function fetchData($url,$data,$type = 'GET'): array {

       $response = $type = 'GET' ? $this->client->request(
            $type,
            $url,
           [
               'query' => $data
           ]
        ):$this->client->request(
           $type,
           $url,
           [
               'headers' => [
                   'Content-Type' => 'text/plain',
               ],

               'body' => $data
           ]
       );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        $total_posts = isset($response->getHeaders()['x-wp-total']) ? $response->getHeaders()['x-wp-total']:0;
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return [
            'content' => $content,
            'total' => $total_posts,
        ];
    }

    public function isEmail($email) {
        return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
    }

    public function sendEmail($name,$phone,$email,$comments,$token){

        if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

        if(trim($name) == '') {
            return ['response' => false,'message' => 'Please enter your name!'];
        } else if(trim($email) == '') {
            return ['response' => false,'message' => 'Please enter your email!'];
        } else if(!$this->isEmail($email)) {
            return ['response' => false,'message' => 'Please enter your valid email!'];
        exit();
        } else if(!$phone) {
            return  ['response' => false,'message' => 'Please enter your valid phone number!'];
        }

        if(trim($comments) == '') {
             return ['response' => false,'message' => 'Please enter your message!'];
        }

        // call curl to POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => '6LdvxIwaAAAAAIA5Q92UFRImUcqOsTWlgVdbaKrF', 'response' => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);

        // verify the response
        if($arrResponse["success"] == '1') {
            // valid submission
            // go ahead and do necessary stuff
        } else {
           return ['response' => false,'message' => 'You are a bot!!'];
        }

        if(get_magic_quotes_gpc()) {
            $comments = stripslashes($comments);
        }

        $address = "contact@spacium.co";

        $e_subject = 'You have been contacted by ' . $name . '.';

        $message = '<html><body>';
        $message .= '<h1 style="color:#f40;">Name: '.$name.'</h1>';
        $message .= '<p style="color:#080;font-size:18px;">Email: '.$email.'</p>';
        $message .= '<p style="color:#080;font-size:18px;">Phone: '.$phone.'</p>';
        $message .= '<p style="color:#080;font-size:18px;">'.$comments.'</p>';
        $message .= '</body></html>';

        $headers = "From: $email" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

        if(mail($address, $e_subject, $message, $headers)) {
            return ['response' => true,'message' => 'Thank you for contacting us, we will revert back to you soon!'];

        } else {
            return ['response' => false,'message' => 'Something went wrong, please try again later.'];
        }
    }

}
