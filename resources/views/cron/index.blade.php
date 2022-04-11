<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cron') }}
            </h2>
        </div>

    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cron Information</title>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
                <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
                <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
                <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
                <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
                <style type="text/css">
                    #cron_logs_delete {
                        height: 38px;
                        vertical-align: middle;
                        margin-top: 0px;
                        border: 2px solid #17A2B8;
                        margin-left: -20px;
                        border-radius: 4px;
                        padding: 7px;
                    }

                    .chron-notes {
                          background: #fff;
                          padding: 20px;
                          margin-top: 80px;
                     }

                     .delete-logs-info {
                          margin-top: 30px;
                      }

                      .delete-logs-info label {
                          margin-bottom: 15px;
                      }

                </style>
            </head>
            <body>
                
            <div class="container">
            
                <div class="delete-logs-info text-center">

                    <label for="cars"><strong>Delete Logs By Cron</strong></label><br>

                    <select name="cron_logs_delete" class="mb-3" id="cron_logs_delete" <?php if(isset($time)) echo $time ;?>>
                      <option value="">Select Hours</option>
                      <option <?php if($time == 24) echo "selected" ?> value="24">24 Hours</option>
                      <option <?php if($time == 48) echo "selected" ?> value="48">48 Hours</option>
                      <option <?php if( $time == 72) echo "selected" ?> value="72">72 Hours</option>
                    </select>
                    <input type="button" id="delete_logs_cron" value="Set Cron To Delete Logs" class="btn btn-info ml-2 mr-2 mb-3" >
                    <input type="button" id="reset_cron" value="Stop Cron" class="btn btn-danger mb-3">
            
                </div>
               <div class="notes chron-notes">
                <p><h2>Note : </h2> 
                  <strong>This Cron will remove all the logs under Invalid Email Logs, Email Logs & Error Logs automatically on the selected time.
                  If you want to stop the cron to delete the logs. Please click on “Stop Cron” button.</strong></p>
               </div>
               <!-- <div class="cron-ingo">
                 <p>Last cron was run on 12am Yesterday</p>
                 <p>Next cron will run on 12am Today</p>
               </div> -->
            </div>
               
            </body>
            <style type="text/css">
                table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after,table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before{
                    display: none;
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function(){
                 
                   $('#delete_logs_cron').click(function(){
                    let houur = $('#cron_logs_delete').val();
                      if(houur == ''){
                         alert('please select hours');
                         return
                      }else{
                        $.ajax({
                            url:'/SetLogsDeleteCron',
                            type:'get',
                            data:{hours:houur},
                            success:function(res){
                               
                                  if(res == 1){
                                    alert('Cron set Successfully')
                                    window.location.reload();
                                  }else{
                                    alert('Something Went Wrong!')
                                  }
                             }
                        })
                      }
                   })
                   $('#reset_cron').click(function(){
                    
                        $.ajax({
                            url:'/SetLogsResetCron',
                            type:'get',
                            data:{reset:'no'},
                            success:function(res){
                               
                                  if(res == 1){
                                    alert('Cron Reset Successfully')
                                    window.location.reload();
                                  }else{
                                    alert('Something Went Wrong!')
                                  }
                             }
                        })
                      
                   })

                });

            </script>
        </html>

        </div>
    </div>
</x-app-layout>
<style type="text/css">
    body .shadow {
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%) !important;
        overflow: visible;
    }
    a:hover{
        text-decoration: none !important;
    }
</style>
