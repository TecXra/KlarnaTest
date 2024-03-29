<?php

namespace App\Repositories\Products;

use App\Product;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchProductsRepository implements ProductsRepository
{
    private $search;

    public function __construct(Client $client) {
        $this->search = $client;
    }

    public function search(string $query = "", $size): Collection
    {
        $items = $this->searchOnElasticsearch($query, $size);

        return $this->buildCollection($items);
    } 

    private function searchOnElasticsearch(string $query, $size): array
    {
    	$instance = new Product;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        // 'fields' => ['title', 'body', 'tags'],
                    	'fields' => ['product_name'],
                        'query' => $query,
                    ],
                ],
                'size' => $size,
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items): Collection
    {
        /**
         * The data comes in a structure like this:
         * 
         * [ 
         *      'hits' => [ 
         *          'hits' => [ 
         *              [ '_source' => 1 ], 
         *              [ '_source' => 2 ], 
         *          ]
         *      ] 
         * ]
         * 
         * And we only care about the _source of the documents.
        */
        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];
        
        $sources = array_map(function ($source) {
            // The hydrate method will try to decode this
            // field but ES gives us an array already.
            $source['product_name'] = json_encode($source['product_name']);
            return $source;
        }, $hits);

        // We have to convert the results array into Eloquent Models.
        return Product::hydrate($sources);
    }
}