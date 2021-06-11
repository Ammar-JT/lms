<?php

namespace App\Exceptions;

use Exception;

class AuthFailedException extends Exception
{

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        //422 means everthings went right, but the credintials of the user is not correct
        return response()->json([
            'message' => 'These credintials do not match our records'
        ],422);  
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    
}