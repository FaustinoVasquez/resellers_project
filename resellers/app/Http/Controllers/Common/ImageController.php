<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    private $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }


    public function index($sku, Request $request){
        $philips ='http://d1vp05nmmxpish.cloudfront.net/catalog/philips/';
        $osram = 'http://d1vp05nmmxpish.cloudfront.net/catalog/osram/';
        $phoenix ='http://d1vp05nmmxpish.cloudfront.net/catalog/phoenix/';
        $ushio ='http://d1vp05nmmxpish.cloudfront.net/catalog/ushio/';
        $generic ='http://d1vp05nmmxpish.cloudfront.net/catalog/generic/';

        //$category = $this->imageRepository->getCategory($sku);

	$category = "http://photos.discount-merchant.com/photos/sku/".$sku."/index.php";

        if($request->ajax()){
            return $category;
        }

    }

}
