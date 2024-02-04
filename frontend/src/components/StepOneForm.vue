<script setup lang="ts">

import {ref} from "vue";
import type {Rule} from "ant-design-vue/es/form";
import type {TMealOrderForm} from "src/type";

const emit = defineEmits(['nextStep'])
const props = defineProps<{
  mealCategories: {
    id: number,
    name: string
  }[] | undefined
}>()
const formData = defineModel<TMealOrderForm>('formData', {required: true})
const rules: Record<string, Rule[]> = {
  meal_category_id: [{required: true, message: 'Meal is required'}],
  number_of_people: [{required: true, message: 'Number of people is required'}]
};
const formRef = ref();

async function onNext() {
  try {
    await formRef.value.validate()
    emit('nextStep')
  } catch (error) {
    console.log('error', error);
  }
}


</script>

<template>
  <a-form ref="formRef" :model="formData" :rules="rules">
    <a-form-item
        name="meal_category_id"
        label="Please select a meal"
    >
      <a-select
          ref="select"
          v-model:value="formData.meal_category_id"
          style="width: 120px"
      >
        <a-select-option v-for="item in props.mealCategories" :value="item.id">{{
            item.name
          }}
        </a-select-option>
      </a-select>
    </a-form-item>
    <a-form-item name="number_of_people" label="Please enter number of people">
      <a-input-number type="number" v-model:value="formData.number_of_people" :min="1" :max="10"/>
    </a-form-item>
    <a-form-item class="btn-section">
      <a-button class="btn-next" type="primary" html-type="submit" @click="onNext">Next</a-button>
    </a-form-item>
  </a-form>
</template>

<style scoped>
.btn-next {
  display: block;
  margin-left: auto;
}
</style>