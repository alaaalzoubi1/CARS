<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    /**
     * Add or update information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOrUpdate(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'address' => 'sometimes|string|max:255',
            'about_us' => 'sometimes|string',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if information already exists
        $information = Information::first();

        if ($information) {
            // Update existing information
            if ($request->exists('address'))
                $information->address = $validatedData['address'];
            if ($request->exists('about_us'))
                $information->about_us = $validatedData['about_us'];

            if ($request->hasFile('logo')) {
                // Handle the logo upload
                $logoName = time() . '-' . $request->file('logo')->getClientOriginalName();
                $logoPath = $request->file('logo')->storeAs('logos', $logoName, 'public');
                // Delete the old logo if exists
                if ($information->logo) {
                    Storage::disk('public')->delete($information->logo);
                }
                $information->logo = $logoPath;
            }

            if ($request->hasFile('cover')) {
                // Handle the cover upload
                $coverName = time() . '-' . $request->file('cover')->getClientOriginalName();
                $coverPath = $request->file('cover')->storeAs('covers', $coverName, 'public');
                // Delete the old cover if exists
                if ($information->cover) {
                    Storage::disk('public')->delete($information->cover);
                }
                $information->cover = $coverPath;
            }

            $information->save();

            return response()->json([
                'message' => 'Information updated successfully',
                'information' => $information,
            ], 200);
        } else {
            // Create new information
            if ($request->hasFile('logo')) {
                // Handle the logo upload
                $logoName = time() . '-' . $request->file('logo')->getClientOriginalName();
                $logoPath = $request->file('logo')->storeAs('logos', $logoName, 'public');
            } else {
                $logoPath = null;
            }

            if ($request->hasFile('cover')) {
                // Handle the cover upload
                $coverName = time() . '-' . $request->file('cover')->getClientOriginalName();
                $coverPath = $request->file('cover')->storeAs('covers', $coverName, 'public');
            } else {
                $coverPath = null;
            }

            $information = Information::create([
                'address' => $validatedData['address'],
                'about_us' => $validatedData['about_us'],
                'logo' => $logoPath,
                'cover' => $coverPath,
            ]);

            return response()->json([
                'message' => 'Information added successfully',
                'information' => $information,
            ], 201);
        }
    }

    /**
     * Show information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        // Get the information (only one row should exist)
        $information = Information::first();

        if (!$information) {
            return response()->json([
                'message' => 'No information found',
            ], 404);
        }

        return response()->json([
            'information' => $information,
        ], 200);
    }

    /**
     * Delete the information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        // Get the information (only one row should exist)
        $information = Information::first();

        if (!$information) {
            return response()->json([
                'message' => 'No information found',
            ], 404);
        }

        // Delete the logo and cover files from storage
        Storage::disk('public')->delete($information->logo);
        Storage::disk('public')->delete($information->cover);

        // Delete the information
        $information->delete();

        return response()->json([
            'message' => 'Information deleted successfully',
        ], 200);
    }
    public function show_user()
    {
        // Get the information (only one row should exist)
        $information = Information::select('address','about_us','logo','cover')->first();
        $links = Link::all()->select('name','link');
        if (!$information) {
            return response()->json([
                'message' => 'No information found',
            ], 404);
        }

        return response()->json([
            'information' => $information,
            'links' => $links,
        ], 200);
    }

}
