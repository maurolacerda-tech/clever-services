<?php

namespace Modules\Banners\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Gate;

use App\Helpers\Functions;
use App\Models\Menu;
use App\Models\Language;
use App\Models\Translation;
use Modules\Banners\Models\Banner;

use Modules\Banners\Http\Requests\BannerRequest;

class BannersController extends Controller
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

        $this->folder = config('banners.folder');
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

    public function index(Banner $banner)
    {  
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;        
        $menu_name = $this->menu_name;
        $slug = $this->slug;        

        if(!is_null($menu_id)){
            $banners = $banner->where('menu_id', $menu_id)->orderBy('order', 'asc')->paginate(50);
            $total = $banners->total();
            $orders = \Functions::number_array($total);
            return view('Banner::index', compact('banners', 'menu_icon', 'menu_name', 'slug', 'orders'));
        }else{
            abort(403, 'Página não encontrada');
        }
    }

    public function create()
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;
        $target_list = Banner::TARGET;
        return view('Banner::create', compact('menu_id', 'menu_icon', 'menu_name', 'target_list', 'slug', 'combine_filds'));
    }

    public function store(BannerRequest $request)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $data = $request->only(array_keys($request->rules()));
        if(isset($request->image))
            $data['image'] = $this->_uploadImage($request);
        Banner::create($data);
        return redirect()->back()->with('success','Adicionado com sucesso!');
    }

    public function edit(Banner $banner)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;
        $target_list = Banner::TARGET;
        $languages = Language::where('status', 'active')->orderBy('order', 'asc')->get();
        return view('Banner::edit', compact('banner', 'languages', 'menu_id', 'menu_icon', 'menu_name', 'target_list', 'slug', 'combine_filds'));
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $data = $request->only(array_keys($request->rules()));
        if(isset($request->image))
            $data['image'] = $this->_uploadImage($request, $banner->image);
        $banner->fill($data);
        $banner->save();
        return redirect()->back()->with('success','Atualizado com sucesso');
    }

    public function destroy(Banner $banner)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');
            
        Translation::where('parent_id', $banner->id)->where('menu_id', $this->menu_id)->delete();
        $banner->delete();              
        return redirect()->back()->with('success','Excluído com sucesso!');
    }

    public function status(Banner $banner)
    {
        $status = $banner->status == 'active' ? 'inactive' : 'active';
        $banner->status = $status;
        $banner->save();
        return redirect()->back()->with('success','Status atualizado com sucesso');
    }

    public function order(Request $request, Banner $banner)
    {
        $this_id = $banner->id;
        $currentOrder = $request->order;
        $nextOrder = $currentOrder + 1;
        $previousOrder = $currentOrder - 1;
        $direction = ($banner->order < $currentOrder ? 'up' : 'down');
        $menu_id = $this->menu_id;
        
        if($direction =='up'){
            $listModel = Banner::where('menu_id', $menu_id)->where('order', '>=', $nextOrder)
            ->where('id', '<>', $this_id)
            ->orderBy('order', 'asc')->get();
            foreach($listModel as $bannerItem){
                $bannerItem->order = $bannerItem->order + 1;
                $bannerItem->save();
            }
            $banner->order = $nextOrder;
            $banner->save();
        }else{
            $listModel = Banner::where('menu_id', $menu_id)->where('order', '<=', $previousOrder)
            ->where('id', '<>', $this_id)
            ->orderBy('order', 'asc')->get();
            foreach($listModel as $bannerItem){
                $bannerItem->order = $bannerItem->order - 1;
                $bannerItem->save();
            }

            $banner->order = $previousOrder;
            $banner->save();
        }
        $cont = 1;
        $listModel = Banner::where('menu_id', $menu_id)->orderBy('order', 'asc')->get();
        foreach($listModel as $bannerItem){
            $bannerItem->order = $cont;
            $bannerItem->update();
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