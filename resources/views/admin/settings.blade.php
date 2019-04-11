@extends('layouts.admin')

@section('title', tr('settings'))

@section('content-header', tr('settings'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-gears"></i> {{tr('settings')}}</li>
@endsection

@section('content')

@include('notification.notify')

  <section class="content">
                

    <div class="row">
        <div class="site_setting_outer">

            <div class="box box-info">

                <div class="box-header with-border">
                    <h3 class="box-title">{{tr('site_settings')}}</h3>
                </div>
                    <div class="box-body">

                        <div class="col-md-6">
                          <form action="{{route('admin.save.settings')}}" method="POST" enctype="multipart/form-data" role="form">

                            <div class="form-group">
                                <label>{{ tr('site_logo') }}</label>
                                   @if(Setting::get('site_logo')!='')
                                   <img class="setting_logo"  src="{{Setting::get('site_logo')}}">
                                   @endif
                                    <input name="picture" type="file">
                                    <p class="help-block">{{ tr('upload_message') }}</p>
                            </div>

                            <div class="form-group">
                                <label>{{ tr('site_icon') }}</label>
                                   @if(Setting::get('site_icon')!='')
                                  <img class="setting_logo"  src="{{Setting::get('site_icon')}}">
                                  @endif
                                    <input name="site_icon" type="file">
                                    <p class="help-block">{{ tr('upload_message') }}</p>
                            </div>

                            <div class="form-group">
                                <label>{{ tr('email_logo') }}</label>
                                 @if(Setting::get('mail_logo')!='')
                                <img class="setting_logo"  src="{{Setting::get('mail_logo')}}">
                                @endif
                                  <input name="email_logo" type="file">
                                  <p class="help-block">{{ tr('upload_message') }}</p>
                            </div>
                          
                  
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ tr('site_name') }}</label>
                                 <input type="text" name="site_name" value="{{ Setting::get('site_name', '')  }}" required class="form-control">
                            </div>

                              <div class="form-group">
                                <label>{{ tr('provider_time') }}</label>
                                 <input type="number" name="provider_select_timeout" value="{{ Setting::get('provider_select_timeout', '')  }}" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Provider Commission (%)</label>
                                 <input type="number" name="provider_commission" value="{{ Setting::get('provider_commission', '')  }}" required class="form-control">
                            </div>

                             <div class="form-group">
                                <label>{{ tr('search_radius') }}</label>
                                <input type="number" name="search_radius" value="{{ Setting::get('search_radius', '')  }}" required class="form-control">
                            </div>

                            <!-- <div class="form-group">
                                <label>{{ tr('base_price') }}</label>
                                 <input type="number" name="base_price" value="{{ Setting::get('base_price', '')  }}" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>{{ tr('price_per_min') }}</label>
                               <input type="number" name="price_per_minute" value="{{ Setting::get('price_per_minute', '')  }}" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>{{ tr('price_per_unit_distance') }}</label>
                                <input type="number" name="price_per_unit_distance" value="{{ Setting::get('price_per_unit_distance', '')  }}" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>{{ tr('default_distance_unit') }}</label>
                                 <select name="default_distance_unit" value="" required class="form-control">
                                 <option value="">{{ tr('select') }}</option>
                                    @if(Setting::get('default_distance_unit')!='')
                                      @if(Setting::get('default_distance_unit') == 'miles')
                                        <option value="miles" selected="true">miles</option>
                                        <option value="kms" >kms</option>
                                      @else
                                        <option value="miles" >miles</option>
                                        <option value="kms" selected="true">kms</option>
                                      @endif
                                    @else
                                    <option value="miles">miles</option>
                                    <option value="kms">kms</option>
                                    @endif
                                    
                                  </select>
                                 <select name="default_distance_unit" value="" required class="form-control">
                                    <option value="">{{ tr('select') }}</option>
                                    @if(Setting::get('default_distance_unit')!='')
                                    <option value="miles">{{ Setting::get('default_distance_unit') }}</option>
                                    @else
                                    
                                    <option value="miles">miles</option>
                                    <option value="kms">kms</option>
                                    @endif

                                  </select>
                            </div>

                            <div class="form-group">
                                <label>{{ tr('tax_price') }}</label>
                                <input type="number" name="tax_price" value="{{ Setting::get('tax_price', '')  }}" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>{{ tr('price_per_service') }}</label>
                                 <select name="price_per_service" value="" required class="form-control">
                                 <option value="">{{ tr('select') }}</option>
                                    @if(Setting::get('price_per_service')!='')
                                      @if(Setting::get('price_per_service') == 1)
                                        <option value="1" selected="true">Yes</option>
                                        <option value="0" >No</option>
                                      @else
                                        <option value="1" >Yes</option>
                                        <option value="0" selected="true">No</option>
                                      @endif
                                    @else
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                    @endif
                                    
                                  </select>
                            </div> -->

                            <div class="form-group">
                                <label>
                                  {{ tr('currency') }} ( <strong>{{ Setting::get('currency', '')  }} </strong>)
                                </label>
                                 <select name="currency" value="" required class="form-control">
                                    @if(Setting::get('currency')!='')
                                    <option value="{{ $symbol }}">{{ $currency }}</option>
                                    @else
                                    <option value="">{{ tr('select') }}</option>
                                    @endif
                                    <option value="$">US Dollar (USD)</option>
                                    <option value="₹"> Indian Rupee (INR)</option>
                                    <option value="د.ك">Kuwaiti Dinar (KWD)</option>
                                    <option value="د.ب">Bahraini Dinar (BHD)</option>
                                    <option value="﷼">Omani Rial (OMR)</option>
                                    <option value="£">British Pound (GBP)</option>
                                    <option value="€">Euro (EUR)</option>
                                    <option value="CHF">Swiss Franc (CHF)</option>
                                    <option value="ل.د">Libyan Dinar (LYD)</option>
                                    <option value="B$">Bruneian Dollar (BND)</option>
                                    <option value="S$">Singapore Dollar (SGD)</option>
                                    <option value="AU$"> Australian Dollar (AUD)</option>
                                    <option value="TRX">Tron Coin (TRX)</option>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label>GCM Key</label>
                                <input type="text" name="gcm_key" value="{{ Setting::get('gcm_key', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Wallet Bay Key</label>
                                <input type="text" name="wallet_bay_key" value="{{ Setting::get('wallet_bay_key', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Wallet URL</label>
                                <input type="text" name="wallet_url" value="{{ Setting::get('wallet_url', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Cancellation Charge</label>
                                <input type="text" name="cancellation_fine" value="{{ Setting::get('cancellation_fine', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Referrer Bonus</label>
                                <input type="text" name="referrer_bonus" value="{{ Setting::get('referrer_bonus', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Referee Bonus</label>
                                <input type="text" name="referee_bonus" value="{{ Setting::get('referee_bonus', '')  }}"  class="form-control">
                            </div>


                            <div class="form-group">
                                <label>
                                    <strong> FORCE APP UPGRADE ON/OFF </strong>
                                </label>
                                 <select name="force_upgrade" value="" required class="form-control">
                    
                                    <option value="1" <?php if(Setting::get('force_upgrade')==1) echo 'selected="selected"'; ?> >ON</option>
                                    <option value="0" <?php if(Setting::get('force_upgrade')==0) echo 'selected="selected"'; ?> > OFF</option>
                                  
                                    </select>
                            </div>

                            <div class="form-group">
                                <label>Android User App Version Latest </label>
                                <input type="text" name="android_user_version" value="{{ Setting::get('android_user_version', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Android Driver App Version Latest </label>
                                <input type="text" name="android_driver_version" value="{{ Setting::get('android_driver_version', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>iOS User App Version Latest </label>
                                <input type="text" name="ios_user_version" value="{{ Setting::get('ios_user_version', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>iOS Driver App Version Latest </label>
                                <input type="text" name="ios_driver_version" value="{{ Setting::get('ios_driver_version', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong> Accept Debt as cash payment added with next trip </strong>
                                </label>
                                 <select name="accept_debt_cash" value="" required class="form-control">
                    
                                    <option value="1" <?php if(Setting::get('accept_debt_cash')==1) echo 'selected="selected"'; ?> >ON</option>
                                    <option value="0" <?php if(Setting::get('accept_debt_cash')==0) echo 'selected="selected"'; ?> > OFF</option>
                                  
                                    </select>
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong> Surge ON/OFF </strong>
                                </label>
                                 <select name="surge_status" value="" required class="form-control">
                    
                                    <option value="1" <?php if(Setting::get('surge_status')==1) echo 'selected="selected"'; ?> >ON</option>
                                    <option value="0" <?php if(Setting::get('surge_status')==0) echo 'selected="selected"'; ?> > OFF</option>
                                  
                                    </select>
                            </div>

                            


                            <div class="form-group">
                                <label>Surge charge after 60% drivers busy </label>
                                <input type="text" name="surge_a" value="{{ Setting::get('surge_a', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Surge charge after 65% drivers busy </label>
                                <input type="text" name="surge_b" value="{{ Setting::get('surge_b', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Surge charge after 70% drivers busy </label>
                                <input type="text" name="surge_c" value="{{ Setting::get('surge_c', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Surge charge after 75% drivers busy </label>
                                <input type="text" name="surge_d" value="{{ Setting::get('surge_d', '')  }}"  class="form-control">
                            </div>


                            <div class="form-group">
                                <label>Surge charge after 80% drivers busy </label>
                                <input type="text" name="surge_e" value="{{ Setting::get('surge_e', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Surge charge after 85% drivers busy </label>
                                <input type="text" name="surge_f" value="{{ Setting::get('surge_f', '')  }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Surge charge after 90% drivers busy</label>
                                <input type="text" name="surge_g" value="{{ Setting::get('surge_g', '')  }}"  class="form-control">
                            </div>

                            <!-- <div class="form-group">
                                <label>Tron Wallet address hex</label>
                                <input type="text" name="tron_address_hex" value="{{ Setting::get('tron_address_hex', '')  }}"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tron Wallet address base58</label>
                                <input type="text" name="tron_address_base58" value="{{ Setting::get('tron_address_base58', '')  }}"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tron Wallet Private Key</label>
                                <input type="text" name="tron_private_key" value="{{ Setting::get('tron_private_key', '')  }}"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tron wallet Api Url</label>
                                <input type="text" name="tron_api_url" value="{{ Setting::get('tron_api_url', '')  }}"  class="form-control">
                            </div> -->

                         </div>

                  </div>
                  <!-- /.box-body -->

                  <div class="box-footer">
                      <button type="submit" class="btn btn-success pull-right">Submit</button>
                      <button type="reset" class="btn btn-danger">Cancel</button>
                      
                  </div>
                </form>

            </div>
        </div>

    </div>


            </section>  
@endsection
