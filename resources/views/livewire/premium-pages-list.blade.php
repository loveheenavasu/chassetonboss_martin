<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Premium Pages') }}
        </x-slot>

        <x-slot name="content">
            <div class="container">
                <table class="table table-bordered" id="page_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>LINKS</th>
                            <th>CREATED AT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </x-slot>
    </x-action-section>
</div>
<style type="text/css">
    body .shadow {
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%) !important;
        overflow: visible;
    }
    a:hover{
        text-decoration: none !important;
    }
    table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after,table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before{
        display: none;
    }
    .dataTable{
        width: auto !important;
    }
    .dataTable td a {
     margin-right: 5px !important;
    }
    .dataTable td:last-child input {
        width: 10px;
    }
    .dataTable td:last-child  {
        white-space: nowrap;
    }
    #page_table {
        width: 100% !important;
    }
    
</style>

<script type="text/javascript">
    $(document).ready(function(){
        var table = $('#page_table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            aaSorting: [[0, 'desc']],
            DeferRender: true,
            ordering: true,
            iDisplayLength: 25,
            lengthMenu: [10, 25, 50,100,250,500,'All'],
            ajax: "/premiumpages",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'full_url', name: 'full_url'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#page_table').on('click', '.btn-delete[data-remote]', function (e){ 
            var id = $(this).attr('data-remote');
            e.preventDefault();
            swal({
              title: `Are you sure you want to delete this rule?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url:'/deletepremiumpage',
                type:'get',
                data:{id:id},
                success:function(res){
                    alert("Rule Deleted Successfully!")
                    setTimeout(function(){
                        window.location.reload();
                    },100); 
                }
              });
            }
          });
        });
    });
    function copyToClipboard(element) {
        
        //var copyText = $().val();
        var copyText = document.getElementById("copy_to_clipboard_"+element);
          /* Select the text field */
          copyText.select();
          copyText.setSelectionRange(0, 99999); /* For mobile devices */

          /* Copy the text inside the text field */
          document.execCommand("copy");

          /* Alert the copied text */
          swal({
              title: `Page link copied to clipboard`,
              icon: "success",
              showConfirmButton: false,
          });
    }
</script>