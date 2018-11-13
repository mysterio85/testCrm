<?php

namespace Test;


class ApiTest extends \PHPUnit_Framework_TestCase
{
    Const URI = "http://leboncoin.local/";

    /**
     * @param       $methode
     * @param array $datas
     *
     * @return mixed
     */
    public function apiCheck($methode, $datas = [])
    {
        $api = self::URI . $methode;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($curl);
        curl_close($curl);
        return $return;

    }

    /**
     * Test nom palindrome
     */
    public function testPalindrome()
    {
        $datas = array(
            'name' => 'ono',
        );

        $this->assertEquals('{"response":true}',
            $this->apiCheck('palindrome', $datas));

    }

    /**
     * Test du nom non palindrome
     */
    public function testPalindromeFalse()
    {
        $datas = array(
            'name' => 'gnamien',
        );

        $this->assertNotEquals('{"response":true}',
            $this->apiCheck('palindrome', $datas));

    }

    /**
     * Test avec entrée bon email
     */
    public function testEmail()
    {
        $datas = array(
            'email' => 'essoagenial@yahoo.fr',
        );

        $this->assertEquals('{"response":true,"message":"L\'email est au bon format"}',
            $this->apiCheck('email', $datas));

    }

    /**
     * test avec entrée mauvais Email
     */
    public function testEmailFalse()
    {
        $datas = array(
            'email' => 'essoa',
        );

        $this->assertNotEquals('{"response":true,"message":"L\'email est au bon format"}',
            $this->apiCheck('email', $datas));

    }

}