<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
class PCPRevisionUpdateController extends Controller
{
    public function PCPBOMCopy(Request $request){ 
        $configPath = base_path()."/config.json";
        $json = json_decode(file_get_contents($configPath), true); 
        $csi_url = '';
        $csi_site = '';
        $deleteResult = [];
        $revisionResult = [];
        $deleteResult1 = [];
        $revisionResult1 = [];
        $deleteResult2 = [];
        $revisionResult2 = [];
        $deleteResult3 = [];
        $revisionResult3 = [];
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
        return $request->input('pcp_doc')['item'];
        if(($request->input('pcp_doc')['item'] == null || $request->input('pcp_doc')['item'] == "") && ($request->input('pcp_doc')['fa_item'] == null || $request->input('pcp_doc')['fa_item'] == "")){
            return Response::json(array(
                'Success'   => false,
                'Code'      =>  404,
                'Message'   => 'Item or FA Item must be filled'
            ), 404); 
        }
        return "Asd";
        $tokenData = $request->header('Authorization');
        $client = new Client();
        
        //PCP Sales
        $loadCollectionIDO = 'AS_PCP_SalesReqLines';
        $loadCollectionProperties = 'pcp_num';
        $loadCollectionFilter = "pcp_num = '".$request->input('pcp_doc')['pcp_num']."'";
        $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
        $checkPCPExist = json_decode($loadCollectRes->getBody(), true);
        if($checkPCPExist['Items'] == null && count($checkPCPExist['Items']) > 0){
            return $checkPCPExist;
        } else if($checkPCPExist['Items'] != null && count($checkPCPExist['Items']) > 0){
            foreach ($checkPCPExist['Items'] as $itemData) {
                $deleteResult[] = 
                    [
                        'Action' => 4,
                        'ItemId' => $itemData['_ItemId'],
                    ];   
            }
        }

        foreach ($request->input('pcp_doc')['pcp_sales_detail'] as $revisionDoc) {
            $revisionResult[] = [
                [
                    'Name' => "pcp_num",
                    'Value' => $request->input('pcp_doc')['pcp_num'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "revision",
                    'Value' => $request->input('pcp_doc')['revision'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "pcp_line",
                    'Value' => $revisionDoc['pcp_line'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "matl_item",
                    'Value' => $revisionDoc['matl_item'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "contigency_factor",
                    'Value' => $revisionDoc['contigency_factor'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "u_m",
                    'Value' => $revisionDoc['u_m'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "priority",
                    'Value' => $revisionDoc['priority'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "manual_flag",
                    'Value' => $revisionDoc['manual_flag'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "manual_yield",
                    'Value' => $revisionDoc['manual_yield'],
                    'Modified' => true,
                    'ISNull' => false,
                ]
            ];   
        }

        //PCP Process
        $loadCollectionIDO = 'AS_PCP_Process';
        $loadCollectionProperties = 'pcp_num';
        $loadCollectionFilter = "pcp_num = '".$request->input('pcp_doc')['pcp_num']."'";
        $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
        $checkPCPExist = json_decode($loadCollectRes->getBody(), true);
        if($checkPCPExist['Items'] == null && count($checkPCPExist['Items']) > 0){
            return $checkPCPExist;
        } else if($checkPCPExist['Items'] != null && count($checkPCPExist['Items']) > 0){
            foreach ($checkPCPExist['Items'] as $itemData) {
                $deleteResult1[] = 
                    [
                        'Action' => 4,
                        'ItemId' => $itemData['_ItemId'],
                    ];   
            }
        }

        foreach ($request->input('pcp_doc')['pcp_process_detail'] as $revisionDoc) {
            $revisionResult1[] = [
                [
                    'Name' => "pcp_num",
                    'Value' => $request->input('pcp_doc')['pcp_num'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "revision",
                    'Value' => $request->input('pcp_doc')['revision'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "oper_num",
                    'Value' => $revisionDoc['oper_num'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "wc",
                    'Value' => $revisionDoc['wc'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "cavity",
                    'Value' => $revisionDoc['cavity'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "cycle",
                    'Value' => $revisionDoc['cycle'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "mold",
                    'Value' => $revisionDoc['mold'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "operator",
                    'Value' => $revisionDoc['operator'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "setup",
                    'Value' => $revisionDoc['setup'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "layer",
                    'Value' => $revisionDoc['layer'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "tool_code",
                    'Value' => $revisionDoc['tool_code'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "combine",
                    'Value' => $revisionDoc['combine'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "contigency_factor",
                    'Value' => $revisionDoc['contigency_factor'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "remark",
                    'Value' => $revisionDoc['remark'],
                    'Modified' => true,
                    'ISNull' => false,
                ]
            ];   
        }

        //PCP Matl
        $loadCollectionIDO = 'AS_PCP_Matl';
        $loadCollectionProperties = 'pcp_num';
        $loadCollectionFilter = "pcp_num = '".$request->input('pcp_doc')['pcp_num']."'";
        $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
        $checkPCPExist = json_decode($loadCollectRes->getBody(), true);
        if($checkPCPExist['Items'] == null && count($checkPCPExist['Items']) > 0){
            return $checkPCPExist;
        } else if($checkPCPExist['Items'] != null && count($checkPCPExist['Items']) > 0){
            foreach ($checkPCPExist['Items'] as $itemData) {
                $deleteResult2[] = 
                    [
                        'Action' => 4,
                        'ItemId' => $itemData['_ItemId'],
                    ];   
            }
        }

        foreach ($request->input('pcp_doc')['pcp_matl_detail'] as $revisionDoc) {
            $revisionResult2[] = [
                [
                    'Name' => "pcp_num",
                    'Value' => $request->input('pcp_doc')['pcp_num'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "revision",
                    'Value' => $request->input('pcp_doc')['revision'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "pcp_line",
                    'Value' => $revisionDoc['pcp_line'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "oper_num",
                    'Value' => $revisionDoc['oper_num'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "y1",
                    'Value' => $revisionDoc['y1'],
                    'Modified' => true,
                    'ISNull' => false,
                ],
                [
                    'Name' => "y2",
                    'Value' => $revisionDoc['y2'],
                    'Modified' => true,
                    'ISNull' => false,
                ]
            ];   
        }

        //PCP Sales Header
        $loadCollectionIDO = 'AS_PCP_SalesHdr';
        $loadCollectionProperties = 'pcp_num';
        $loadCollectionFilter = "pcp_num = '".$request->input('pcp_doc')['pcp_num']."'";
        $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
        $checkPCPExist = json_decode($loadCollectRes->getBody(), true);
        if($checkPCPExist['Items'] == null && count($checkPCPExist['Items']) > 0){
            return $checkPCPExist;
        } else if($checkPCPExist['Items'] != null && count($checkPCPExist['Items']) > 0){
            foreach ($checkPCPExist['Items'] as $itemData) {
                $deleteResult3[] = 
                    [
                        'Action' => 4,
                        'ItemId' => $itemData['_ItemId'],
                    ];   
            }
        }

        $revisionResult3[] = [
            [
                'Name' => "pcp_num",
                'Value' => $request->input('pcp_doc')['pcp_num'],
                'Modified' => true,
                'ISNull' => false,
            ],
            [
                'Name' => "revision",
                'Value' => $request->input('pcp_doc')['revision'],
                'Modified' => true,
                'ISNull' => false,
            ],
            [
                'Name' => "item",
                'Value' => $request->input('pcp_doc')['item'],
                'Modified' => true,
                'ISNull' => false,
            ],
            [
                'Name' => "fa_item",
                'Value' => $request->input('pcp_doc')['fa_item'],
                'Modified' => true,
                'ISNull' => false,
            ],
            [
                'Name' => "qty_per_month",
                'Value' => $request->input('pcp_doc')['qty_per_month'],
                'Modified' => true,
                'ISNull' => false,
            ]
        ];   
        
        //PCP Sales
        if(count($deleteResult) > 0){
            $insertBody['Changes'] = $deleteResult;
            $loadCollectionIDO = 'AS_PCP_SalesReqLines';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
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
            $loadCollectionIDO = 'AS_PCP_SalesReqLines';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse = json_decode($insertRes->getBody()->getContents(), true);
        }  
        //PCP Process
        if(count($deleteResult1) > 0){
            $insertBody['Changes'] = $deleteResult1;
            $loadCollectionIDO = 'AS_PCP_Process';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse1 = json_decode($insertRes->getBody()->getContents(), true);
        }  

        if(count($revisionResult1) > 0){
            foreach ($revisionResult1 as $data) {
                $revisionChanges1[] = [
                    'Action' => 1,
                    'ItemId' => "",
                    'UpdateLocking' => "1",
                    'Properties' => $data
                ];
            }
            
            $insertBody['Changes'] = $revisionChanges1;
            $loadCollectionIDO = 'AS_PCP_Process';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse1 = json_decode($insertRes->getBody()->getContents(), true);
        }  

        //PCP Matl
        if(count($deleteResult2) > 0){
            $insertBody['Changes'] = $deleteResult2;
            $loadCollectionIDO = 'AS_PCP_Matl';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse2 = json_decode($insertRes->getBody()->getContents(), true);
        }  

        if(count($revisionResult2) > 0){
            foreach ($revisionResult2 as $data) {
                $revisionChanges2[] = [
                    'Action' => 1,
                    'ItemId' => "",
                    'UpdateLocking' => "1",
                    'Properties' => $data
                ];
            }
            
            $insertBody['Changes'] = $revisionChanges2;
            $loadCollectionIDO = 'AS_PCP_Matl';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse2 = json_decode($insertRes->getBody()->getContents(), true);
        }  

        //PCP Sales Header
        if(count($deleteResult3) > 0){
            $insertBody['Changes'] = $deleteResult3;
            $loadCollectionIDO = 'AS_PCP_SalesHdr';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse3 = json_decode($insertRes->getBody()->getContents(), true);
        }  

        if(count($revisionResult3) > 0){
            foreach ($revisionResult3 as $data) {
                $revisionChanges3[] = [
                    'Action' => 1,
                    'ItemId' => "",
                    'UpdateLocking' => "1",
                    'Properties' => $data
                ];
            }
            
            $insertBody['Changes'] = $revisionChanges3;
            $loadCollectionIDO = 'AS_PCP_SalesHdr';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'. $loadCollectionIDO .'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse3 = json_decode($insertRes->getBody()->getContents(), true);
        }  

        $pcp_sales_res = [];
        $pcp_process_res = [];
        $pcp_matl_res = [];
        $pcp_saleshdr_res = [];
        if ($insertResponse['Success']){
            $pcp_process_res = $insertResponse;
        }

        if ($insertResponse1['Success']){
            $pcp_sales_res = $insertResponse1;
        }

        if ($insertResponse2['Success']){
            $pcp_matl_res = $insertResponse2;
        }

        if ($insertResponse3['Success']){
            $pcp_saleshdr_res = $insertResponse3;
        }
        //INVOKE START
        $invokeResult = '';
        $invokeIDO = 'SLItems';
        $invokeMethod = 'AS_PCP_CopyBOMSp';
        $invokeBody = [
            "", //INFOBAR
            $request->input('pcp_doc')['pcp_num'],
            $request->input('pcp_doc')['revision'],
            "", //SITE
        ];
        $invokeRes = $client->request('POST', $csi_url . "/ido/invoke/" . $invokeIDO . "?method=" . $invokeMethod . "", ['headers' => ['Authorization' => $tokenData], 'json' => $invokeBody]);
        $invokeResponse = json_decode($invokeRes->getBody()->getContents(), true);
        if ($invokeResponse['ReturnValue'] != 0) {
            return Response::json(array(
                'Success'   => false,
                'Code'      => 404,
                'Message'   =>  $invokeResponse['Parameters'][0]
            ), 404);
        } 
        //INVOKE END

        if ($pcp_saleshdr_res != [] && $pcp_sales_res != [] && $pcp_process_res != [] && $pcp_matl_res != []){
            return [
                'Success'   => true,
                'Code'      => 200,
                'Message'   => null
            ];
        } else {
            return Response::json(array(
                'Success'   => false,
                'Code'      => 404,
                'Message'   => 'BOM Copy failed'
            ), 404);
        }
    }

    public function PCPRevisionUpdate(Request $request){ 
        $configPath = base_path()."/config.json";
        $json = json_decode(file_get_contents($configPath), true); 
        $csi_url = '';
        $csi_site = '';
        $deleteResult = [];
        $revisionResult = [];
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
        $request = json_decode(json_encode(json_decode(preg_replace('/\xc2\xa0/', '', $request->getContent())), JSON_PRETTY_PRINT),true);
        
        foreach ($request['revision_doc'] as $revisionDoc) {
            $loadCollectionIDO = 'AS_PCP_RevisionStats';
            $loadCollectionProperties = 'pcp_num, revision, stat';
            $loadCollectionFilter = "pcp_num = '".$revisionDoc['pcp_num']."'";
            $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
            $checkPCPExist = json_decode($loadCollectRes->getBody(), true);
            if($checkPCPExist['Items'] == null && count($checkPCPExist['Items']) > 0){
                return $checkPCPExist;
            } else if($checkPCPExist['Items'] != null && count($checkPCPExist['Items']) > 0){
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
            $loadCollectionIDO = 'AS_PCP_RevisionStats';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'.$loadCollectionIDO.'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
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
            $loadCollectionIDO = 'AS_PCP_RevisionStats';
            $insertRes = $client->request('POST', $csi_url.'/ido/update/'.$loadCollectionIDO.'?refresh=true', ['headers' => ['Authorization' => $tokenData], 'json' => $insertBody]);
            $insertResponse = json_decode($insertRes->getBody()->getContents(), true);
            if ($insertResponse['Success']){
                return [
                    'Success'   => true,
                    'Code'      => 200,
                    'Message'   => null
                ];
            }
        }  
        return Response::json(array(
            'Success'   => false,
            'Code'      =>  404,
            'Message'   => 'No Revision needed to be updated'
        ), 404);
    }

    public function PCPGetRevisionUpdate(Request $request){ 
        $configPath = base_path()."/config.json";
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
        $loadCollectionIDO = 'AS_PCP_RevisionStats';
        $loadCollectionProperties = 'pcp_num, revision, stat';
        $loadCollectionFilter = "";
        $loadCollectRes = $client->request('GET', $csi_url . "/ido/load/" . $loadCollectionIDO . "?properties=" . $loadCollectionProperties . "&filter=" . $loadCollectionFilter, ['headers' => ['Authorization' => $tokenData]]);
        $checkPCPExist = json_decode($loadCollectRes->getBody(), true)['Items'];
        return $checkPCPExist;
    }
}

