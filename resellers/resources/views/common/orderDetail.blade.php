

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <a href="#" class="linkClass"></href><h4><span class ="glyphicon glyphicon-arrow-left"></span> Back {{$from}}</h4></a>
            </div>
            <div class="col-sm-6">
                <h4 class="pull-right">Order: {{$orderNumber}} <span class="glyphicon glyphicon-pencil"></span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                        <tr>
                            <th colspan="2">Sold To:</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Name:</td>
                            <td><strong>{{ucwords(strtolower($soldData['FullName']))}}</strong></td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>{{ucwords(strtolower($soldData['Address']))}}</td>
                        </tr>
                        <tr>
                            <td>City:</td>
                            <td>{{ucwords(strtolower($soldData['City']))}}, {{$soldData['State']}} {{$soldData['Zip']}} {{$soldData['Country']}}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><a href="mailto:#">{{strtolower($soldData['Email'])}}</td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>{{$soldData['Phone']}}</td>
                        </tr>
                        <tr>
                            <td>Company:</td>
                            <td>{{ucwords(strtolower($soldData['Company']))}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                        <tr>
                            <th colspan="2">Ship to:</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Name:</td>
                            <td><strong>{{ucwords(strtolower($shipData['ShipName']))}}</strong></td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>{{ucwords(strtolower($shipData['ShipAddress']))}}</td>
                        </tr>
                        <tr>
                            <td>City:</td>
                            <td>{{ucwords(strtolower($shipData['ShipCity']))}}, {{$shipData['ShipState']}} {{$shipData['ShipZip']}} {{$shipData['ShipCountry']}}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><a href="mailto:#">{{strtolower($shipData['ShipEmail'])}}</td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>{{$shipData['ShipPhone']}}</td>
                        </tr>
                        <tr>
                            <td>Company:</td>
                            <td>{{ucwords(strtolower($shipData['ShipCompany']))}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:10%">SKU</th>
                            <th class="text-center" style="width:30%">Product</th>
                            <th class="text-center" style="width:10%">S/N</th>
                            <th class="text-center" style="width:10%">Price Unit</th>
                            <th class="text-center" style="width:10%">QTY Ordered</th>
                            <th class="text-center" style="width:10%">Status</th>
                            <th class="text-center" style="width:10%">Ext Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td class='text-center'>{{$product->SKU}}</td>
                                <td class='text-left'>{{$product->Product}}</td>
                                <td class='text-center'>{{$product->SerialNumber}}</td>
                                <td class='text-right'>{{money_format('%.2n',$product->PricePerUnit)}}</td>
                                <td class='text-center'>{{$product->QuantityOrdered}}</td>
                                <td class='{{$orderStatus[$product->Status]}} text-center'>{{$product->Status}}</td>
                                <td class='text-right'>{{money_format('%.2n',$product->extPrice)}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                        <tr>
                            <th class="text-center">Orders Comments</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class='text-left'>{{$comments['Comments']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                        <tr>
                            <th colspan="2">Totals:</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class='text-right'>Sub Total</td>
                            <td class='text-right'>{{money_format('%.2n',$totals['FinalProductTotal'])}}</td>
                        </tr>
                        <tr>
                            <td class='text-right'>SurCharges</td>
                            <td class='text-right'>{{money_format('%.2n',$totals['Surcharge'])}}</td>
                        </tr>
                        <tr>
                            <td class='text-right'>Discounts</td>
                            <td class='text-right'>{{money_format('%.2n',$totals['Discount'])}}</td>
                        </tr>
                        <tr>
                            <td class='text-right'>Coupons</td>
                            <td class='text-right'>{{money_format('%.2n',$totals['CouponDiscount'])}}</td>
                        </tr>
                        <tr>
                            <td class='text-right'>Shipping</td>
                            <td class='text-right'>{{money_format('%.2n',$totals['ShippingTotal'])}}</td>
                        </tr>
                        <tr>
                            <td class='text-right'>Sales Tax</td>
                            <td class='text-right'>{{money_format('%.2n',$totals['TaxTotal'])}}</td>
                        </tr>
                        <tr>
                            <td class='text-right'>Grand Total</td>
                            <td class='text-right'>{{money_format('%.2n',$totals['FinalGrandTotal'])}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Tracking #</th>
                            <th class="text-center">Carrier</th>
                            <th class="text-center">Service</th>
                            <th class="text-center">Notes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tracks as $track)
                            <tr>
                                <td class='text-center'>{{$track->DateAdded}}</td>
                                @if($track->Carrier == 'UPS')
                                    <td class='text-center'><a href= 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=en_us&InquiryNumber1={{$track->TrackingID}}&track.x=0&track.y=0' target='_blank'>{{$track->TrackingID}}</a></td>
                                @elseif($track->Carrier == 'USPS')
                                    <td class='text-center'><a href= 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum={{$track->TrackingID}}' target='_blank'>{{$track->TrackingID}}</a></td>
                                @elseif($track->Carrier =='FDXE')
                                    <td class='text-center'><a href= 'http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers={{$track->TrackingID}}' target='_blank'>{{$track->TrackingID}}</a></td>
                                @elseif($track->Carrier =='FXM')
                                    <td class='text-center'><a href= 'http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers={{$track->TrackingID}}' target='_blank'>{{$track->TrackingID}}</a></td>
                                @elseif($track->Carrier =='FDXG')
                                    <td class='text-center'><a href= 'http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers={{$track->TrackingID}}' target='_blank'>{{$track->TrackingID}}</a></td>
                                @endif
                                <td class='text-center'>{{$track->Carrier}}</td>
                                <td class='text-center'>{{$track->ShippersMethod}}</td>
                                <td class='text-center'>{{$track->Notes}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
