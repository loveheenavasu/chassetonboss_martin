<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Gmail Connections') }}
        </x-slot>

        <x-slot name="description">
            Contains Account credentials.
        </x-slot>

        <div>

        <x-slot name="content">
            <div class="space-y-6 position-relative">
                <div class="datatable-filter">
                    <div class="gmails-filter">
                        <label class="text-lg font-medium text-gray-900">Saved Filter:</label>
                        <select class="form-select" name="saved_filter" id="saved_filter">
                            <option value="">Select filter</option>
                        </select>

                        <label class="text-lg font-medium text-gray-900">Filter:</label>
                        <select class="form-select" name="group_name" id="group_name">
                        </select>

                        <select class="form-select" name="token_check" id="token_check">
                          <option value="">Select token status</option>
                          <option value="valid">Authenticated</option>
                          <option value="notauth">Not authenticated</option>
                          <option value="suspended">Suspended</option>
                        </select>
                        <button type="button" name="assigngroup" id="save-filter" class="btn btn-info" data-toggle="modal" data-target="#gmailFilterModal">
                            Save Filter
                        </button>
                    </div>
                    <button type="button d-none" name="assigngroup" id="assigngroup" class="btn btn-primary" data-toggle="modal" data-target="#groupsModal">
                        Groups
                    </button>
                    <button type="button" class="ml-2 btn btn-danger d-none" id="deleteSelected">Delete</button>

                </div>
                <div class="items-center">
                    <div class="container-fluid">
                        <table class="table table-bordered gmail-con-data" id="page_table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="select_all" id="checkdata"></th>
                                    <th>ID</th>
                                    <th>GMAIL CONNECTIONS</th>
                                    <th>GROUP NAME</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <div>
                <div class="modal fade" id="proxyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Proxy List</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                           <div class="modal-body">
                           <x-jet-label for="selectedproxy" value="{{ __('Select a Group') }}" />
                                <x-select name="selectedproxy" id="selectedproxy" class="mt-1">
                                    <option value=""></option>
                                    @foreach($this->Proxy as $proxy)
                                    <option value="{{ $proxy->id }}">
                                        {{ $proxy->name }}
                                    </option>

                                    @endforeach
                                </x-select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" name="proxybtn" id="proxybtn" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="groupsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Groups list</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                            <div class="modal-body">
                                <x-jet-label for="selectedgroup" value="{{ __('Select a Group') }}" />
                                    <x-select name="selectedgroup" id="selectedgroup" class="mt-1" >
                                    <option value=""></option>
                                    @foreach($this->Groups as $groups)
                                    <option value="{{ $groups->id }}">
                                        {{ $groups->name }}
                                    </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" name="groupbtn" id="groupbtn"class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="gmailFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Filter Name</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                            <div class="modal-body">
                                <x-jet-label for="selectedgroup" value="{{ __('Enter filter name') }}" />
                                <x-jet-input id="filtername" type="text" class="mt-1 block w-full" autofocus />
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" name="filterbtnsave" id="filterbtnsave"class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="allselected" id="allselected">
        <input type="hidden" name="singleselected" id="singleselected">
        </div>
    </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingGmailConnectionDeletion">
        <x-slot name="title">
            {{ __('Delete connections') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this account?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingGmailConnectionDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteGmailConnection" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

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
    #assigngroup{
        float: right;
    }
    #page_table td .btn {
    margin-right: 10px !important;
    }
    #page_table td .btn:last-child {
    margin-right: 0 !important;
    }
    button.open.btn.btn-primary.btn-sm.btn-primary.disabled {
        cursor: not-allowed;
    }

    .datatable-filter #assigngroup {
        float: none;
    }
    .datatable-filter .gmails-filter {
        margin-right: 10px;
    }
    .datatable-filter {
        display: flex;
        align-items: end;
        position: absolute;
        right: 15px;
        top: -10px;
        z-index: 1;
    }
    table.gmail-con-data {
        margin-top: 40px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){

        $.ajax({
            url:'/getallgorups',
            type:'get',
            data:'',
            success:function(response){
               $('#group_name').html(response);
            }
        });

        $.ajax({
            url:'/listsavedfilter',
            type:'get',
            data:'',
            success:function(response){
               $('#saved_filter').html(response);
            }
        });
        var selectedvalue = [];
        var allselected ;
        var allselectedval = [];
        var selectvalue =[];

        var table = $('#page_table').DataTable({
            'columnDefs': [{
            'targets': 0,
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta){
                return '<input type="checkbox" name="id[]" id="gmailcheck" value="' + $('<div/>').text(data).html() + '">';
            }
        }],
        'order': [[1, 'desc']],
            processing: true,
            serverSide: true,
            searching: false,
            paging: true,
            aaSorting: [[1, 'desc']],
            DeferRender: true,
            ordering: true,
            iDisplayLength: 25,
            lengthMenu: [10, 25, 50,100,250,500,'All'],
            ajax: {
                url: "/gmailconnection",
                data: function (d) {
                    d.group_name = $('#group_name').val(),
                    d.token_check = $('#token_check').val()
                }
            },
            columns: [
                {data: 'select', select: 'select'},
                {data: 'id', name: 'id'},
                {data: 'email_id', name: 'email_id'},
                {data: 'group_name', name: 'group_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#page_table').on('click', '.btn-delete[data-remote]', function (e){
            var id = $(this).attr('data-remote');
            e.preventDefault();
            swal({
              title: `Are you sure you want to delete this gmailconnection?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url:'/deletegmailconnection',
                type:'get',
                data:{id:id},
                success:function(res){
                    alert("Gmail Connection Deleted Successfully!")
                    setTimeout(function(){
                        window.location.reload();
                    },100);
                }
              });
            }
          });
        });

        $('#group_name,#token_check').change(function(){
            table.draw();
        });

        $("#saved_filter").on('change', function(){
            var group_name = $('option:selected', this).attr('data-group');
            var token_check = $('option:selected', this).attr('data-token-check');
            $('#group_name').val(group_name);
            $('#token_check').val(token_check);
            table.draw();
        });

    $('#checkdata').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
            if (this.checked) {
                $('input[type="checkbox"]:checked:not(:first)').each(function () {
                    var id= table.row($(this).closest('tr')).data().id;
                    selectvalue.push(id);

                });
                $("#deleteSelected").removeClass('d-none');
            }
            else{
                selectvalue=[];
                $("#deleteSelected").addClass('d-none');
            }
        $("#allselected").val(selectvalue);
    });


    $('#deleteSelected').on('click',function(){
        var getselectedvalue=[];
        var getselectedvalue = $('#allselected').val();
        if(getselectedvalue==""){
            alert("Please select a email");
        }
        else{
            var getGroupId = $('#selectedgroup').val();
            $.ajax({
                url: "/deleteSelectedGmailconnection",
                type:"get",
                data: {getselectedvalue:getselectedvalue},
                success: function(result){
                    window.location.reload();
                }
            });
        }
    });

    $(' #page_table tbody').on('change', 'input[type="checkbox"]', function(){
        if (!this.checked) {
            $('#checkdata').prop("checked", false);
        } else {
            $("#deleteSelected").removeClass('d-none');
            if ($('input[type="checkbox"]:checked').length == $('#page_table tbody tr').length) {
                $('#checkdata').prop("checked", true);
            }
        }
        selectvalue = $(':checkbox[type=checkbox]:checked').map(function() {
            return table.row($(this).closest('tr')).data().id;
        }).get();
        $("#allselected").val(selectvalue);

    });

    $('#groupbtn').on('click',function(){
        var getselectedvalue=[];
        var getselectedvalue = $('#allselected').val();
        if(getselectedvalue==""){
            alert("Please select a email");
        }
        else{
            var getGroupId = $('#selectedgroup').val();
            $.ajax({
                url: "/multiplecheck",
                type:"get",
                data: {getselectedvalue:getselectedvalue,getGroupId:getGroupId},
                success: function(result){
                    window.location.reload();
                }
            });
        }
    });

    $('#proxybtn').on('click',function(){
        var getselectedvalue=[];
        var getselectedvalue = $('#allselected').val();
        if(getselectedvalue==""){
            alert("Please select a email");
        }
        else{
            var getProxyId = $('#selectedproxy').val();
            $.ajax({
                url: "/multiProxycheck",
                type:"get",
                data: {getselectedvalue:getselectedvalue,getProxyId:getProxyId},
                success: function(result){
                    window.location.reload();
                }
            });
        }
    });

    $("#filterbtnsave").on('click', function(){
        var group_name = $('#group_name').val();
        var token_check = $('#token_check').val();
        var filter_name = $("#filtername").val();
        if(group_name == "" && token_check == ""){
            alert("Please select any filter");
        }else if(filter_name == ""){
            alert("Please enter the filter name");
        }else{
            $.ajax({
                url: "/savegmailfilter",
                type:"get",
                data: {filter_name:filter_name,group_name:group_name,token_check:token_check},
                success: function(result){
                    if(result == true){
                        swal({
                          title: `Filter Added successfully!`,
                          icon: "success",
                          showConfirmButton: false,
                        });
                        $("#gmailFilterModal").modal('hide');
                    }else{
                        swal({
                          title: `Something went wrong!`,
                          icon: "error",
                          showConfirmButton: false,
                        });
                        $("#gmailFilterModal").modal('hide');
                    }
                }
            });
        } 

    })

});

function testConnection(element) {
    var id= element;
    $.ajax({
        url:'/testconnection',
        type:'get',
        data:{id:id},
        success:function(res){
            window.location.href =res;
        }
    });
}

function refreshToken(element) {
    var id= element;
    $.ajax({
        url:'/refreshtoken',
        type:'get',
        data:{id:id},
        success:function(res){
            swal({
              title: `Token refresh successfully!`,
              icon: "success",
              showConfirmButton: false,
            });
            $('#page_table').DataTable().ajax.reload();
        }
    });
}
</script>
