<script setup lang="ts">
import { computed, type ComputedRef, ref, watch } from 'vue'
import StepOneForm from '@/components/StepOneForm.vue'
import StepTwoForm from '@/components/StepTwoForm.vue'
import StepThreeForm from '@/components/StepThreeForm.vue'
import Preview from '@/components/Preview.vue'
import {
  createMealOrder,
  getMealOrderList,
  getDishesForSelection,
  getMealCategories,
  getRestaurantByMealCategories
} from '@/api'
import { message } from 'ant-design-vue'
import type { TMealOrder, TMealOrderForm, TPreviewData } from 'src/type'
import type { TCreateMealOrderPayload } from 'src/type'

const currentStep = ref(0)
const steps = [
  {
    title: 'Step 1'
  },
  {
    title: 'Step 2'
  },
  {
    title: 'Step 3'
  },
  {
    title: 'Preview'
  }
]
const columns = [
  {
    title: 'Meal',
    dataIndex: ['meal_category', 'name']
  },
  {
    title: 'Number Of People',
    dataIndex: 'number_of_people'
  },
  {
    title: 'Restaurant',
    dataIndex: ['restaurant', 'name']
  },
  {
    title: 'Dishes',
    dataIndex: 'dishes'
  }
]
const initFormData = {
  meal_category_id: null,
  number_of_people: 1,
  restaurant_id: null,
  dishes: [
    {
      id: null,
      name: '',
      quantity: 1
    }
  ]
}
const orderList = ref<TMealOrder[]>([])
let mealCategories = ref<
  {
    id: number
    name: string
  }[]
>()
const restaurants = ref<
  {
    id: number
    name: string
  }[]
>()
const dishesForSelection = ref<
  {
    id: number
    name: string
  }[]
>()

const formData = ref<TMealOrderForm>({ ...initFormData })

const previewData: ComputedRef<TPreviewData> = computed(() => {
  return {
    meal_category_name:
      mealCategories.value?.find((item) => item.id === formData.value.meal_category_id)?.name ?? '',
    restaurant_name:
      restaurants.value?.find((item) => item.id === formData.value.restaurant_id)?.name ?? '',
    number_of_people: formData.value.number_of_people,
    dishes: formData.value.dishes
  }
})

async function fetchData() {
  const [mcResponse, olResponse] = await Promise.all([getMealCategories(), getMealOrderList()])
  mealCategories.value = mcResponse.data.data ?? []
  orderList.value = olResponse.data.data ?? []
}

fetchData()

async function submitOrder() {
  try {
    const payload: TCreateMealOrderPayload = {
      meal_category_id: formData.value.meal_category_id as number,
      restaurant_id: formData.value.restaurant_id as number,
      number_of_people: formData.value.number_of_people,
      dishes: formData.value.dishes.map((item) => ({
        id: item.id as number,
        quantity: item.quantity
      }))
    }
    const response = await createMealOrder(payload)
    if (response) {
      message.success('Create Meal Order successfully!')
      formData.value = { ...initFormData }
      currentStep.value = 0
      const mealOrderList = await getMealOrderList()
      orderList.value = mealOrderList.data.data ?? []
    }
  } catch (e) {
    console.log(e)
  }
}

watch(
  () => formData.value.meal_category_id,
  async (newValue, oldValue) => {
    if (newValue === null) {
      return
    }
    const response = await getRestaurantByMealCategories(newValue)
    restaurants.value = response.data.data ?? []
  }
)

watch(
  () => formData.value.restaurant_id,
  async (newValue, oldValue) => {
    if (newValue === null) {
      return
    }
    const response = await getDishesForSelection(formData.value.meal_category_id, newValue)
    dishesForSelection.value = response.data.data ?? []
    formData.value.dishes = [
      {
        id: null,
        name: '',
        quantity: 1
      }
    ]
  }
)
</script>

<template>
  <a-flex class="order-container" vertical>
    <a-card title="Order Meal">
      <div class="steps">
        <a-steps :current="currentStep" :items="steps"> </a-steps>
      </div>
      <div class="step-content">
        <StepOneForm
          v-if="currentStep === 0"
          :meal-categories="mealCategories"
          v-model:form-data="formData"
          @next-step="currentStep++"
        >
        </StepOneForm>
        <StepTwoForm
          v-if="currentStep === 1"
          v-model:form-data="formData"
          :restaurants="restaurants"
          @next-step="currentStep++"
          @previous-step="currentStep--"
        >
        </StepTwoForm>
        <StepThreeForm
          v-if="currentStep === 2"
          :dishes-for-selection="dishesForSelection"
          v-model:form-data="formData"
          @next-step="currentStep++"
          @previous-step="currentStep--"
        >
        </StepThreeForm>
        <Preview
          v-if="currentStep === 3"
          @previous-step="currentStep--"
          @on-submit="submitOrder"
          :preview-data="previewData"
        >
        </Preview>
      </div>
    </a-card>

    <a-card class="order-list" title="Order List">
      <a-table :columns="columns" :data-source="orderList">
        <template #bodyCell="{ column, record }">
          <template v-if="column.dataIndex === 'dishes'">
            <ul>
              <li v-for="dish in record.dishes">{{ dish.name }} - {{ dish.extra.quantity }}</li>
            </ul>
          </template>
        </template>
      </a-table>
    </a-card>
  </a-flex>
</template>

<style scoped>
@media only screen and (max-width: 576px) {
  .order-container {
    max-width: 95%;
  }
}

@media only screen and (min-width: 576px) {
  .order-container {
    max-width: 90%;
  }
}

@media only screen and (min-width: 1140px) {
  .order-container {
    max-width: 60%;
  }
}

.order-container {
  margin: 3rem auto 0 auto;
}

.steps {
  margin-bottom: 3rem;
}

.step-content {
  margin-bottom: 3rem;
}

.order-list {
  margin-top: 3rem;
}
</style>

<style>
.btn-section {
  margin-top: 2rem;
}
</style>
