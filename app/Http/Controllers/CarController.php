<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\CarFeature;
use App\Models\CarImage;
use App\Models\Category;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Store a newly created car and related data in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CarRequest $request)
    {
        // Validate the request data
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            // Create rent details
            $rent = Rent::create([
                'daily' => $validatedData['daily'],
                'weekly' => $validatedData['weekly'],
                'monthly' => $validatedData['monthly'],
                'daily_with_driver' => $validatedData['daily_with_driver'],
                'weekly_with_driver' => $validatedData['weekly_with_driver'],
                'monthly_with_driver' => $validatedData['monthly_with_driver'],
            ]);

            // Create car
            $car = Car::create([
                'trademark' => $validatedData['trademark'],
                'model' => $validatedData['model'],
                'delivery' => $validatedData['delivery'],
                'details' => $validatedData['details'],
                'rent_id' => $rent->id,
                'insurance' => $validatedData['insurance'],
                'KMs' => $validatedData['KMs'],
                'deposit' => $validatedData['deposit'],
                'min_age' => $validatedData['min_age'],
                'category_id' => $validatedData['category_id'],
            ]);

            // Create car features
            CarFeature::create([
                'gear' => $validatedData['gear'],
                'engine' => $validatedData['engine'],
                'color' => $validatedData['color'],
                'seats' => $validatedData['seats'],
                'doors' => $validatedData['doors'],
                'luggage' => $validatedData['luggage'],
                'sensors' => $validatedData['sensors'],
                'bluetooth' => $validatedData['bluetooth'],
                'gcc' => $validatedData['gcc'],
                'camera' => $validatedData['camera'],
                'lcd' => $validatedData['lcd'],
                'safety' => $validatedData['safety'],
                'radio' => $validatedData['radio'],
                'Mb3_CD' => $validatedData['Mb3_CD'],
                'car_id' => $car->id,
            ]);

            // Handle image uploads
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('car_images', $imageName, 'public');

                CarImage::create([
                    'car_id' => $car->id,
                    'image' => $imagePath,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Car and related data created successfully',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'There was an error creating the car',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function showByCategory($category_id)
    {
        if (!is_numeric($category_id) || $category_id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        if (!Category::where('id', $category_id)->exists()) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        $cars = Car::where('category_id', $category_id)
            ->with(['rent', 'images' => function($query) {
                $query->limit(1);
            }])
            ->get();

        return response()->json([
            'cars' => $cars,
        ], 200);
    }
    /**
     * Show detailed information for a specific car, including features and images.
     *
     * * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function details($id)
    {
        // Validate the car ID format
        if (!is_numeric($id) || $id <= 0)
        {
            return response()->json([ 'message' => 'Invalid ID format', ], 400);
        }
        // Find the car by ID and include related features and images
        $car = Car::with(['rent', 'features', 'images'])->find($id);
        // Check if the car exists
        if (!$car)
        {
            return response()->json([ 'message' => 'Car not found', ], 404);
        }
        return response()->json([ 'car' => $car, ], 200);
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'trademark' => 'sometimes|string|max:255',
            'model' => 'sometimes|string|max:255',
            'delivery' => 'sometimes|string|max:255',
            'details' => 'sometimes|string',
            'insurance' => 'sometimes|string|max:255',
            'KMs' => 'sometimes|string|max:255',
            'deposit' => 'sometimes|string|max:255',
            'min_age' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'daily' => 'sometimes|numeric',
            'weekly' => 'sometimes|numeric',
            'monthly' => 'sometimes|numeric',
            'daily_with_driver' => 'sometimes|numeric',
            'weekly_with_driver' => 'sometimes|numeric',
            'monthly_with_driver' => 'sometimes|numeric',
        ]);
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        // Find the car by ID
        $car = Car::find($id);

        // Check if the car exists
        if (!$car) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Update car details
            $car->update($validatedData);

            // Update rent details if provided
            if ($request->hasAny(['daily', 'weekly', 'monthly', 'daily_with_driver', 'weekly_with_driver', 'monthly_with_driver'])) {
                $car->rent->update($request->only([
                    'daily',
                    'weekly',
                    'monthly',
                    'daily_with_driver',
                    'weekly_with_driver',
                    'monthly_with_driver'
                ]));
            }
            DB::commit();

            return response()->json([
                'message' => 'Car and related data updated successfully',
                'car' => $car,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'There was an error updating the car',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateDetails(Request $request,$id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        $validatedData = $request->validate([
            'gear' => 'sometimes|string|max:255',
            'engine' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:255',
            'seats' => 'sometimes|integer',
            'doors' => 'sometimes|integer',
            'luggage' => 'sometimes|integer',
            'sensors' => 'sometimes|boolean',
            'bluetooth' => 'sometimes|boolean',
            'gcc' => 'sometimes|boolean',
            'camera' => 'sometimes|boolean',
            'lcd' => 'sometimes|boolean',
            'safety' => 'sometimes|boolean',
            'radio' => 'sometimes|boolean',
            'Mb3_CD' => 'sometimes|boolean',]);
        $car_details = CarFeature::where('car_id',$id)->first();
        if (!$car_details) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }
        if ($request->hasAny(['gear', 'engine', 'color', 'seats', 'doors', 'luggage', 'sensors', 'bluetooth', 'gcc', 'camera', 'lcd', 'safety', 'radio', 'Mb3_CD'])) {
            $car_details->update($request->only([
                'gear',
                'engine',
                'color',
                'seats',
                'doors',
                'luggage',
                'sensors',
                'bluetooth',
                'gcc',
                'camera',
                'lcd',
                'safety',
                'radio',
                'Mb3_CD'
            ]));
        }
        return response()->json([
            'message' => 'Car details updated successfully',
            'car_details' => $car_details,
        ], 200);


    }
    public function deleteImage($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        // Find the image by car_id and image_id
        $image = CarImage::where('id', $id)
            ->first();
        // Check if the image exists
        if (!$image) {
            return response()->json([
                'message' => 'Image not found for the specified car'
            ], 404);
        }

        // Delete the image file from storage
        Storage::disk('public')->delete($image->image);

        // Delete the image record from the database
        $image->delete();

        return response()->json([
            'message' => 'Image deleted successfully'
        ], 200);
    }
    public function addImages(Request $request)
    {

        $validatedData = $request->validate([
            'car_id' => 'required',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if (!is_numeric($request->car_id) || $request->car_id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        // Check if the car exists
        if (!Car::where('id', $request->car_id)->exists()) {
            return response()->json([
                'message' => 'Car not found'
            ], 404);
        }

        // Handle image uploads
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $imageName = time() . '-' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('car_images', $imageName, 'public');

            // Store the image record in the database
            $carImage = CarImage::create([
                'car_id' => $validatedData['car_id'],
                'image' => $imagePath,
            ]);

            $imagePaths[] = $carImage->image;
        }

        return response()->json([
            'message' => 'Images added successfully',
            'images' => $imagePaths
        ], 201);
    }


}
