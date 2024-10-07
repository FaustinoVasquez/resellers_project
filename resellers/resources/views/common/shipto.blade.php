{{--<h3>CustomerID:{{$shipFromInformation['CustomerID']}} WebOrder:{{$webOrder}}</h3>--}}


<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <form role="form">
                <div class="form-group">

                    {!! Form::text('shipFromName', $shipFromInformation['FullName'], ['id'=>'shipFromName','class' => 'form-control','placeholder'=>'FullName' ]) !!}
                </div>
                <div class="form-group">

                    {!! Form::text('shipFromCompany', $shipFromInformation['Company'], ['id'=>'shipFromCompany','class' => 'form-control','placeholder'=>'FullName' ]) !!}
                </div>
                <div class="form-group">

                    {!! Form::text('shipFromAddress', $shipFromInformation['Address'], ['id'=>'shipFromAddress','class' => 'form-control','placeholder'=>'FullName' ]) !!}
                </div>
                <div class="form-group">

                    {!! Form::text('shipFromCompany', $shipFromInformation['Company'], ['id'=>'shipFromCompany','class' => 'form-control','placeholder'=>'FullName' ]) !!}
                </div>
                <div class="form-group">

                    {!! Form::text('shipFromCity', $shipFromInformation['City'], ['id'=>'shipFromCity','class' => 'form-control','placeholder'=>'FullName' ]) !!}
                </div>
                <div class="form-group">

                    {!! Form::text('shipFromState', $shipFromInformation['State'], ['id'=>'shipFromState','class' => 'form-control','placeholder'=>'FullName' ]) !!}
                </div>
                <div class="form-group">

                    {!! Form::text('shipFromZip', $shipFromInformation['Zip'], ['id'=>'shipFromZip','class' => 'form-control','placeholder'=>'FullName' ]) !!}
                </div>
                <div class="form-group">

                    {!! Form::text('shipFromCountry', $shipFromInformation['Country'], ['id'=>'shipFromCountry','class' => 'form-control' ]) !!}
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form role="form">
                <div class="form-group">

                    {!! Form::text('shipToName', null, ['id'=>'shipToName','class' => 'form-control', 'placeholder'=>'Full Name']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('shipToCompany', null, ['id'=>'shipToCompany','class' => 'form-control', 'placeholder'=>'Company' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('shipToAddress', null, ['id'=>'shipToAddress','class' => 'form-control', 'placeholder'=>'Address' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('shipToCompany', null, ['id'=>'shipToCompany','class' => 'form-control', 'placeholder'=>'Address1' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('shipToCity', null, ['id'=>'shipToCity','class' => 'form-control', 'placeholder'=>'City' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('shipToState', null, ['id'=>'shipToState','class' => 'form-control', 'placeholder'=>'State' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('shipToZip', null, ['id'=>'shipToZip','class' => 'form-control', 'placeholder'=>'Zip' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('shipToCountry', null, ['id'=>'shipToCountry','class' => 'form-control', 'placeholder'=>'Country' ]) !!}
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Product
                    </th>
                    <th>
                        Payment Taken
                    </th>
                    <th>
                        Status
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        1
                    </td>
                    <td>
                        TB - Monthly
                    </td>
                    <td>
                        01/04/2012
                    </td>
                    <td>
                        Default
                    </td>
                </tr>
                <tr class="active">
                    <td>
                        1
                    </td>
                    <td>
                        TB - Monthly
                    </td>
                    <td>
                        01/04/2012
                    </td>
                    <td>
                        Approved
                    </td>
                </tr>
                <tr class="success">
                    <td>
                        2
                    </td>
                    <td>
                        TB - Monthly
                    </td>
                    <td>
                        02/04/2012
                    </td>
                    <td>
                        Declined
                    </td>
                </tr>
                <tr class="warning">
                    <td>
                        3
                    </td>
                    <td>
                        TB - Monthly
                    </td>
                    <td>
                        03/04/2012
                    </td>
                    <td>
                        Pending
                    </td>
                </tr>
                <tr class="danger">
                    <td>
                        4
                    </td>
                    <td>
                        TB - Monthly
                    </td>
                    <td>
                        04/04/2012
                    </td>
                    <td>
                        Call in to confirm
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
        </div>
    </div>
</div>

