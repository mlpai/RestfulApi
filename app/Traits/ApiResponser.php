<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

trait ApiResponser
{
    private function successResponse($data,$code)
    {
        return response()->json($data,$code);
    }

    protected function errorResponse($message,$code)
    {
        return response()->json(['error'=>$message,'code'=>$code],$code);
    }

    protected function showAll(Collection $collection,$code = 200)
    {
        if($collection->isEmpty())
        {
            return $this->successResponse(['data'=> $collection],200);
        }


        $transformer = $collection->first()->transformer;

        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortBy($collection, $transformer);
        $collection = $this->paginator($collection);
        $collection = $this->transformData($collection,$transformer);
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection,$code);
    }

    protected function showOne(Model $model,$code = 200)
    {

        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);

        return $this->successResponse($model,$code);
    }


    protected function sortBy(Collection $collection, $transformer)
    {
        if(request()->has('sort_by'))
        {
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }

        return $collection;
    }

    protected function filterData(Collection $collection, $transformer)
    {
        foreach(request()->query() as $query => $value)
        {
            $attribute = $transformer::originalAttribute($query);
            if(isset($attribute, $value))
            {
                $collection = $collection->where($attribute,$value);
            }
        }
        return $collection;
    }

    protected function showMessage($message,$code = 200)
    {
        return $this->successResponse(['data'=>$message],$code);
    }

    protected function paginator(Collection $collection)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if(request()->has('per_page'))
        {
            request()->validate(['per_page'=> 'integer|min:2|max:50']);
            $perPage = (int) request()->per_page;
        }
        $result = $collection->slice(($page - 1) *  $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all);

        return $paginated;
    }

    protected function transformData($data,$transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($url, 30, function () use($data) {
            return $data;
        });
    }

}
