<meta name="csrf-token" content="{{ csrf_token() }}">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
                <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
                <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
                <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
                <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<div>
    <x-jet-action-section>
        <x-slot name="title">
            Actions history
        </x-slot>

        <x-slot name="description">
            History of performed exports.
        </x-slot>

        <x-slot name="content">
            {{'List of Synced Emails'}}
           <table class="table table-bordered" id="synced_emails_table">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>EMAIL</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            
       
            <table id="emails-list" class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
            $emails = $this->rule->SyncedEmail($this->rule->id);
            @endphp
           <input type="hidden" name="rule_id" value="{{$this->rule->id}}"id="rule_id">
            

          
        </x-slot>
    </x-jet-action-section>
</div>

<script type="text/javascript">
                $(document).ready(function(){
                    //alert();
                    var table = $('#synced_emails_table').DataTable({
                        processing: true,
                        serverside: true,
                        searching: true,
                        paging: true,
                        aaSorting: [[0, 'desc']],
                        ordering: true,
                        iDisplayLength: 100,
                        ajax: {
                                "url": "/emailSyncedList",
                                "data": function ( d ) {
                                     d.rule_id = $('#rule_id').val();
                                }
                            },
                       // ajax: "/emailSyncedList",
                        columns: [
                            // {data: 'id', name: 'id'},
                            {data: 'email', name: 'email'},
                        ]
                    });
                });

            </script>
