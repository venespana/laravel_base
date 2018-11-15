@switch($type ?? 'default')
    @case('user')
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-{{ $color ?? 'aqua-active' }}">
                <h3 class="widget-user-username">{{ $name }}</h3>
                <h5 class="widget-user-desc">{{ $desc }}</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="{{ $img }}" alt="{{ $name.' profile photo' }}">
            </div>
            <div class="box-footer">
                @list
                    @slot('wrapper', ['class' => 'list-group list-group-unbordered'])
                    @slot('attr', ['class' => 'list-group-item'])
                    @slot('list', $list)
                @endlist
            </div>
        </div>
    @break
        
    @case('user-2')
        <div class="box box-widget widget-user-2">
            <div class="widget-user-header bg-{{ $color ?? 'aqua-active' }}">
              <div class="widget-user-image">
                <img class="img-circle" src="{{ $img }}" alt="{{ $name.' profile photo' }}">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">{{ $name }}</h3>
              <h5 class="widget-user-desc">{{ $desc }}</h5>
            </div>
            <div class="box-footer no-padding">
                @list
                    @slot('wrapper', ['class' => 'nav nav-stacked'])
                    @slot('attr', ['class' => 'list-group-item'])
                    @slot('list', $list)
                @endlist
              
            </div>
        </div>
    @break

    @default

@endswitch