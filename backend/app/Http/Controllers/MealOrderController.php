<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MealOrderController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['dishes', 'mealCategory', 'restaurant'])
            ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }

    /**
     * @param StoreMealOrderRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreMealOrderRequest $request): JsonResponse
    {
        $input = $request->all();
        try {
            DB::beginTransaction();

            $order = Order::query()->create(['user_id' => 1, ...$input]);

            $dishes = array_map(function ($item){
                return ['dish_id' => $item['id'], 'quantity' => $item['quantity']];
            }, $input['dishes']);
            $order->dishOrders()->createMany($dishes);

            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return response()->json(['success' => true], Response::HTTP_CREATED);
    }
}
