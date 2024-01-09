<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Label $label)
    {
        $configId = $request->input('config_id');
        $currentPage = $request->input('page');
        $label_0 = $request->input('label_0');
        $label_1 = $request->input('label_1');
        $check_1 = '';
        $check_2 = '';
        $test = '';

        if (is_numeric($label_0)) {
            $request->merge(['label_0' => (int) $label_0]);
        } else if (is_numeric($label_1)) {
            $request->merge(['label_1' => (int) $label_1]);
        } else {
            $label_0 = mb_strtolower($label_0);
            $label_0 = ucfirst($label_0);
            $request->merge(['label_0' => $label_0]);

            $label_1 = mb_strtolower($label_1);
            $label_1 = ucfirst($label_1);
            $request->merge(['label_1' => $label_1]);
        }

        // $validator = Validator::make([], []);

        // if ($label_0) {
        //     $check_1 = Label::where('config_id', $configId)->where('label_1', $label_0)->get();
        //     if (!$check_1->isEmpty()) {
        //         $validator->errors()->add('label_0', 'The label 0 has already been taken.');
        //     }
        // }

        // if ($label_1) {
        //     $check_2 = Label::where('config_id', $configId)->where('label_0', $label_1)->get();
        //     if (!$check_2->isEmpty()) {
        //         $validator->errors()->add('label_1', 'The label 1 has already been taken.');
        //     }
        // }   

        // if ($validator->errors()) {
        //     return response()->json($validator->errors(), 400);
        // }

        if ($label_0) {
            $data = $request->validate([
                'label_0' => ['string', 'required', Rule::unique('labels')->where(function ($query) use ($request) {
                    return $query->where('config_id', $request->config_id);
                })],
            ]);

            $label->update(['label_0' => $data['label_0']]);
        }
        
        if ($label_1) {
            $data = $request->validate([
                'label_1' => ['string', 'required', Rule::unique('labels')->where(function ($query) use ($request) {
                    return $query->where('config_id', $request->config_id);
                })],
            ]);

            $label->update(['label_1' => $data['label_1']]);
        }

        $config = Config::find($configId);

        $labels = $config->labels();

        $table = $labels->orderBy('id', 'asc')
        ->paginate(
            $perPage = 3, 
            $columns = ['*'],
            $pageName = 'configs',
            $page = $currentPage
        );

        return $table;
    }
}