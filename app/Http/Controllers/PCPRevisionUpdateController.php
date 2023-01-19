<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
class PCPRevisionUpdateController extends Controller
{
    public function PCPRevisionUpdate(Request $request){ 
        $configPath = '/Users/ferryirawan/Desktop/PCP_BE/config.json';
        $json = json_decode(file_get_contents($configPath), true); 
        $csi_url = '';
        $csi_site = '';
        foreach ($json as $fileData) {
            if($fileData['ConfigName'] == $request->header('ConfigName')){
                $csi_url = $fileData['URL'];
            }
        }

        if($csi_url == '' || $csi_url == null)
        return Response::json(array(
            'Success'   => false,
            'Code'      =>  404,
            'Message'   => 'No Config Name found'
        ), 404);

        $tokenData = $request->header('Authorization');
        $client = new Client();
        foreach ($request->input('revision_doc') as $revisionDoc) {
            $loadCollectionIDO = 'PCP_RevisionUpdate';
            $loadCollectionProperties = 'pcp_num, revision, stat';
            $loadCollectionFilter = "pcp_num = '".$revisionDoc['pcp_num']."'";
            $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
            $checkPCPExist = json_decode($loadCollectRes->getBody(), true);

            if($checkPCPExist['Items'] == null){
                return $checkPCPExist;
            } else if(count($checkPCPExist['Items']) > 0){
                foreach ($checkPCPExist['Items'] as $itemData) {
                    $deleteResult[] = 
                        [
                            'Action' => 4,
                            'ItemId' => $itemData['_ItemId'],
                        ];   
                }
            }

            foreach ($revisionDoc['revision_detail'] as $revisionData) {
                $revisionResult[] = [
                    [
                        'Name' => "pcp_num",
                        'Value' => $revisionDoc['pcp_num'],
                        'Modified' => true,
                        'ISNull' => false,
                    ],
                    [
                        'Name' => "revision",
                        'Value' => $revisionData['revision'],
                        'Modified' => true,
                        'ISNull' => false,
                    ],
                    [
                        'Name' => "stat",
                        'Value' => $revisionData['stat'],
                        'Modified' => true,
                        'ISNull' => false,
                    ]
                ];   
            }
        }

        if(count($deleteResult) > 0){
            $insertBody['Changes'] = $deleteResult;
            $insertRes = $client->request('POST', $csi_url.'/ido/update/PCP_RevisionUpdate?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse = json_decode($insertRes->getBody()->getContents(), true);
        }  

        if(count($revisionResult) > 0){
            foreach ($revisionResult as $data) {
                $revisionChanges[] = [
                    'Action' => 1,
                    'ItemId' => "",
                    'UpdateLocking' => "1",
                    'Properties' => $data
                ];
            }
            
            $insertBody['Changes'] = $revisionChanges;
            $insertRes = $client->request('POST', $csi_url.'/ido/update/PCP_RevisionUpdate?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse = json_decode($insertRes->getBody()->getContents(), true);
            return $insertResponse;
        }  
        return Response::json(array(
            'Success'   => false,
            'Code'      =>  404,
            'Message'   => 'No Revision needed to be updated'
        ), 404);
    }

    public function PCPGetRevisionUpdate(Request $request){ 
        $configPath = '/Users/ferryirawan/Desktop/PCP_BE/config.json';
        $json = json_decode(file_get_contents($configPath), true); 
        foreach ($json as $fileData) {
            if($fileData['ConfigName'] == $request->header('ConfigName')){
                $csi_url = $fileData['URL'];
            }
        }
        $tokenData = $request->header('Authorization');
        $client = new Client();
        
        if($tokenData == null || $tokenData == ""){
            $tokenErrorMessage = json_decode($token->getBody(), true)['Message'];
            $this->insertErrorLog($tokenErrorMessage, $client, $request, $tokenData);
            return Response::json(array(
                'Success'   => false,
                'Code'      =>  404,
                'Message'   =>  $tokenErrorMessage
            ), 404);
        }
        $loadCollectionIDO = 'PCP_RevisionUpdate';
        $loadCollectionProperties = 'pcp_num, revision, stat';
        $loadCollectionFilter = "";
        $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
        $checkPCPExist = json_decode($loadCollectRes->getBody(), true)['Items'];
        return $checkPCPExist;
    }
}

