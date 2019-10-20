<?php

namespace App\Providers;

use App\Repositories\Products\ElasticsearchProductsRepository;
use App\Repositories\Products\EloquentProductsRepository;
use App\Repositories\Products\ProductsRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('App\Repositories\Products\ProductDimensionsInterface', 'App\Repositories\Products\ProductDimension');
        // $this->app->bind(ProductsRepository::class, function () {
        //     return new EloquentProductsRepository();
        // });
        try {
            $client = new GuzzleClient();
            $request = $client->get('http://localhost:9200');
            $status = $request->getStatusCode();
            if($status == 200){
                 $this->app->singleton(ProductsRepository::class, function($app) {
                    // This is useful in case we want to turn-off our
                    // search cluster or when deploying the search
                    // to a live, running application at first.
                    if (!config('services.search.enabled')) {
                        return new EloquentProductsRepository();
                    }

                    return new ElasticsearchProductsRepository(
                        $app->make(Client::class)
                    );
                });

            }else{
                // The server responded with some error. You can throw back your exception
                // to the calling function or decide to handle it here

                return new EloquentProductsRepository();
            }

        } catch (ConnectException $e) {
            //Catch the guzzle connection errors over here.These errors are something 
            // like the connection failed or some other network error

            $this->app->singleton(ProductsRepository::class, function($app) {

                return new EloquentProductsRepository();
            });
        }
        
       
        
        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }
}
