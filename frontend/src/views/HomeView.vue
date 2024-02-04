<script setup lang="ts">
import {computed, type ComputedRef, ref, watch} from "vue";
import StepOneForm from "@/components/StepOneForm.vue";
import StepTwoForm from "@/components/StepTwoForm.vue";
import StepThreeForm from "@/components/StepThreeForm.vue";
import Preview from "@/components/Preview.vue";
import {createMealOrder, getDishesForSelection, getMealCategories, getRestaurantByMealCategories} from "@/api";
import {message} from "ant-design-vue";
import type {TMealOrderForm, TPreviewData} from "src/type";
import type {TCreateMealOrderPayload} from "src/type";

const currentStep = ref(0);
const steps = [
  {
    title: 'Step 1',
  },
  {
    title: 'Step 2',
  },
  {
    title: 'Step 3',
  },
  {
    title: 'Preview',
  },
]
const initFormData = {
  meal_category_id: null,
  number_of_people: 1,
  restaurant_id: null,
  dishes: [
    {
      id: null,
      name: '',
      quantity: 1,
    },
  ]
}
let mealCategories = ref<{
  id: number,
  name: string
}[]>();
const restaurants = ref<{
  id: number,
  name: string
}[]>();
const dishesForSelection = ref<{
  id: number,
  name: string
}[]>()

const formData = ref<TMealOrderForm>({...initFormData})

const previewData: ComputedRef<TPreviewData> = computed(() => {
  return {
    meal_category_name: mealCategories.value?.find((item) => item.id === formData.value.meal_category_id)?.name ?? '',
    restaurant_name: restaurants.value?.find((item) => item.id === formData.value.restaurant_id)?.name ?? '',
    number_of_people: formData.value.number_of_people,
    dishes: formData.value.dishes
  }
})

async function fetchMealCategories() {
  const response = await getMealCategories()
  mealCategories.value = response.data.data ?? []
}

async function submitOrder() {
  try {
    const payload: TCreateMealOrderPayload = {
      meal_category_id: formData.value.meal_category_id as number,
      restaurant_id: formData.value.restaurant_id as number,
      number_of_people: formData.value.number_of_people,
      dishes: formData.value.dishes.map((item) => ({id: item.id as number, quantity: item.quantity})),
    };
    const response = await createMealOrder(payload);
    if (response) {
      message.success('Create Meal Order successfully!')
      formData.value = {...initFormData}
      currentStep.value = 0;
    }
  } catch (e) {
    console.log(e);
  }
}

watch(() => formData.value.meal_category_id, async (newValue, oldValue) => {
  if (newValue === null) {
    return
  }
  const response = await getRestaurantByMealCategories(newValue)
  restaurants.value = response.data.data ?? []
})

watch(() => formData.value.restaurant_id, async (newValue, oldValue) => {
  if (newValue === null) {
    return
  }
  const response = await getDishesForSelection(formData.value.meal_category_id, newValue)
  dishesForSelection.value = response.data.data ?? []
  formData.value.dishes = [
    {
      id: null,
      name: '',
      quantity: 1,
    },
  ]
})

fetchMealCategories();

</script>

<template>
  <a-card title="Order Meal" class="order-form">
    <div class="steps">
      <a-steps :current="currentStep"
               :items="steps">
      </a-steps>
    </div>
    <div class="step-content">
      <StepOneForm
          v-if="currentStep===0"
          :meal-categories="mealCategories"
          v-model:form-data="formData"
          @next-step="currentStep++"
      >
      </StepOneForm>
      <StepTwoForm
          v-if="currentStep===1"
          v-model:form-data="formData"
          :restaurants="restaurants"
          @next-step="currentStep++"
          @previous-step="currentStep--"
      >
      </StepTwoForm>
      <StepThreeForm
          v-if="currentStep===2"
          :dishes-for-selection="dishesForSelection"
          v-model:form-data="formData"
          @next-step="currentStep++"
          @previous-step="currentStep--"
      >

      </StepThreeForm>
      <Preview
          v-if="currentStep===3"
          @previous-step="currentStep--"
          @on-submit="submitOrder"
          :preview-data="previewData"
      >

      </Preview>
    </div>
  </a-card>

</template>

<style scoped>
@media only screen and (max-width: 576px) {
  .order-form {
    max-width: 95%;
  }
}

@media only screen and (min-width: 576px) {
  .order-form {
    max-width: 90%;
  }
}

@media only screen and (min-width: 1140px) {
  .order-form {
    max-width: 60%;
  }
}

.order-form {
  margin: 3rem auto 0;
}

.steps {
  margin-bottom: 3rem;
}

.step-content {
  margin-bottom: 3rem;
}
</style>

<style>
.btn-section {
  margin-top: 2rem;
}
</style>