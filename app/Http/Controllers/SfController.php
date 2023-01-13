<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB as DB;
use SemiorbitGuid\Guid;


class sfController extends Controller
{
    /**
     * Показать список всех пользователей приложения.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData($id, $edoid = false)
    {

        /*
        $sql="SELECT  zodDoc.sum docsummary ,zodDoc.no nb,zodDocData.price price,zodDo`cData.sum sum,zodDocData.cost/zodDocData.count cost,zodDocData.price/zodDocData.ratio sm,zodDoc.data,name,zodDocData.count count ,zodDocData.zodNom nom,(SELECT  okei FROM zodUnit WHERE zodUnit.row_id=zodDocData.zodUnit_id  LIMIT 1) okei,(SELECT  name FROM zodUnit WHERE zodUnit.row_id=zodDocData.zodUnit_id  LIMIT 1) okeiname, (SELECT  name FROM zodCompany WHERE id=zodDoc.zodCompany LIMIT 1) company,(SELECT  inn FROM zodCompany WHERE id=zodDoc.zodCompany LIMIT 1) inn,(SELECT  kpp FROM zodCompany WHERE id=zodDoc.zodCompany LIMIT 1) kpp, zodDoc.zodCompany companyid, (SELECT  vals FROM zodCompanyFields WHERE zodCompanyFields.zodCompany=zodDoc.zodCompany and zodField_id=18 LIMIT 1) adress, (SELECT  vals FROM zodCompanyFields WHERE zodCompanyFields.zodCompany=zodDoc.zodCompany and zodField_id=19 LIMIT 1) fadress, zodDoc.no n ,  zodDoc.data dt,(select zodNomLot.gtd from zodNomLot, zodDoc as doc2 where doc2.zod=zodDocData.zod and doc2.id=zodNomLot.zodDoc and doc2.data<zodDoc.data and zodNomLot.zod=zodDocData.zod and zodNomLot.zodNom=zodDocData.zodNom order by doc2.data desc limit 1) as gtd FROM zodDoc, zodDocData, zodNom WHERE zodDoc.zod=1 AND zodDoc.zoddoctype_id=".["upd"=>"2","bill"=>"1"][$type]." AND zodDoc.data >= '2021-01-01' AND zodDocData.zodDoc=zodDoc.id AND zodDocData.zod=zodDoc.zod AND zodDocData.del=0 AND zodNom.zod=zodDoc.zod AND zodNom.id=zodDocData.zodNom  and zodDoc.id=" . $id;

*/
$sql = "SELECT  zodDoc.sum docsummary ,zodDoc.no nb,zodDocData.price price,zodDocData.sum sum,zodDocData.cost/zodDocData.count cost,zodDocData.sum/zodDocData.count sm,zodDoc.data,name,zodDocData.count count ,zodDocData.zodNom nom,(SELECT  okei FROM zodUnit WHERE zodUnit.row_id=zodDocData.zodUnit_id  LIMIT 1) okei,(SELECT  name FROM zodUnit WHERE zodUnit.row_id=zodDocData.zodUnit_id  LIMIT 1) okeiname, (SELECT  name FROM zodCompany WHERE id=zodDoc.zodCompany LIMIT 1) company,(SELECT  inn FROM zodCompany WHERE id=zodDoc.zodCompany LIMIT 1) inn,(SELECT  kpp FROM zodCompany WHERE id=zodDoc.zodCompany LIMIT 1) kpp, zodDoc.zodCompany companyid, (SELECT  vals FROM zodCompanyFields WHERE zodCompanyFields.zodCompany=zodDoc.zodCompany and zodField_id=18 LIMIT 1) adress, (SELECT  vals FROM zodCompanyFields WHERE zodCompanyFields.zodCompany=zodDoc.zodCompany and zodField_id=19 LIMIT 1) fadress, zodDoc.no n ,  zodDoc.data dt,(select zodNomLot.gtd from zodNomLot, zodDoc as doc2 where doc2.zod=zodDocData.zod and doc2.id=zodNomLot.zodDoc and doc2.data<zodDoc.data and zodNomLot.zod=zodDocData.zod and zodNomLot.zodNom=zodDocData.zodNom order by doc2.data desc limit 1) as gtd FROM zodDoc, zodDocData, zodNom WHERE zodDoc.zod=1 AND zodDoc.zoddoctype_id=18 AND zodDoc.data >= '2021-01-01' AND zodDocData.zodDoc=zodDoc.id AND zodDocData.zod=zodDoc.zod AND zodDocData.del=0 AND zodNom.zod=zodDoc.zod AND zodNom.id=zodDocData.zodNom  and zodDoc.id=" . $id . " order by zodDocData.pos";

        $docsql=$sql;

        $goods = DB::select($sql, []);


        $sf = DB::select("SELECT * FROM zod01.zodDoc where zod01.zodDoc.zodDocMaster=$id and zodDocType_id=10", []);



        if (count($goods) == 0) {
            return response()->view('message', ["message" => "Запрос к БД не вернул данных:" . $sql], 200)->header('EDO-Error', "Empty SQL");
        }



        $toid = "1";
        $toid = $edoid == false ? file_get_contents("http://192.168.0.65/zod/edo/edoSbis.php?a=3&inn=" . $goods[0]->inn . (strlen($goods[0]->inn) == 10 ? "&kpp=" . $goods[0]->kpp : "") . "&k=lK_wh__rFg2") : $edoid;
        //$toid = file_get_contents("http://192.168.0.65/zod/edo/edoSbis.php?a=3&inn=" .$goods[0]->inn. "&kpp=" . $goods[0]->kpp. "&k=lK_wh__rFg2");

        if (strpos($toid, "ERR")) {
            return response()->view('message', ["message" => $toid])->header('EDO-Error', $toid);
        }
        $FadressArray = [];
        $UadressArray = [];

        $Uadress = $goods[0]->adress;
        $Fadress = $Uadress;




        if (!$UadressArray = $this->checkAdress($Uadress)) {
            return response()->view('message', ["message" => "Юридический адрес не соответствует формату:" . $Uadress . "[" . count(explode(",", $Uadress)) . "]"], 200)->header('EDO-Error', "adress error");
        }

        $FadressArray = $this->checkAdress($Fadress);
        $UadressArray = $this->checkAdress($Uadress);


        $sql = "SELECT code FROM zod01.zodCityRegion where zod01.zodCityRegion.name='" . trim($UadressArray[2]) . "'";
        $regionCode = DB::select($sql, []);

        if (count($regionCode) == 0) {
            return response()->view('message', ["message" => "Регион не соответствует формату:" . $Uadress], 200)->header('EDO-Error', "region error");
        }

        $fromid = "2BM-1001136430-100101001-201409171144111017157";
        $fromid = "2BE806b135aba9511e187a55cf3fc3369f0";
        $date = date("Ymd");

        $guid = str_replace("}", "", (str_replace("{", "", Guid::NewGuid())));

        $file = "ON_NSCHFDOPPR_" . $toid . "_" . $fromid . "_" . $date . "_" . $guid;



        return  response()->view("corsf", [
            'company' => $goods[0]->company,
            'inn' =>  $goods[0]->inn,
            'kpp' =>  $goods[0]->kpp,
            "fileid" => $file,
            "fromid" => $fromid,
            "toid" => $toid,
            "time" => date("h.i.s"),
            "date" => date("d.m.Y"),
            "sfnumber" => count($sf) != 0 ? $sf[0]->no : "",
            "sfdate" => count($sf) != 0 ? $this->convertDate($sf[0]->data) : "",
            "ttnnumber" =>  $goods[0]->n,
            "ttndate" =>  $this->convertDate($goods[0]->dt),
            "UAdressArray" => $UadressArray,
            "FAdressArray" => $FadressArray,
            "UAdress" => $Uadress,
            "FAdress" => $Fadress,
            "goods" => $goods,
            "docsum" => $goods[0]->docsummary,
            "key" => 0,
            "code" => $regionCode[0]->code,
            "roundType" => 0,
            "docsql"=>$docsql


        ], 200)->header('EDO-File', $file);
    }

    public function checkAdress($adress)
    {


        return count(explode(",", $adress)) === 10 || count(explode(",", $adress)) === 9 || count(explode(",", $adress)) === 12 ? explode(",", $adress) : false;
    }

    public function convertDate($date)
    {
        return  substr($date, 8, 2) . "." . substr($date, 5, 2) . "." . substr($date, 0, 4);
    }
}
