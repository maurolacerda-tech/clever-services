<?php

return [
    'name' => 'Banners',
    'controller' => 'BannersController',
    'actions' => 'get;index,get;create;create,post;store;store,get;edit;edit/{banner},put;update;{banner},delete;destroy;{banner},post;status;{banner}/status,post;order;{banner}/order',
    'fields' => 'name,image,url,status,target,order,summary_01,summary_02,menu_id',
    'menu' => true,
    'author' => 'Mauro Lacerda - contato@maurolacerda.com.br',
    'folder' => 'banners'
];