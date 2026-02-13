<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\QuotationService;
use App\Http\Resources\QuotationResource;
use App\Http\Requests\StoreQuotationRequest; // Import StoreQuotationRequest
use App\Http\Requests\UpdateQuotationRequest; // Import UpdateQuotationRequest
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use App\Quotation; // Import the Quotation model

class QuotationController extends Controller
{
    protected $quotationService;

    public function __construct(QuotationService $quotationService)
    {
        $this->quotationService = $quotationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Quotation::class); // Authorize viewing any quotations
        try {
            $quotations = $this->quotationService->getAllQuotations();
            return QuotationResource::collection($quotations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve quotations: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotationRequest $request) // Use StoreQuotationRequest
    {
        $this->authorize('create', Quotation::class); // Authorize creating a quotation
        try {
            $quotation = $this->quotationService->createQuotation($request->validated()); // Use validated data
            return new QuotationResource($quotation);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create quotation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $quotation = $this->quotationService->getQuotationById($id);
            if (!$quotation) {
                throw new ModelNotFoundException('Quotation not found.');
            }
            $this->authorize('view', $quotation); // Authorize viewing this specific quotation
            return new QuotationResource($quotation);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve quotation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotationRequest $request, string $id) // Use UpdateQuotationRequest
    {
        try {
            // Fetch the quotation before authorizing to pass to the policy
            $quotation = $this->quotationService->getQuotationById($id);
            if (!$quotation) {
                throw new ModelNotFoundException('Quotation not found.');
            }
            $this->authorize('update', $quotation); // Authorize updating this specific quotation
            $quotation = $this->quotationService->updateQuotation($quotation->id, $request->validated()); // Use validated data
            return new QuotationResource($quotation);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update quotation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Fetch the quotation before authorizing to pass to the policy
            $quotation = $this->quotationService->getQuotationById($id);
            if (!$quotation) {
                throw new ModelNotFoundException('Quotation not found.');
            }
            $this->authorize('delete', $quotation); // Authorize deleting this specific quotation

            $deleted = $this->quotationService->deleteQuotation($quotation->id); // Use fetched ID
            if (!$deleted) {
                // This case should ideally not be reached if authorize passed and model was found
                return response()->json(['message' => 'Failed to delete quotation despite authorization.'], 500);
            }
            return response()->json(['message' => 'Quotation deleted successfully.'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete quotation: ' . $e->getMessage()], 500);
        }
    }
}
