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
use Tymon\JWTAuth\Facades\JWTAuth;

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
                'daily' => $validatedData['daily'] ?? null,
                'weekly' => $validatedData['weekly'] ?? null,
                'monthly' => $validatedData['monthly'] ?? null,
                'daily_with_driver' => $validatedData['daily_with_driver'] ?? null,
                'weekly_with_driver' => $validatedData['weekly_with_driver'] ?? null,
                'monthly_with_driver' => $validatedData['monthly_with_driver'] ?? null,
            ]);

            // Create car with new date columns
            $car = Car::create([
                'trademark' => $validatedData['trademark'] ?? null,
                'model' => $validatedData['model'] ?? null,
                'delivery' => $validatedData['delivery'] ?? null,
                'details' => $validatedData['details'] ?? null,
                'rent_id' => $rent->id,
                'insurance' => $validatedData['insurance'] ?? null,
                'KMs' => $validatedData['KMs'] ?? null,
                'deposit' => $validatedData['deposit'] ?? null,
                'min_age' => $validatedData['min_age'] ?? null,
                'category_id' => $validatedData['category_id'] ?? null,
                'date_of_manufacture' => $validatedData['date_of_manufacture'] ?? null,
                'registration_date' => $validatedData['registration_date'] ?? null,
            ]);

            // Create car features
            CarFeature::create([
                'gear' => $validatedData['gear'] ?? null,
                'engine' => $validatedData['engine'] ?? null,
                'color' => $validatedData['color'] ?? null,
                'seats' => $validatedData['seats'] ?? null,
                'doors' => $validatedData['doors'] ?? null,
                'luggage' => $validatedData['luggage'] ?? null,
                'sensors' => $validatedData['sensors'] ?? null,
                'bluetooth' => $validatedData['bluetooth'] ?? null,
                'gcc' => $validatedData['gcc'] ?? null,
                'camera' => $validatedData['camera'] ?? null,
                'lcd' => $validatedData['lcd'] ?? null,
                'safety' => $validatedData['safety'] ?? null,
                'radio' => $validatedData['radio'] ?? null,
                'Mb3_CD' => $validatedData['Mb3_CD'] ?? null,
                'car_id' => $car->id,
            ]);

            // Handle image uploads (if needed)
            // ...

            DB::commit();

            return response()->json([
                'message' => 'Car and related data created successfully',
                'id' => $car->id
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
                $query->where('is_main',true)->first();
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
            'date_of_manufacture' => 'sometimes|integer|min:1900|max:' . date('Y'),
            'registration_date' => 'sometimes|integer|min:1900|max:' . date('Y'),
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
            $car->update(array_merge($validatedData, [
                'date_of_manufacture' => $validatedData['date_of_manufacture'] ?? $car->date_of_manufacture,
                'registration_date' => $validatedData['registration_date'] ?? $car->registration_date,
            ]));

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
    public function addImage(Request $request)
    {
        $validatedData = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_main' => 'required|boolean',
        ]);

        if (!is_numeric($request->car_id) || $request->car_id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }

        // Check if the car exists (validation already ensures this)
        $car = Car::find($request->car_id);
        if (!$car) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }
        // Handle the image upload
        $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
        $imagePath = $request->file('image')->storeAs('car_images', $imageName, 'public');

        if ($validatedData['is_main']) {
            // Check if there's an existing main image for the car
            $mainImage = CarImage::where('car_id', $validatedData['car_id'])
                ->where('is_main', true)
                ->first();

            if ($mainImage) {
                // Update the existing main image
                Storage::disk('public')->delete($mainImage->image);
                $mainImage->image = $imagePath;
                $mainImage->save();

                return response()->json([
                    'message' => 'Main image updated successfully',
                    'image' => $mainImage,
                ], 200);
            }
        }

        // Create a new image record in the database
        $carImage = CarImage::create([
            'car_id' => $validatedData['car_id'],
            'image' => $imagePath,
            'is_main' => $validatedData['is_main'],
        ]);

        return response()->json([
            'message' => 'Image added successfully',
            'image' => $carImage,
        ], 201);
    }
    public function hide_unhide_car($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }

        $car = Car::where('id',$id)->first();
        if (!$car) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }
        $car->is_hidden = !$car->is_hidden;
        $car->save();
        return response()->json([
           'is_hidden' => $car->is_hidden
        ]);
    }
    public function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        $car = Car::find($id);
        if (!$car) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }
        $car->delete();
        return response()->json([
            'message' => 'car deleted successfully'
        ]);
    }
    /**
     * Display a paginated list of cars with main image.
     * * * @return \Illuminate\Http\JsonResponse
     */
    public function showCars()
    { // Retrieve cars where is_hidden is false and paginate results by 5
         $cars = Car::where('is_hidden', false)
             ->with(['images' => function($query)
             { $query->where('is_main', true);
             }])
             ->paginate(5);
         return response()->json([
             'cars' => $cars, ]
             );
    }
    /**
     * Display a paginated list of cars by category with main image.
     * * * @param int $category_id
     * * @return \Illuminate\Http\JsonResponse
     */
    public function showCarsByCategory_user($category_id)
    { // Retrieve cars where is_hidden is false and category_id matches, paginate results by 5
         $cars = Car::where('is_hidden', false)
             ->where('category_id', $category_id)
             ->with(['images' => function($query)
             { $query->where('is_main', true);
             }])
             ->paginate(10);
         return response()->json([
             'cars' => $cars, ]
         );
    }



}
