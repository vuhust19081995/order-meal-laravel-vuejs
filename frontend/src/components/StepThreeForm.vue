<script setup lang="ts">
import { ref } from 'vue'
import type { TMealOrderForm } from 'src/type'

const emit = defineEmits(['nextStep', 'previousStep'])
const props = defineProps<{ dishesForSelection: { id: number; name: string }[] | undefined }>()
const formData = defineModel<TMealOrderForm>('formData', { required: true })
const formRef = ref()
let isTotalDishesInValid = ref(false)
let isDuplicateDish = ref(false)

function addMoreDish() {
  formData.value.dishes = [...formData.value.dishes, { dish_id: 0, name: '', quantity: 1 }]
}

function onChangeDish(index, id) {
  formData.value.dishes[index].name =
    props.dishesForSelection?.find((item) => item.id === id)?.name ?? ''
}

async function onNext() {
  try {
    // Validate form
    await formRef.value.validate()

    // Check whether total dishes is valid or dish is duplicate
    isTotalDishesInValid.value = false
    isDuplicateDish.value = false
    const set = new Set()
    const dishes = formData.value.dishes
    let totalDish = 0
    for (const dish of dishes) {
      totalDish += dish.quantity
      set.add(dish.id)
    }
    if (totalDish < formData.value.number_of_people) {
      isTotalDishesInValid.value = true
      return
    }

    if (set.size !== formData.value.dishes.length) {
      isDuplicateDish.value = true
      return
    }

    emit('nextStep')
  } catch (error) {
    console.log('error', error)
  }
}
</script>

<template>
  <a-flex style="margin-bottom: 1rem" vertical>
    <a-alert
      message="Error"
      description="Total dishes must be equal or greater the number of people and a maximum of 10 is allowed!"
      type="error"
      closable
      v-show="isTotalDishesInValid"
    />

    <a-alert
      message="Error"
      description="Can not choose same dish"
      type="error"
      closable
      v-show="isDuplicateDish"
    />
  </a-flex>

  <a-row style="margin-bottom: 1rem">
    <a-col :span="12">Please select a dish</a-col>
    <a-col :span="12">Please enter no. of servings</a-col>
  </a-row>
  <a-form ref="formRef" :model="formData">
    <a-row v-for="(selectedDish, index) in formData.dishes">
      <a-col :span="12">
        <a-form-item
          :name="['dishes', index, 'id']"
          :rules="[{ required: true, message: 'Please choose a dish' }]"
        >
          <a-select
            @change="onChangeDish(index, $event)"
            v-model:value="selectedDish.id"
            style="width: 150px"
          >
            <a-select-option v-for="item in props.dishesForSelection" :value="item.id"
              >{{ item.name }}
            </a-select-option>
          </a-select>
        </a-form-item>
      </a-col>
      <a-col :span="12">
        <a-form-item
          :name="['dishes', index, 'quantity']"
          :rules="[{ required: true, message: 'This field is required' }]"
        >
          <a-input-number type="number" v-model:value="selectedDish.quantity" :min="1" :max="10" />
        </a-form-item>
      </a-col>
    </a-row>
    <a-button @click="addMoreDish()" type="primary" shape="circle"> + </a-button>
    <a-flex :justify="'space-between'" class="btn-section">
      <a-button @click="emit('previousStep')">Previous</a-button>
      <a-form-item>
        <a-button type="primary" @click="onNext">Next</a-button>
      </a-form-item>
    </a-flex>
  </a-form>
</template>

<style scoped></style>
