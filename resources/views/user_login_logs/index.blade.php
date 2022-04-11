<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Login Details and List upload Logs') }}
            </h2>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <!DOCTYPE html>
            <html>
            <head>
                <title>Login Details and List upload Logs</title>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
                <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
                <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
                <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
                <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
            </head>
            <body>
                
            <div class="container">
                <h5>Login Details and List upload Logs</h5>
                
                <table class="table table-bordered" id="user_login_details_table">
                    <thead>
                        <tr>
                            <th>EMAIL</th>
                            <th>TIMEZONE</th>
                            <th>IP ADDRESS</th>
                            <th>TYPE</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
               
            </body>
            <style type="text/css">
                table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after,table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before{
                    display: none;
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function(){
                    var table = $('#user_login_details_table').DataTable({
                        processing: true,
                        serverside: true,
                        searching: true,
                        paging: true,
                        aaSorting: [[0, 'desc']],
                        ordering: true,
                        iDisplayLength: 100,
                        ajax: "/logindetails",
                        columns: [
                            {data: 'email', name: 'email'},
                            {data: 'timezone', name: 'timezone'},
                            {data: 'ip_address', name: 'ip_address'},
                            {data: 'type', name: 'type'},
                            
                        ]
                    });

                    // $('#delete_logs').click(function(){
                    //     var date = $('#dlt_date').val();
                    //      if(date == ''){
                    //         alert('Please select date first')
                    //         return
                    //      }else{
                    //     $.ajax({
                    //          url:'/DeleteEventLogs_valid_email',
                    //          type:'get',
                    //          data:{date:date},
                    //          success:function(res){
                    //               if(res == 1){
                    //                 alert('Logs Deleted Successfully')
                    //                 window.location.reload();
                    //               }else{
                    //                 alert('Something Went Wrong!')
                    //               }
                    //          }

                    //     })
                    //   }
                    // })
                   // $('#delete_logs_cron').click(function(){
                   //  let houur = $('#cron_logs_delete').val();
                   //    if(houur == ''){
                   //       alert('please select hours');
                   //       return
                   //    }else{
                   //      $.ajax({
                   //          url:'/SetLogsDeleteCron',
                   //          type:'get',
                   //          data:{hours:houur},
                   //          success:function(res){
                               
                   //                if(res == 1){
                   //                  alert('Cron set Successfully')
                   //                  window.location.reload();
                   //                }else{
                   //                  alert('Something Went Wrong!')
                   //                }
                   //           }
                   //      })
                   //    }
                   // })

                   // $('#maulay-delete-logs').click(function(){
                   //    $.ajax({
                   //      url : 'Delete-logs-manaully',
                   //      type:'get',
                   //      success:function(res){
                   //         if(res == 1){
                   //                  alert('Logs file removed Successfully')
                   //                  window.location.reload();
                   //                }else{
                   //                  alert('Something Went Wrong!')
                   //                }
                   //      }
                   //    })
                   // })
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
