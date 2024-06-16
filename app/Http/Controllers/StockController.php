<?php

namespace App\Http\Controllers;

use App\Models\MvtProduct;
use App\Models\storeProduct;
use Illuminate\Http\Request;
class StockController extends Controller
{
    public function index()
    {
      return Stock::select('id','id_Store','id_delivery','id_vehicule','id_TypePanne','DateMvt','TypeMvt','Reference','NumBon','Qte','Tva','Price','observation','login','Kilometrage','Extra','idCiterne','Designation')->get();
    }
    public function parameter($parameter)
    {
        return request()->has($parameter);
    }

//Aficher   
public function loadStore(Request $request)
{
   // $auth = $request->auth;
    // if (!isset($auth['accountID'], $auth['user'], $auth['password'])) {
    //     return response([
    //         'message' => 'Error Authentication',
    //     ], 401);
    // }
    try {
      
       
            $StoreProducts = storeProduct::get([
                'id',
                'id_Store',
                'Reference',
                'Qte',


            ])->toArray();
        
    } catch (Exception $e) {
        echo 'Message: loadModify' . $e->getMessage();
        return response(['message' => ''], 400);
    }
    return response($StoreProducts, 200);
}




//Ajouter

public function AddMvt(Request $request)
{
//  $auth = $request->auth;
//  if (!isset($auth['accountID'], $auth['user'], $auth['password'])) {
//      return response([
//          'message' => 'Error Authentication',
//      ], 401);
//  }
 $id = 0;
 if (isset($_GET["u"])) {
     try {
         $d = json_decode($_GET["u"]);
         if ($d[0]->Reference != "" && $d[0]->id_Store != "" && $d[0]->DateMvt != "") {
             $Mvt = array();
             $BonS = "";
             if ($d[0]->TypeMvt == "S") {
                 $BonS = "DA" . date("y");
                 $cmfs = mvtproduct::select('NumBon')//->where('accountID', $auth['accountID'])
                 ->where('NumBon', 'LIKE', $BonS . '%')->orderBy("NumBon", "desc")->first();
                 $BonS .= date("m");
                 if ($cmfs && isset($cmfs['NumBon'])) {
                     $num = $cmfs['NumBon'];
                     $last4Num = str_split($num, 6);
                     $BonS .= str_pad(intval($last4Num[1] + 1), 4, '0', STR_PAD_LEFT);
                 } else {
                     $BonS .= '0001';
                 }
             }
             for ($i = 0; $i < count($d); $i++) {
                 $SP = storeProduct::select('id', 'Qte')//->where('accountID', $auth['accountID'])
                 ->where('id_Store', $d[$i]->id_Store)->where('Reference', $d[$i]->Reference)->first();
                 if ($SP) {
                     if ($d[0]->TypeMvt == "S") {
                         $Qtes = $SP['Qte'] - $d[$i]->Qte;
                     } else {
                         $Qtes = $SP['Qte'] + $d[$i]->Qte;
                     }
                     //StoreProduct::where('accountID', $auth['accountID'])->where('id', $SP['id'])->update("Qte", $Qtes);
                     storeProduct:://where('accountID', $auth['accountID'])->
                     where('id', $SP['id'])->update(['Qte' => $Qtes]);
                 } else {
                     $Qte = $d[0]->TypeMvt == "S" ? -$d[$i]->Qte : $d[$i]->Qte;
                     $data = array(
                         //'accountID' => $auth['accountID'],
                          'id_Store' => $d[$i]->id_Store,
                         'Reference' => $d[$i]->Reference, 'Qte' => $Qte,
                     );
                     storeProduct::insert($data);
                 }
                 $Mvt[] = array(
                    // 'accountID' => $auth['accountID'], 
                    'DateMvt' => $d[$i]->DateMvt, 'id_Store' => $d[$i]->id_Store, 'TypeMvt' => $d[$i]->TypeMvt,
                     'Reference' => $d[$i]->Reference, 'Qte' => $d[$i]->Qte, 'Price' => $d[$i]->Price, 'observation' => $d[$i]->observation,
                     'id_Vehicule' => $d[$i]->id_Vehicule, 'Kilometrage' => $d[$i]->Kilometrage, 'id_TypePanne' => $d[$i]->id_TypePanne,
                     'Tva' => $d[$i]->Tva, 'Extra' => $d[$i]->Extra,'NumBon' => $BonS,'Designation'=>$d[$i]->Designation ,'login' => $d[$i]->login,'idCiterne'=>$d[$i]->idCiterne,//S'created_at' => time(),
                 );
             }
             $id = mvtproduct::insert($Mvt);
             return response(['message' => 'Added Successfuly', "id" => $id], 200);
         }
     } catch (Exception $e) {
         echo 'Message: ' . $e->getMessage();
         return response(['message' => $e->getMessage()], 400);
     }
 }
 return response(['message' => 'Refus'], 402);
}                                            


//supprimer
public function DeleteMvt(Request $request)
    {
        // $auth = $request->auth;
        // if (!isset($auth['accountID'], $auth['user'], $auth['password'])) {
        //     return response([
        //         'message' => 'Error Authentication',
        //     ], 401);
        // }
        if (isset($_GET["Bon"]) && !empty($_GET["Bon"])) {
            $d = $_GET["Bon"];
            $MvtProducts = mvtproduct::where('NumBon', $d)->get()->toArray();
            for ($i = 0; $i < count($MvtProducts); $i++) {
                $SP = StoreProduct::select('id', 'Qte')->where('id_Store', $MvtProducts[$i]['id_Store'])->where('Reference', $MvtProducts[$i]['Reference'])->first();
                if ($SP) {
                    if ($MvtProducts[$i]['TypeMvt'] == "S") {
                        $Qtes = $SP['Qte'] - $MvtProducts[$i]['Qte'];
                    } else {
                        $Qtes = $SP['Qte'] + $MvtProducts[$i]['Qte'];
                    }
                    StoreProduct::where('id', $SP['id'])->update(['Qte' => $Qtes]);

                   
                }

            }
           mvtproduct::where('NumBon', $d)->delete();
            return response(['message' => 'Deleted Successfuly'], 200);
        }
   if (isset($_GET["id"]) && !empty($_GET["id"])) {
            $d = $_GET["id"];
            $MvtProducts = mvtproduct:://where('accountID', $auth['accountID'])->
            where('id', $d)->get()->toArray();

                $SP = StoreProduct::select('id', 'Qte')//->where('accountID', $auth['accountID'])
                ->where('id_Store', $MvtProducts[0]['id_Store'])->where('Reference', $MvtProducts[0]['Reference'])->first();
                if ($SP) {
                    if ($MvtProducts[0]['TypeMvt'] == "S") {
                        $Qtes = $SP['Qte'] + $MvtProducts[0]['Qte'];
                    } else {
                        $Qtes = $SP['Qte'] - $MvtProducts[0]['Qte'];
                    }
                    StoreProduct:://where('accountID', $auth['accountID'])->
                    where('id', $SP['id'])->update(['Qte' => $Qtes]);
                }

           mvtproduct:://where('accountID', $auth['accountID'])->
           where('id', $d)->delete();
            return response(['message' => 'Deleted Successfuly'], 200);
        }     
        return response(['message' => 'Refus'], 402);
    }
  
    public function loadMvt(Request $request)
    {
        //$auth = $request->auth;
    //     if (!isset($auth['accountID'], $auth['user'], $auth['password'])) {
    //         return response([
    //             'message' => 'Error Authentication'
    //         ], 401);
    //     }
        try {
            if (isset($_GET["Bon"])) {
                $Bon = $request->input('Bon');
                $MvtProducts = MvtProduct::select('DateMvt', 'NumBon', 'id_Store')
                    ->distinct()//->where('accountID', $auth['accountID'])
                    ->where('TypeMvt', 'S')
                    ->get()
                    ->toArray();
            } else {
                //->where('accountID', $auth['accountID'])
                $where = "1=1";
                if ($request->has('id_Store')) {
                    $where .= " and id_Store = '{$request->input('id_Store')}'";
                }
                if ($request->has('st') && $request->has('et')) {
                    $where .= " and DateMvt between '{$request->input('st')}' and '{$request->input('et')}'";
                }
                $MvtProducts = Mvtproduct::select('id', 'id_Store', 'id_delivery', 'TypeMvt', 'id_Vehicule', 'id_TypePanne', 'DateMvt', 'Reference', 'NumBon', 'Qte', 'Tva', 'Price', 'observation', 'Kilometrage', 'Extra', 'idCiterne', 'Designation', 'login')
                    ->whereRaw($where)
                    ->get()
                    ->toArray();
            }
            return response()->json($MvtProducts, 200);
        } catch (Exception $e) {
            return response(['message' => 'Error loading Mvt'], 400);
        }
    
    
    }
}
    