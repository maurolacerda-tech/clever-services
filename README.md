# clever-banners
Módulo de criação de banners do CMS da cleverweb.com.br

## Instalação
```
composer require maurolacerda-tech/clever-banners:dev-master
```
```
php artisan migrate
```

## Opcionais
Você poderá públicar os arquivos de visualização padrão em seu diretório views/vendor/Banner

```
php artisan vendor:publish --provider="Modules\Banners\Providers\BannerServiceProvider" --tag=views
```


Para públicar os arquivos de configurações.

```
php artisan vendor:publish --provider="Modules\Banners\Providers\BannerServiceProvider" --tag=config
```

