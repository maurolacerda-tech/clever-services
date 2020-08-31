<?php

return [
    'name' => 'Services',
    'controller' => 'ServicesController',
    'actions' => 'get;index,get;create;create,post;store;store,get;edit;edit/{service},put;update;{service},delete;destroy;{service},post;status;{service}/status,post;featured;{service}/featured,post;order;{service}/order',
    'fields' => 'name,slug,image,icon,summary,status,featured,order,body,seo_title,meta_description,meta_keywords,menu_id,parent_id',
    'menu' => true,
    'author' => 'Mauro Lacerda - contato@maurolacerda.com.br',
    'folder' => 'services'
];