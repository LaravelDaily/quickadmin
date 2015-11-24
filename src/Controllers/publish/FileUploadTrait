<?php namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait FileUploadTrait
{
    /**
     * File upload trait used in controllers to upload files
     */
	public function saveFiles(Request $request)
	{
		foreach($request->all() as $key => $value) {
			if($request->hasFile($key)) {
				$filename = time() . '-' . $request->file($key)->getClientOriginalName();
				$request->file($key)->move(public_path('uploads'), $filename);
				$request->replace(array_merge($request->all(), [$key => $filename]));
				$request->files->remove($key);
			}
		}
	}
}