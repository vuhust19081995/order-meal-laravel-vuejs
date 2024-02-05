// Types for http
export type THttpResponse<T, E> = {
    success: boolean,
    data: T,
    errors: E
}

export type TMealCategoriesResponse = THttpResponse<{ id: number; name: string }[], any>
export type TRestaurantsResponse = THttpResponse<{ id: number; name: string }[], any>
export type TDishesResponse = THttpResponse<{ id: number; name: string }[], any>
export type TCreateMealOrderResponse = THttpResponse<any, any>
export type TMealOrder = {
    meal_category: {
        id: number;
        name: string
    };
    restaurant: {
        id: number;
        name: string
    };
    number_of_people: number;
    dishes: { id: number; name: string; extra: { quantity: number } }[]
}
export type TGetMealOrderListResponse = THttpResponse<TMealOrder[], any>

export type TCreateMealOrderPayload = {
    meal_category_id: number;
    number_of_people: number;
    restaurant_id: number;
    dishes: { id: number; quantity: number }[]
}

// Other Types
export type TDish = { id: number | null; name?: string; quantity: number };

export type TMealOrderForm = {
    meal_category_id: number | null;
    number_of_people: number;
    restaurant_id: number | null;
    dishes: TDish[];
}

export type TPreviewData = {
    meal_category_name: string;
    restaurant_name: string;
    number_of_people: number;
    dishes: TDish[]
}