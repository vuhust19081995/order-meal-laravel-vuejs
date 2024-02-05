import axios, { type AxiosResponse } from 'axios'
import type {
  TCreateMealOrderPayload,
  TCreateMealOrderResponse,
  TDishesResponse,
  TGetMealOrderListResponse,
  TMealCategoriesResponse,
  TRestaurantsResponse
} from 'src/type'
import { useAppStore } from '@/stores'
const BASE_URL = 'http://127.0.0.1:8000/api'
const request = axios.create({
  baseURL: BASE_URL,
  timeout: 3000, // 3s
  headers: {
    'Content-type': 'application/json'
  }
})

const store = useAppStore()
// Add a request interceptor
request.interceptors.request.use(
  function (config) {
    // Do something before request is sent
    store.setIsLoading(true)
    return config
  },
  function (error) {
    // Do something with request error
    store.setIsLoading(false)
    return Promise.reject(error)
  }
)

// Add a response interceptor
request.interceptors.response.use(
  function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    store.setIsLoading(false)
    return response
  },
  function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    store.setIsLoading(false)
    return Promise.reject(error)
  }
)

export function getMealCategories(query = null): Promise<AxiosResponse<TMealCategoriesResponse>> {
  return request({
    url: `/meal-categories`,
    method: 'get',
    params: query
  })
}

export function getRestaurantByMealCategories(
  mealCategoryId: number | null,
  query = null
): Promise<AxiosResponse<TRestaurantsResponse>> {
  return request({
    url: `/meal-categories/${mealCategoryId}/restaurants`,
    method: 'get',
    params: query
  })
}

export function getDishesForSelection(
  mealCategoryId: number | null,
  restaurantId: number | null,
  query = null
): Promise<AxiosResponse<TDishesResponse>> {
  return request({
    url: `/meal-categories/${mealCategoryId}/${restaurantId}/dishes`,
    method: 'get',
    params: query
  })
}

export function getMealOrderList(query?: any): Promise<TGetMealOrderListResponse> {
  return request({
    url: `/meal-orders`,
    method: 'get',
    params: query
  })
}

export function createMealOrder(order: TCreateMealOrderPayload): Promise<TCreateMealOrderResponse> {
  return request({
    url: `/meal-orders`,
    method: 'post',
    data: order
  })
}
