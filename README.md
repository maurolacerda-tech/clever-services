# clever-banners
Módulo de criação de serviços do CMS da cleverweb.com.br

## Instalação
```
composer require maurolacerda-tech/clever-services:dev-master
```
```
php artisan migrate
```

## Opcionais
Você poderá públicar os arquivos de visualização padrão em seu diretório views/vendor/Service

```
php artisan vendor:publish --provider="Modules\Services\Providers\ServiceServiceProvider" --tag=views
```


Para públicar os arquivos de configurações.

```
php artisan vendor:publish --provider="Modules\Services\Providers\ServiceServiceProvider" --tag=config
```

