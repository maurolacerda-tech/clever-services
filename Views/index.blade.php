@extends('admin.layout.default')

@section('content')

<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="{{$menu_icon}} bg-orange"></i>
                <div class="d-inline">
                    <h5>{{$menu_name}}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <nav class="breadcrumb-container" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.dashboard.index')}}"><i class="ik ik-home"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{$menu_name}}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="row clearfix">
    <div class="card table-card"> 
        <div class="card-header text-right">
            <a href="{{ url('panel/'.$slug.'/create') }}" class="btn2 btn-dark">
                <i class="ik ik-plus"></i>
                Adicionar
            </a>
        </div>      
        <div class="card-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            @isset ($combine_filds['parent_id'])
                                <th width="150">{{$combine_filds['parent_id']}}</th>
                            @endisset
                            <th width="100">Ordem</th>                            
                            @isset ($combine_filds['featured'])
                                <th width="100">{{$combine_filds['featured']}}</th>
                            @endisset
                            <th width="100">Status</th>
                            <th width="100">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse( $services as $service )
                        <tr>                            
                            <td>{{$service->name}}</td>
                            @isset ($combine_filds['parent_id'])
                                <td>
                                    <a href="{{ url('panel/'.$slug.'?parent='.$service->id) }}">
                                        ({{$service->count_child}}) categorias filhos
                                    </a>
                                </td>
                            @endisset
                            <td>
                                {{ Form::open(['url' => ['panel/'.$slug.'/'.$service->id.'/order'], 'method' => 'POST', 'id' => 'formorder'.$service->id ]) }}
                                    {{Form::select('order', $orders, $service->order ,['class' => 'form-control fieldOrder fs-13'])}}
                                {{ Form::close() }}    
                            </td> 
                            @isset ($combine_filds['featured']) 
                            <td>
                                <input type="checkbox" class="js-status" @if ($service->featured == 'active') checked @endif onchange="event.preventDefault();document.getElementById('form-featured{{$service->id}}').submit();"  />
                            </td> 
                            @endisset
                            <td>
                                <input type="checkbox" class="js-status" @if ($service->status == 'active') checked @endif onchange="event.preventDefault();document.getElementById('form-status{{$service->id}}').submit();"  />
                            </td>                     
                            <td>
                                <a href="{{ url('panel/'.$slug.'/edit/'.$service->id)}}" title="" data-toggle="tooltip" data-placement="top" data-original-title="editar" class="mr-2">
                                    <i class="ik ik-edit f-16 mr-15 text-green"></i>
                                </a>


                                <a href="javascript:void(0);" data-toggle="modal" data-target="#deleteModal{{$service->id}}" >
                                    <i class="ik ik-trash-2 f-16 text-red"></i>
                                </a>

                                <div class="modal fade" id="deleteModal{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteModal{{$service->id}}Label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        {{ Form::open(['url' => ['panel/'.$slug.'/'.$service->id], 'method' => 'DELETE', 'id' => 'form-delete'.$service->id ]) }}
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterLabel">Excluir</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                Tem certeza que deseja excluir <i>"{{$service->name}}"</i>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn2 btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn2 btn-danger">Sim, pode excluir</button>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>

                                
                                {{ Form::open(['url' => ['panel/'.$slug.'/'.$service->id.'/featured'], 'method' => 'POST', 'id' => 'form-featured'.$service->id ]) }}
                                {{ Form::close() }}
                                
                                {{ Form::open(['url' => ['panel/'.$slug.'/'.$service->id.'/status'], 'method' => 'POST', 'id' => 'form-status'.$service->id ]) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <p>Nenhum Resultado!</p>
                            </td>
                        </tr>
                        @endforelse 
                    </tbody>
                </table>
                @if($services instanceof \Illuminate\Pagination\LengthAwarePaginator )
                    {{$services->links()}}
                @endif
            </div>

        </div>
    </div>
</div>
@endsection