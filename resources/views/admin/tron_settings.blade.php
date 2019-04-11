@extends('layouts.admin')
@section('title', 'Tron Wallet Settings')
@section('content-header', 'Tron Wallet Settings')
@section('breadcrumb')
<li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
<li class="active"><i class="fa fa-gears"></i> Tron Wallet</li>
@endsection
@section('content')
@include('notification.notify')
<section class="content">
    <div class="row">

        <div class="site_setting_outer">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tron Api URL</h3>
                </div>
                <div class="box-body">
                    
                    <form action="" id="api_url_save_form">
                    <input type="hidden" value="{{csrf_token()}}" name="_token">
                    <div class="form-group">
                        <label for="email">Api URL</label>
                        <input type="text" class="form-control" id="tron_api_url" name="tron_api_url" value="{{Setting::get('tron_api_url', '') }}" placeholder="Ex: http://463 .101.106.16:3000">
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                    </form>

                </div>
            </div>
        </div>
        
        <div class="site_setting_outer">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">New Address Generation</h3>
                </div>
                <div class="box-body">
                    
                    <form action="" id="add_save_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}"> 
                    <div class="form-group">
                        <label for="email">Address(Base58)</label>
                        <input type="text" class="form-control" id="address_base58" name="address_base58">
                    </div>
                    <div class="form-group">
                        <label for="email">Address(Hex)</label>
                        <input type="text" class="form-control" id="address_hex" name="address_hex">
                    </div>
                    <div class="form-group">
                        <label for="email">Public Key</label>
                        <input type="text" class="form-control" id="public_key" name="public_key">
                    </div>
                    <div class="form-group">
                        <label for="email">Private Key</label>
                        <input type="text" class="form-control" id="private_key" name="private_key">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Save Address</button>
                    <button type="button" id="add_generate_button" class="btn btn-info">Generate Address</button>
                    </form>


                </div>
            </div>
        </div>
        <div class="site_setting_outer">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Your Tron Addresses</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped ">
                        <thead>
                            <tr>
                                <th>Default</th>
                                <th>Address</th>
                                <!-- <th>Address(Hex)</th> -->
                                <th>Private Key</th>
                               <!--  <th>Public Key</th> -->
                                <th>Generated On</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wallets as $wallet)
                            <tr>
                                <td>
                                    @if($wallet->is_default)
                                    <input type="checkbox" checked disabled>
                                    @else
                                     <a href="javascript:void(0)" data-wallet-id="{{$wallet->id}}" class="make_default_check">Make Default</a>
                                    @endif
                                </td>
                                <td>{{$wallet->address_base58}}</td>
                                <!-- <td>{{$wallet->address_hex}}</td> -->
                                <td>{{$wallet->private_key}}</td>
                                <!-- <td>{{$wallet->public_key}}</td> -->
                                <td>{{$wallet->generatedOn('Asia/Kolkata')}}</td>
                                <td>
                                    <a href="javascript:void(0)" data-wallet-id="{{$wallet->id}}" data-address-base58="{{$wallet->address_base58}}" class="check_balance">Check</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('bottom-scripts')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    
    var urlsaveapi = '{{route('admin.save_tron_api_url')}}'
    var createaddressapi = "{{route('admin.create_tron_address')}}"
    var saveaddressapi = "{{route('admin.save_tron_address')}}"
    var makedefaultapi = "{{route('admin.make_default')}}"
    var checkbalanceapi = "{{route('admin.check_tron_balance')}}?addressbase58="
    var csrf_token = '{{csrf_token()}}'

    $(document).ready(function(){


        $(".check_balance").on('click', function(){

            var addressbase58 = $(this).data('address-base58')
            console.log(addressbase58)

            var url = checkbalanceapi+addressbase58
            $.get(url, function(response){
                console.log(response)

                if(response.success) {
                    balance = `Address : ${addressbase58}, <br> Balance : ${response.balance} TRX`
                    toastr.options.closeButton = true;
                    toastr.success(balance, 'Balance', {timeOut: 5000})
                }

            })

        })



        $(".make_default_check").on('click', function(){

            var walletid = $(this).data('wallet-id')
            console.log(walletid)

            $.post(makedefaultapi, {_token:csrf_token, wallet_id : walletid}, function(response){

                toastr.success('Address is default now', 'Success')
                setTimeout(() => {
                    window.location.reload()
                }, 1000);

            })
            .fail(function(response) {
                
                toastr.error('Failed to make this address default', 'Error')
                
            });  


        })



        $("#add_save_form").on('submit', function(event){
            event.preventDefault();
            var data = $(this).serializeArray();

            console.log(data)


            $.post(saveaddressapi, data, function(response){
                console.log(response)
                if(response.success) {
                    toastr.success('Address saved successfully', 'Success')
                    setTimeout(() => {
                        window.location.reload()
                    }, 1000);
                } else {
                    toastr.error('Failed to save address', 'Error')
                }

            })
            .fail(function(response) {
                
                toastr.error('Failed to save address', 'Error')
                
            });  

        })







        $("#add_generate_button").on('click', function(){

            $.get(createaddressapi, function(response){

                console.log(response)

                if(!response.address.base58 || !response.address.hex || !response.publicKey || !response.privateKey) {
                    toastr.info('Address generate failed', 'Failed')
                    return;
                }

                $("#address_base58").val(response.address.base58)
                $("#address_hex").val(response.address.hex)
                $("#public_key").val(response.publicKey)
                $("#private_key").val(response.privateKey)


                toastr.info('Address generated and form filled with details. You can save the address now', 'Generated')

            })
            .fail(function(response) {
                
                toastr.info('Address generate failed', 'Failed')
                
            });  

        })



        $("#api_url_save_form").on('submit', function(event){
            event.preventDefault();
            var data = $(this).serializeArray();

            console.log(data)


            $.post(urlsaveapi, data, function(response){

                if(response.success) {
                    toastr.success('Api URL saved successfully', 'Success')
                   /*  setTimeout(() => {
                        window.location.reload()
                    }, 1000); */
                } else {
                    toastr.error('Api URL saving failed', 'Error')
                }

            })

        })
        

    });
</script>
@endsection