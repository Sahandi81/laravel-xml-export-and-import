<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use XMLWriter;

class XMLController extends Controller
{

    public function createXML()
    {
        $users = [
            [
                'firstname' => 'sahandi81'
            ],
        ];
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->startDocument();
        $xml->startElement('users');
        foreach($users as $user) {
            $xml->startElement('yo');
            $xml->writeAttribute('writeAttribute', 'writeAttributeValue');
            $xml->writeRaw('writeRaw');
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();

        $content = $xml->outputMemory();
        $xml = null;

        return response($content)->header('Content-Type', 'text/xml');
    }


    public function readXML()
    {
        $xmlDataString = file_get_contents(public_path('simple-users.xml'));
        $xmlObject = simplexml_load_string($xmlDataString);

        $json = json_encode($xmlObject);
        $phpDataArray = json_decode($json, true);

        if (count($phpDataArray['users']) > 0) {

            $dataArray = array();
            $count     = 1;
            foreach ($phpDataArray['users'] as $index => $data) {
                $dataArray[] = [
                    "name"          => $data['name'],
                    "email"         => $data['email'],
                    "password"      => Hash::make($data['pw'])
                ];
                $count++;
            }


            if (User::insert($dataArray))
                return response(['success', 'Data saved successfully!']);
            else
                return response(['failure', 'Data doesn\'t saved!']);
        }
        return response(['failure', 'No valid data found']);
    }
}
