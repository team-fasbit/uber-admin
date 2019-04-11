<?php
/**
* Created by PhpStorm.
* User: aravinth
* Date: 5/7/15
* Time: 11:58 AM
*/
?>
@if(Session::has('flash_errors'))
    @if(is_array(Session::get('flash_errors')))
        <div class="alert alert-danger" style="margin: 0;">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                @foreach(Session::get('flash_errors') as $errors)
                    @if(is_array($errors))
                        @foreach($errors as $error)
                            <li> <script type="text/javascript">
                                //Toast({text: "<?php echo $error; ?>"}).show();
                                </script>
                            </li>
                        @endforeach
                    @else
                        <li> <script type="text/javascript">
                            //Toast({text: "<?php echo $errors; ?>"}).show();
                            </script> 
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

    @else
        <script type="text/javascript">
            //Toast({text: "<?php echo Session::get('flash_errors'); ?>"}).show();
        </script>
    @endif
@endif

@if(Session::has('flash_error'))
    <script type="text/javascript">
        //Toast({text: "<?php echo Session::get('flash_error'); ?>"}).show();
    </script>
@endif


@if(Session::has('flash_success'))
    <script type="text/javascript">
    //Toast({text: "<?php echo Session::get('flash_success'); ?>"}).show();
    </script>
@endif
