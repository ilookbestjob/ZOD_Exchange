<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB as DB;
use SemiorbitGuid\Guid;


class CheckactController extends Controller
{
    /**
     * Показать список всех пользователей приложения.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData($id)
    {

        $docs = DB::select("SELECT  zd.data, zd.no, za.comm, zd.zodDocType_id,zt.name,za.debit, za.credit from zod00.zodDocDataAct za,zodDoc zd ,zodDocType zt where zodDocType_id=zt.row_id and zodDoc2=zd.id and zd.zod=1 and zd.zod=za.zod and za.zodDoc=" . $id, []);

        $sdebit = 0;
        $scredit = 0;

        foreach ($docs as $doc) {
            $sdebit+=$doc->debit;
            $scredit+=$doc->credit;
        }

        $header = DB::select("select za.*, zd.no,zd.data, (SELECT  name FROM zodCompany WHERE id=zd.zodCompany LIMIT 1) company,(SELECT  inn FROM zodCompany WHERE id=zd.zodCompany LIMIT 1) inn,(SELECT  kpp FROM zodCompany WHERE id=zd.zodCompany LIMIT 1) kpp, (SELECT  vals FROM zodCompanyFields WHERE zodCompanyFields.zodCompany=zd.zodCompany and zodField_id=18 LIMIT 1) adress, (SELECT  vals FROM zodCompanyFields WHERE zodCompanyFields.zodCompany=zd.zodCompany and zodField_id=19 LIMIT 1) fadress from zodDoc zd, zodDocAct za where za.id=zd.id and zd.id=" . $id, []);


        $toid = "1";
        $toid = file_get_contents("http://192.168.0.65/zod/edo/edoSbis.php?a=3&inn=" . $header[0]->inn . "&kpp=" . $header[0]->kpp . "&k=lK_wh__rFg2");

        if (strpos($toid, "ERR")) {
            return response()->view('message', ["message" => $toid])->header('EDO-Eroor', $toid);
        }


        $FadressArray = [];
        $UadressArray = [];

        $Fadress = $header[0]->adress;
        $Uadress = $header[0]->fadress;

        /*

        if (!$FadressArray = $this->checkAdress($Fadress)) {
            return response()->view('message', ["message" => "Фактический адрес не соответствует формату:".$Fadress])->header('EDO-Error', "fact adress error");
        }



        if (!$UadressArray = $this->checkAdress($Uadress)) {
            return response()->view('message', ["message" => "Юридический адрес не соответствует формату"],200)->header('EDO-Error', "adress error");
        }

*/
        $UadressArray = $this->checkAdress($Uadress);
        $UadressArray = $this->checkAdress($Uadress);

        $fromid = "2BM-1001136430-100101001-201409171144111017157";
        $fromid = "2BE806b135aba9511e187a55cf3fc3369f0";
                   
        $date = date("Ymd");

        $guid = str_replace("}", "", (str_replace("{", "", Guid::NewGuid())));

        $file = "ON_ACCOUNTS___" . $date . "_" . $guid;

        return  response()->view("checkact", [
            'company' => str_replace('"', '&quot;', $header[0]->company),
            'inn' =>  $header[0]->inn,
            'kpp' =>  $header[0]->kpp,
            "fileid" => $file,
            "fromid" => $fromid,
            "toid" => $toid,
            "time" => date("h.i.s"),
            "date" => date("d.m.Y"),
            "number" =>  $header[0]->no,
            "date" => $this->convertDate($header[0]->data),

            "UAdressArray" => $UadressArray,
            "FAdressArray" => $FadressArray,
            "docs" => $docs,
            "startBalance" => $header[0]->startBalance,
            "endBalance" => $header[0]->startBalance*1-$scredit*1+$sdebit*1,
            "difference" => $header[0]->difference,
            "startdate" => $header[0]->dataStart,
            "enddate" => $header[0]->dataEnd,
            "key" => 0,
            "scr" => $scredit,
            "sdt" => $sdebit

        ], 200)->header('EDO-File', $file);
    }

    public function checkAdress($adress)
    {


        return count(explode(",", $adress)) === 10 || count(explode(",", $adress)) === 9 ? explode(",", $adress) : false;
    }

    public function convertDate($date)
    {
        return  substr($date, 8, 2) . "." . substr($date, 5, 2) . "." . substr($date, 0, 4);
    }
}
