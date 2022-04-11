@foreach ($group as $g)
        <li><button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#yourModal{{$g->id}}"></li>
    @endforeach

@foreach ($group as $g)    
    <div class="modal fade" id="yourModal{{$g->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
@endforeach