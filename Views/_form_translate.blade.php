{{ Form::hidden('menu_id', $menu_id) }}

@isset ($combine_filds['name'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('name', $combine_filds['name']) }}
            {{ Form::text('name', null, ['class' => $errors->has('name') ?  'form-control is-invalid' : 'form-control']) }}
            @include('admin.partials._help_block',['field' => 'name'])
        </div>
    </div>
</div>
@endisset

@isset ($combine_filds['url'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('url', $combine_filds['url']) }}
            {{ Form::text('url', null, ['class' => $errors->has('url') ?  'form-control is-invalid' : 'form-control']) }}
            @include('admin.partials._help_block',['field' => 'url'])
        </div>
    </div>
</div>
@endisset

@isset ($combine_filds['target'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('target', 'Alvo') }}
            {{Form::select('target', $target_list,null,['class' => $errors->has('target') ?  'form-control is-invalid' : 'form-control'])}}
            @include('admin.partials._help_block',['field' => 'target'])
        </div>
    </div>
</div>
@endisset


@isset ($combine_filds['summary_01'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('summary_01', $combine_filds['summary_01']) }}
            {{ Form::text('summary_01', null, ['class' => $errors->has('summary_01') ?  'form-control is-invalid' : 'form-control']) }}
            @include('admin.partials._help_block',['field' => 'summary_01'])
        </div>
    </div>
</div>
@endisset

@isset ($combine_filds['summary_02'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('summary_02', $combine_filds['summary_02']) }}
            {{ Form::text('summary_02', null, ['class' => $errors->has('summary_02') ?  'form-control is-invalid' : 'form-control']) }}
            @include('admin.partials._help_block',['field' => 'summary_02'])
        </div>
    </div>
</div>
@endisset