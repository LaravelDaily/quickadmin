<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

trait FileUploadTrait
{

    /**
     * File upload trait used in controllers to upload files
     */
    public function saveFiles(Request $request)
    {
        if (!file_exists(public_path('uploads'))) {
            mkdir(public_path('uploads'), 0777);
            mkdir(public_path('uploads/thumb'), 0777);
        }
        $newRequest = null; // Variable to hold a new request created by above array merging
        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($request->has($key . '_w') && $request->has($key . '_h')) {
                    // Check file width
                    $filename = time() . '-' . $request->file($key)->getClientOriginalName();
                    $file     = $request->file($key);
                    $image    = Image::make($file);
                    Image::make($file)->resize(50, 50)->save(public_path('uploads/thumb') . '/' . $filename);
                    $width  = $image->width();
                    $height = $image->height();
                    if ($width > $request->{$key . '_w'} && $height > $request->{$key . '_h'}) {
                        $image->resize($request->{$key . '_w'}, $request->{$key . '_h'});
                    } elseif ($width > $request->{$key . '_w'}) {
                        $image->resize($request->{$key . '_w'}, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } elseif ($height > $request->{$key . '_w'}) {
                        $image->resize(null, $request->{$key . '_h'}, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $image->save(public_path('uploads') . '/' . $filename);
                    // Determine which request's data to use further
                    $requestDataToMerge = $newRequest == null ? $request->all() : $newRequest->all();
                    // Create new request without changing the original one (prevents removal of specific metadata which disables parsing of a second file)
                    $newRequest = new Request(array_merge($requestDataToMerge, [$key => $filename]));
                } else {
                    $filename = time() . '-' . $request->file($key)->getClientOriginalName();
                    $request->file($key)->move(public_path('uploads'), $filename);
                    // Determine which request's data to use further
                    $requestDataToMerge = $newRequest == null ? $request->all() : $newRequest->all();
                    // Create new request without changing the original one (prevents removal of specific metadata which disables parsing of a second file)
                    $newRequest = new Request(array_merge($requestDataToMerge, [$key => $filename]));
                }
            }
        }

        return $newRequest == null ? $request : $newRequest;
    }
}
