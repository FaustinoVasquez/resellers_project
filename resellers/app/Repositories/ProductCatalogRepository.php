<?php
namespace App\Repositories;

use App\Models\ProductCatalog;
use Illuminate\Support\Facades\DB;


class ProductCatalogRepository extends BaseRepository
{
    public function getModel()
    {
        return new ProductCatalog();
    }

    public function getIfSkuIsAvailable($sku){

      return $this->newQuery()
      ->select( DB::raw("CASE WHEN (gs.[TotalStock] > '1' OR ProductCatalog.[AlwaysInStock] = '1')
      THEN '1' ELSE '0' END AS Availability")
      )->leftjoin('Global_Stocks as gs','ProductCatalog.ID','=','gs.ProductCatalogId')
      ->where('ProductCatalog.ID', DB::select(DB::raw("select inventory.dbo.fn_Catalog_2_SKU('" . $sku . "')"))[0]->computed0)
      ->groupBy(['gs.TotalStock','ProductCatalog.AlwaysInStock'])->first()->Availability;
    }


    public function getMitSkuList(){
        $string='';

        $pdo = DB::connection()->getPdo();
        $query = "SELECT CAST(PC.[ID] AS NVARCHAR(10)) AS [SKU], PC.[Name] AS [Description] FROM [Inventory].[dbo].[ProductCatalog] AS PC WHERE PC.[CategoryID] IN ('5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','25','26','61','62','63','87','88','89','90','91','93','94','95','96','97','98','99','100','101','102','103','104','105','106','107','108','109','110','111','112','113','114','115','116','117','118','119','120','121','122','123','124','125','126','127','128','129','130','131','132','133','134','135','136','137','138','139','140','141','142','143','144','145','146','147')
    UNION ALL
    SELECT PJD.[CatalogID]+'-G' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Generic w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKU] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-OP' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Philips w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUPH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-OO' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Osram w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUOS] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-OX' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Phoenix w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUPX] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-OU' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Ushio w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUUSH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-BG' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Generic Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKU] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-BOP' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Philips Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUPH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-BOO' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Osram Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUOS] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-BOX' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Phoenix Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUPX] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
    UNION ALL
    SELECT PJD.[CatalogID]+'-BOU' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Ushio Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUUSH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL";

    foreach($pdo->query($query) as $row){
            $string .= $row['SKU'].":".$row['SKU'].'--'.$row['Description'].';';
        }
       return substr($string, 0, -1);
    }

    public function getMitSkuList1($req){
        $pdo = DB::connection()->getPdo();
        $from = ($req->get("start") > 0 )? $req->get("start") : $req->get("start")+1 ;
        $length =($req->get("length") + $from) - 1;
        if($req->get("search")["value"] != ""){
          $constraint = "WHERE TBL.Description like ? OR TBL.SKU like ?";
          $options = [
            "%".$req->get("search")["value"]."%",
            "%".$req->get("search")["value"]."%",
            $from,
            $length
          ];
        }
        else{
          $constraint = "";
          $options = [$from,$length];
        }

        $query = "
        WITH mytable AS (

        SELECT TBL.SKU, TBL.Description, ROW_NUMBER() OVER (ORDER BY SKU Asc) AS RowNumber
        FROM (
          SELECT CAST(PC.[ID] AS NVARCHAR(10)) AS [SKU], PC.[Name] AS [Description] FROM [Inventory].[dbo].[ProductCatalog] AS PC WHERE PC.[CategoryID] IN ('5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','25','26','61','62','63','87','88','89','90','91','93','94','95','96','97','98','99','100','101','102','103','104','105','106','107','108','109','110','111','112','113','114','115','116','117','118','119','120','121','122','123','124','125','126','127','128','129','130','131','132','133','134','135','136','137','138','139','140','141','142','143','144','145','146','147')
            UNION ALL
            SELECT PJD.[CatalogID]+'-G' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Generic w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKU] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-OP' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Philips w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUPH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-OO' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Osram w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUOS] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-OX' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Phoenix w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUPX] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-OU' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Ushio w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUUSH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-BG' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Generic Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKU] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-BOP' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Philips Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUPH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-BOO' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Osram Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUOS] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-BOX' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Phoenix Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUPX] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
            UNION ALL
            SELECT PJD.[CatalogID]+'-BOU' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Ushio Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUUSH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
        ) TBL ".$constraint."

        )
        SELECT SKU, Description, RowNumber
        FROM mytable
        WHERE RowNumber BETWEEN ? AND ?";
        $output["query"] = $query;
        $prepared = $pdo->prepare($query);
        $prepared->execute($options);
        $result = $prepared->fetchAll();

        $output["data"] = $result;

        $query = "
        SELECT COUNT(*) AS Total
        FROM (
        SELECT CAST(PC.[ID] AS NVARCHAR(10)) AS [SKU], PC.[Name] AS [Description] FROM [Inventory].[dbo].[ProductCatalog] AS PC WHERE PC.[CategoryID] IN ('5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','25','26','61','62','63','87','88','89','90','91','93','94','95','96','97','98','99','100','101','102','103','104','105','106','107','108','109','110','111','112','113','114','115','116','117','118','119','120','121','122','123','124','125','126','127','128','129','130','131','132','133','134','135','136','137','138','139','140','141','142','143','144','145','146','147')
          UNION ALL
          SELECT PJD.[CatalogID]+'-G' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Generic w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKU] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-OP' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Philips w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUPH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-OO' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Osram w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUOS] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-OX' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Phoenix w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUPX] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-OU' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Ushio w/Housing' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[EncSKUUSH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-BG' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Generic Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKU] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-BOP' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Philips Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUPH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-BOO' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Osram Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUOS] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-BOX' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Phoenix Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUPX] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
          UNION ALL
          SELECT PJD.[CatalogID]+'-BOU' AS [SKU], PJD.[Brand]+' '+PJD.[PartNumber]+' Ushio Bare' FROM [MITDB].[dbo].[ProjectorData] AS PJD WHERE PJD.[BareSKUUSH] != '' AND PJD.[EncSKU] IS NOT NULL AND PJD.[CatalogID] IS NOT NULL
      ) TBL ".$constraint."
        ";

        $prepared = $pdo->prepare($query);
        if($req->get("search")["value"] != ""){
          $prepared->execute([
            $req->get("search")["value"]."%",
            $req->get("search")["value"]."%",
          ]);
        }
        else{
          $prepared->execute();
        }
        $output["recordsTotal"] = $prepared->fetch()["Total"];
        $output["recordsFiltered"] = $output["recordsTotal"];
        $output["InfoA"] = $req->all();
        return $output;
    }





}
