<?php

namespace App\Transformers;

use App\transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(transaction $transaction)
    {
        return [
            'identifier' => (int)$transaction->id,
            'quantity' => (int)$transaction->quantity,
            'buyer' => (int)$transaction->buyer_id,
            'product' => (int)$transaction->product_id,
            'creationDate' => (string)$transaction->created_at,
            'lastChange' => (string)$transaction->updated_at,
            'deletedDate' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show',$transaction->id),
                ],
                [
                    'rel' => 'buyers',
                    'href' => route('buyers.show',$transaction->buyer_id),
                ],
                [
                    'rel' => 'transaction.product',
                    'href' => route('products.show',$transaction->product_id),
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index',$transaction->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes =  [
            'identifier' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

    public static function transformedAttribute($index)
    {
        $attributes =  [
            'id' => 'identifier',
            'quantity' => 'quantity',
            'buyer_id' => 'buyer',
            'product_id' => 'product',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

}
