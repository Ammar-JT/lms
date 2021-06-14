<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
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
        //notice we didn't use:
            //throw ValidationException::withMessage([])
        //.. cuz the validation exception is a string store in session, and we don't want that
        //.. we want a json repsonse so that we can parse it and use the message inside it
        //.. in our error message in our custom login form

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