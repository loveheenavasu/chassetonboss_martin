<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Content Templates') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if($this->contents->isNotEmpty())
                    @foreach($this->contents as $content)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('eventtemplate.show', ['eventtemplate' => $content->id]) }}">
                                    {{ $content->name }}
                                </a>
                            </div>

                            <div class="flex items-center">
                                <button type="button" class="cursor-pointer ml-6 text-sm text-blue-500 focus:outline-none testTemplateModal" name="testTemplateModal" data-toggle="modal" data-target="#testTemplateModal" data-id="{{ $content->id }}">
                                    {{ __('Send Test Template') }}
                                </button>

                                <button type="button" class="cursor-pointer ml-6 text-sm text-blue-500 focus:outline-none contentShow" name="contentShow" data-toggle="modal" data-target="#contentModal" data-id="{{ $content->id }}">
                                    {{ __('Preview Content') }}
                                </button>

                                <button class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none" wire:click="cloneContent({{ $content->id }})">
                                    Clone
                                </button>


                                <a href="{{ route('eventtemplate.show', ['eventtemplate' => $content->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Edit') }}
                                </a>

                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmContentDeletion({{ $content->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                    <div class="modal fade" id="contentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Content</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" id="modalContent">
                          </div>
                          
                        </div>
                      </div>
                    </div>
                    <input type="hidden" name="contentpre" id="contentpre" value="{{ $content->id }}">

                    <div class="modal fade" id="testTemplateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Send Test Template</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" id="modalContent">
                            <form>
                              <div class="form-group">
                                <label for="email" class="col-form-label">Email:</label>
                                <input type="email" class="form-control email" id="email">
                                <input type="hidden" name="testtemplate" class="testtemplate">
                              </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary sendTemplate">Send</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                @endforeach

                @if($this->contents->hasPages())
                        <div contents="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->contents->fragment('')->links() }}
                        </div>
                @endif
                @else
                    <div>{{ __('No contents yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingContentDeletion">
        <x-slot name="title">
            {{ __('Delete Content Template') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this content template?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingContentDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteContent" wire:loading.attr="disabled">
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
    .sm\:pb-4{
        padding-bottom: 1rem !important;
    }
    .sm\:p-6{
        padding: 1.5rem !important;
    }
    .sm\:p-6{
        padding: 1.5rem !important;
    }
    .sm\:mx-0{
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
</style>
<script type="text/javascript">

    $(".contentShow").click(function(){
    var contentId = $(this).attr('data-id');
    $.ajax({
            url: "/contenttemplate",
            type:"get",
            data: {contentId:contentId}, 
            success: function(response){
                $("#exampleModalLabel").html(response.event_name);
                $("#modalContent").html(response.result);
                $('#contentModal').show();
            } 
        });
    }); 
    $(".testTemplateModal").click(function(){
        var contentId = $(this).attr('data-id');
        $('.testtemplate').val(contentId);
    });
    $(".sendTemplate").click(function(){
        var contentId = $('.testtemplate').val();
        var email = $('.email').val();
        if(email == ''){
            alert("please enter the email");
            return false;
        }else{
            $.ajax({
                url: "/sendtesttemplate",
                type:"get",
                data: {contentId:contentId,email:email}, 
                success: function(response){
                    $('.close').click();
                    swal({
                      title: response.message,
                      text: 'Please check you email',
                      icon: response.status,
                      showConfirmButton: false,
                   });
                } 
            });
        }
    });
</script>
