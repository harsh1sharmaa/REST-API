<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use app\component\Myescaper;

use GuzzleHttp\Client;



class OneController extends Controller
{

   

    public function addorderAction()
    {

        $data = $this->request->getPost();
        // print_r($data);
        // die();
        $products = $this->getproduct();

        // echo "<pre>";
        // print_r($products);
        // // foreach ($products as $key => $value) {
        // //     echo "<pre>";
        // //     print_r($value);
        // // }
        // die();
        if (isset($data['submit'])) {

            // print_r($data);
            // die();
            $id = $data['id'];
            $quantity = $data['quantity'];
            $email = $data['email'];
            $uname = $data['uname'];
            $token = $data['token'];

            $arr = array("productId" => $id, "quantity" => $quantity, "username" => $uname, "email" => $email, "token" => $token);
            // $header = array("Content-Type" => "application/json");

            $form = [
                'form_params' => $arr
            ];

            $client = new Client();
            $r = $client->request('POST', "192.168.2.24:8080/api/invoices/post?key=$token", $form);

            // print_r($r);
            // die();
        }
        $this->view->message =$products;
    }


   

    public function getproduct()
    {

        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->products;

        $ans = $collection->find([], array("limit" => 50));
        $ans=$ans->toArray();
      
        return json_decode(json_encode($ans), 1);
        // die;
    }
}
