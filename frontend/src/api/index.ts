import axios, {type AxiosResponse} from "axios";
import type {
    TCreateMealOrderPayload,
    TCreateMealOrderResponse,
    TDishesResponse,
    TMealCategoriesResponse,
    TRestaurantsResponse
} from "src/type";

const BASE_URL = 'http://127.0.0.1:8000/api'
const request = axios.create({
    baseURL: BASE_URL,
    timeout: 3000, // 3s
    headers: {
        "Content-type": "application/json"
    }
})

export function getMealCategories(query = null): Promise<AxiosResponse<TMealCategoriesResponse>> {
    return request({
        url: `/meal-categories`,
        method: 'get',
        params: query
    });
}

export function getRestaurantByMealCategories(mealCategoryId: number | null, query = null): Promise<AxiosResponse<TRestaurantsResponse>> {
    return request({
        url: `/meal-categories/${mealCategoryId}/restaurants`,
        method: 'get',
        params: query
    });
}

export function getDishesForSelection(mealCategoryId: number | null, restaurantId: number | null, query = null): Promise<AxiosResponse<TDishesResponse>> {
    return request({
        url: `/meal-categories/${mealCategoryId}/${restaurantId}/dishes`,
        method: 'get',
        params: query
    });
}

export function createMealOrder(order: TCreateMealOrderPayload): Promise<TCreateMealOrderResponse> {
    return request({
        url: `/meal-orders`,
        method: 'post',
        data: order
    });
}