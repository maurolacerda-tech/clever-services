<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Gate;

use App\Helpers\Functions;
use App\Models\Menu;
use App\Models\Language;
use App\Models\Translation;
use Modules\Services\Models\Service;

use Modules\Services\Http\Requests\ServiceRequest;

class ServicesController extends Controller
{

    protected $menu_id;
    protected $menu_icon;
    protected $menu_name;
    protected $slug;
    protected $folder;
    protected $combine_filds;    
    
    public function __construct()
    {
        $slug = Functions::get_menuslug();
        $menu = Menu::where('slug',$slug)->first();
        $this->slug = $slug;        

        $this->folder = config('services.folder');
        if($menu){
            $this->menu_id = $menu->id;
            $this->menu_icon = $menu->icons;
            $this->menu_name = $menu->name;
            $keysFilds = explode(',',$menu->fields_active);
            $titlesFilds = explode(',',$menu->fields_title);
            $combineFilds = array_combine($keysFilds, $titlesFilds);
            $this->combine_filds = $combineFilds;
        }else{
            $this->menu_id = null;
        }
    }

    public function index(Request $request, Service $service)
    {  
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;

        if(!is_null($menu_id)){
            $services = $service->where('menu_id', $menu_id)
            ->where(function ($query) use ($request) {            
                if(isset($request['parent']) && is_numeric($request['parent']) ){
                    $parent = (int)$request['parent'];
                    $query->where('parent_id', $parent);
                }else{
                    $query->whereNull('parent_id');
                }           
            })->orderBy('order', 'asc')->paginate(50);
            $total = $services->total();
            $orders = \Functions::number_array($total);
            return view('Service::index', compact('services', 'menu_icon', 'menu_name', 'slug', 'orders', 'combine_filds'));
        }else{
            abort(403, 'Página não encontrada');
        }
    }

    public function create(Service $service)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;
        $target_list = Service::TARGET;

        $option_void = ['' => 'Selecione' ];
        $categories_list = $option_void+$service->combo_all();

        return view('Service::create', compact('menu_id', 'menu_icon', 'menu_name', 'target_list', 'slug', 'combine_filds', 'categories_list'));
    }

    public function store(ServiceRequest $request)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $data = $request->only(array_keys($request->rules()));
        if(isset($request->image))
            $data['image'] = $this->_uploadImage($request);
        Service::create($data);
        return redirect()->back()->with('success','Adicionado com sucesso!');
    }

    public function edit(Service $service)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;
        $target_list = Service::TARGET;
        $languages = Language::where('status', 'active')->orderBy('order', 'asc')->get();

        $serviceModel = new Service;
        $option_void = ['' => 'Selecione' ];
        $categories_list = $option_void+$serviceModel->combo_all();

        return view('Service::edit', compact('service', 'languages', 'menu_id', 'menu_icon', 'menu_name', 'target_list', 'slug', 'combine_filds', 'categories_list'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $data = $request->only(array_keys($request->rules()));
        if(isset($request->image))
            $data['image'] = $this->_uploadImage($request, $service->image);
        $service->fill($data);
        $service->save();
        return redirect()->back()->with('success','Atualizado com sucesso');
    }

    public function destroy(Service $service)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');
            
        Translation::where('parent_id', $service->id)->where('menu_id', $this->menu_id)->delete();
        $service->delete();              
        return redirect()->back()->with('success','Excluído com sucesso!');
    }

    public function status(Service $service)
    {
        $status = $service->status == 'active' ? 'inactive' : 'active';
        $service->status = $status;
        $service->save();
        return redirect()->back()->with('success','Status atualizado com sucesso');
    }

    public function featured(Service $service)
    {
        $featured = $service->featured == 'active' ? 'inactive' : 'active';
        $service->featured = $featured;
        $service->save();
        return redirect()->back()->with('success','Destaque atualizado com sucesso');
    }

    public function order(Request $request, Service $service)
    {
        $this_id = $service->id;
        $currentOrder = $request->order;
        $nextOrder = $currentOrder + 1;
        $previousOrder = $currentOrder - 1;
        $direction = ($service->order < $currentOrder ? 'up' : 'down');
        $menu_id = $this->menu_id;
        
        if($direction =='up'){
            $listModel = Service::where('menu_id', $menu_id)->where('order', '>=', $nextOrder)
            ->where('id', '<>', $this_id)
            ->orderBy('order', 'asc')->get();
            foreach($listModel as $serviceItem){
                $serviceItem->order = $serviceItem->order + 1;
                $serviceItem->save();
            }
            $service->order = $nextOrder;
            $service->save();
        }else{
            $listModel = Service::where('menu_id', $menu_id)->where('order', '<=', $previousOrder)
            ->where('id', '<>', $this_id)
            ->orderBy('order', 'asc')->get();
            foreach($listModel as $serviceItem){
                $serviceItem->order = $serviceItem->order - 1;
                $serviceItem->save();
            }

            $service->order = $previousOrder;
            $service->save();
        }
        $cont = 1;
        $listModel = Service::where('menu_id', $menu_id)->orderBy('order', 'asc')->get();
        foreach($listModel as $serviceItem){
            $serviceItem->order = $cont;
            $serviceItem->update();
            $cont++;
        }
        return redirect()->back()->with('success','Ordem atualizada com sucesso');

    }

    protected function _uploadImage(Request $request, $nameImage = null)
    {
        if(isset($request->image)){           
            $responseUpload = \Upload::imagePublic($request, 'image', $this->folder, null, $nameImage);
            if($responseUpload->original['success']){
                return $responseUpload->original['file'];
            }
            return null;
        }else{
            return null;
        }
    }

}