<?php

return [
    'name' => 'Banners',
    'controller' => 'BannersController',
    'actions' => 'get;index,get;create,post;store,get;edit,put;update,delete;destroy,post;status,post;order',
    'fields' => 'name,image,url,status,target,order,summary_01,summary_02,menu_id',
    'menu' => true,
    'author' => 'Mauro Lacerda - contato@maurolacerda.com.br',
    'folder' => 'banners'
];