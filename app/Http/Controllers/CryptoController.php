<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CryptoController extends Controller
{   
    protected $_3DES;

    public function __construct() {
        $this->_3DES = new _3DesController();
    }

    public function encrypt_3DES(Request $request) {
        $raw = $request->input('raw');

        if ($raw) {
            return response()->json(["data" => $this->_3DES->encrypt($raw)], 200);
        }
        
        return response("You need to provide a data to be encrypted in the shape {raw: 'dataToBeEncrypted'}", 400);
    }

    public function decrypt_3DES(Request $request) {
        $encrypted = $request->input('encrypted');

        if ($encrypted) {
            return response(["data" => $this->_3DES->decrypt($encrypted)], 200);
        }
        
        return response("You need to provide a data to be decrypted in the shape {encrypted: 'dataToBeDecrypted'}", 400);
    }
}
