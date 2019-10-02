<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformeredInput = [];
        foreach ($request->request->all() as $input => $value) {
            $transformeredInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformeredInput);
        $responce =  $next($request);

        if(isset($responce->exception) && $responce->exception instanceof ValidationException)
        {
            $data = $responce->getData();

            $transformeredErrors = [];

            foreach ($data->error as $field => $error) {
                $transformeredField = $transformer::transformedAttribute($field);
                $transformeredErrors[$transformeredField] = str_replace($field, $transformeredField, $error);
            }
            $data->error = $transformeredErrors;
            $responce->setData($data);
        }

        return $responce;
    }
}
