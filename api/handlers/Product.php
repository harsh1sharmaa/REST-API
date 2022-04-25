<?php

namespace Api\Handlers;

use Phalcon\Mvc\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Http\Request;
use Micro;


class Product extends Controller
{

    function allproducts()
    {
        // echo "All products";

        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->products;
        $ans = $collection->find(array(), array("limit" => 10));

        $results = array();

        foreach ($ans as $value) {

            array_push($results, $value);
        }

        return json_encode($results);
    }

    function searchByName($name)
    {
        if ($name == '') {

            return "enter valid name";
        } else {


            $m = $this->mongo;
            $db = $m->store;
            $collection = $db->products;
            $ans = $collection->find(array("name" => $name), array("limit" => 10));

            $results = array();

            foreach ($ans as $value) {

                array_push($results, $value);
            }
            if (count($results) > 0) {
                return json_encode($results);
            } else {
                return "there is no product";
            }
        }
    }

    function searchByNameAndLimit($name = "", $limit = 10)
    {
        if (((int)$limit) < 1) {

            return  "limit is not valid";
        }

        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->products;
        $ans = $collection->find(array("name" => $name), array("limit" => (int)$limit));

        $results = array();

        foreach ($ans as $value) {

            array_push($results, $value);
        }
        if (count($results) > 0) {
            return json_encode($results);
        } else {
            return "there is no product";
        }
    }

    function jumpToPage($pageno = 1, $limit = 10)
    {
        if (((int)$pageno) < 1) {

            return  "page not found";
        }
        if (((int)$limit) < 1) {

            return  "limit is not valid";
        }
        $skipdoc = (int)$limit * ((int)$pageno - 1);


        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->products;

        // echo "limit";
        $results = array();
        // $i = 0;
        $ans = $collection->find(array(), array('skip' => (int) $skipdoc, "limit" => (int)$limit));

        foreach ($ans as $value) {
            array_push($results, $value);
        }
        if (count($results) > 0) {
            return json_encode($results);
        } else {
            return "there is no product";
        }
    }




    //************************************************************************************************ */
  


   
    function search($name = "")
    {
        $delimiter = '%20';
        $words = explode($delimiter, $name);

        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->product;


        $respo = array();

        foreach ($words as $word) {

            $ans = $collection->find(array('$or' => [['info.name' => array('$regex' => $word)], ['info.category' => array('$regex' => $word)], ['variation' => ['$elemMatch' => ["color" => array('$regex' => $word)]]]]));

            $results = array();
            foreach ($ans as $value) {
                array_push($results, $value);
            }
            array_push($respo, $results);
        }

        return json_encode($respo);
        die;
    }
  

  


   


    public function postdata()
    {
        $data = $this->request->getpost();
        // $robot =$this->request->getJsonRawBody();
        // $robot =$robot->body();
        //  return print_r($robot->body());
        // die;
        // echo $robot;
        // $this->response->setContent("Sorry, the e-mail doesn't exist.");
        // die;

        // $doc = array("name" => $data['field_name']);
        // $doc = array("productName" => $data['productName'], "quantity" => $data['quantity'], "username" => $data['username'], "email" => $data['email']);
        // $doc = array("name" => $data['field_name']);
        $doc = array("productId" => $data['productId'], "quantity" => $data['quantity'], "username" => $data['username'], "email" => $data['username'], "status" => "paid");
        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->order;
        $collection->insertOne($doc);
    }

    function getorder()
    {

        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->order;

        $ans = $collection->find();

        $results = array();

        foreach ($ans as $value) {

            array_push($results, $value);
        }

        echo json_encode($results);
    }

    function update()
    {
        return $this->request->getPut('id');
        // return "hello";

        // return $id;

        // $m = $this->mongo;
        // $db = $m->store;
        // $collection = $db->order;

        // $collection->updateOne(array('_id' => new \MongoDB\BSON\ObjectId($id)), array('$set' => ['email' => "xyx@gmail"]));
        // return;
    }
}
