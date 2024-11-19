<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Add a new link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        // Create the new link
        $link = Link::create([
            'name' => $validatedData['name'],
            'link' => $validatedData['link'],
        ]);

        return response()->json([
            'message' => 'Link added successfully',
            'link' => $link,
        ], 201);
    }

    /**
     * Show all links.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        // Get all links
        $links = Link::all();

        return response()->json([
            'links' => $links,
        ], 200);
    }

    /**
     * Delete an existing link.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        // Find the link by ID
        $link = Link::find($id);

        // Check if the link exists
        if (!$link) {
            return response()->json([
                'message' => 'Link not found',
            ], 404);
        }

        // Delete the link
        $link->delete();

        return response()->json([
            'message' => 'Link deleted successfully',
        ], 200);
    }
}
