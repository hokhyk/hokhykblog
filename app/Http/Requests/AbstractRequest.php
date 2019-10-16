<?php


namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest as LaravelRequest;
//use Illuminate\Support\Facades\Config;

/**
 * Class AbstractRequest
 * @package App\Http\Requests
 */
abstract class AbstractRequest extends LaravelRequest
{
    /**
     * Overriding this function to modify the any user input before
     * applying the validation rules.
     *
     * @param null $keys
     *
     * @return  array
     */
    public function all($keys = null)
    {
        $requestData = parent::all($keys);

        $requestData = $this->mergeUrlParametersWithRequestData($requestData);

//        $requestData = $this->decodeHashedIdsBeforeValidation($requestData);

        return $requestData;
    }

    /**
     * Maps Keys in the Request.
     *
     * For example, ['data.attributes.name' => 'firstname'] would map the field [data][attributes][name] to [firstname].
     * Note that the old value (data.attributes.name) is removed the original request - this method manipulates the request!
     * Be sure you know what you do!
     *
     * @param array $fields
     */
    public function mapInput(array $fields)
    {
        $data = $this->all();

        foreach ($fields as $oldKey => $newKey) {
            // the key to be mapped does not exist - skip it
            if (!array_has($data, $oldKey)) {
                continue;
            }

            // set the new field and remove the old one
            array_set($data, $newKey, array_get($data, $oldKey));
            array_forget($data, $oldKey);
        }

        // overwrite the initial request
        $this->replace($data);
    }


    /**
     * Used from the `authorize` function if the Request class.
     * To call functions and compare their bool responses to determine
     * if the user can proceed with the request or not.
     *
     * @param array $functions
     *
     * @return  bool
     */
    protected function check(array $functions)
    {
        $orIndicator = '|';
        $returns = [];

        // iterate all functions in the array
        foreach ($functions as $function) {

            // in case the value doesn't contains a separator (single function per key)
            if (!strpos($function, $orIndicator)) {
                // simply call the single function and store the response.
                $returns[] = $this->{$function}();
            } else {
                // in case the value contains a separator (multiple functions per key)
                $orReturns = [];

                // iterate over each function in the key
                foreach (explode($orIndicator, $function) as $orFunction) {
                    // dynamically call each function
                    $orReturns[] = $this->{$orFunction}();
                }

                // if in_array returned `true` means at least one function returned `true` thus return `true` to allow access.
                // if in_array returned `false` means no function returned `true` thus return `false` to prevent access.
                // return single boolean for all the functions found inside the same key.
                $returns[] = in_array(true, $orReturns) ? true : false;
            }
        }

        // if in_array returned `true` means a function returned `false` thus return `false` to prevent access.
        // if in_array returned `false` means all functions returned `true` thus return `true` to allow access.
        // return the final boolean
        return in_array(false, $returns) ? false : true;
    }

    /**
     * apply validation rules to the ID's in the URL, since Laravel
     * doesn't validate them by default!
     *
     * Now you can use validation riles like this: `'id' => 'required|integer|exists:items,id'`
     *
     * @param array $requestData
     *
     * @return  array
     */
    private function mergeUrlParametersWithRequestData(Array $requestData)
    {
        if (isset($this->urlParameters) && !empty($this->urlParameters)) {
            foreach ($this->urlParameters as $param) {
                $requestData[$param] = $this->route($param);
            }
        }

        return $requestData;
    }

    /**
     * This method mimics the $request->input() method but works on the "decoded" values
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public function getInputByKey($key = null, $default = null)
    {
        return data_get($this->all(), $key, $default);
    }

}
