<script setup lang="ts">
import { ref } from 'vue'
import type { TMealOrderForm } from 'src/type'

const emit = defineEmits(['nextStep', 'previousStep'])
const props = defineProps<{
  restaurants: { id: number | null; name: string }[] | undefined
}>()

const formData = defineModel<TMealOrderForm>('formData', { required: true })
const formRef = ref()

async function onNext() {
  try {
    await formRef.value.validate()
    emit('nextStep')
  } catch (error) {
    console.log('error', error)
  }
}
</script>

<template>
  <a-form ref="formRef" :model="formData">
    <a-form-item
      name="restaurant_id"
      label="Please select a Restaurant"
      :rules="[{ required: true, message: 'The Restaurant is required' }]"
    >
      <a-select ref="select" v-model:value="formData.restaurant_id" style="width: 200px">
        <a-select-option v-for="item in props.restaurants" :value="item.id"
          >{{ item.name }}
        </a-select-option>
      </a-select>
    </a-form-item>
    <a-flex :justify="'space-between'" class="btn-section">
      <a-button @click="emit('previousStep')">Previous</a-button>
      <a-form-item>
        <a-button type="primary" html-type="submit" @click="onNext">Next</a-button>
      </a-form-item>
    </a-flex>
  </a-form>
</template>

<style scoped></style>
