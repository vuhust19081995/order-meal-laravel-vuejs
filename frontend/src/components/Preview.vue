<script setup lang="ts">
import type { TPreviewData } from '@/type'

const props = defineProps<{ previewData: TPreviewData }>()
const emit = defineEmits(['onSubmit', 'previousStep'])
</script>

<template>
  <a-flex>
    <a-col :span="8"><h3>Meal:</h3></a-col>
    <a-col :span="16">{{ props.previewData.meal_category_name }}</a-col>
  </a-flex>
  <a-flex>
    <a-col :span="8"><h3>No of People:</h3></a-col>
    <a-col :span="16">{{ props.previewData.number_of_people }}</a-col>
  </a-flex>
  <a-flex>
    <a-col :span="8"><h3>Restaurant:</h3></a-col>
    <a-col :span="16">{{ props.previewData.restaurant_name }}</a-col>
  </a-flex>
  <a-flex>
    <a-col :span="8"><h3>Dishes:</h3></a-col>
    <a-col :span="16">
      <p v-for="dish in props.previewData.dishes">{{ dish.name }} - {{ dish.quantity }}</p>
    </a-col>
  </a-flex>
  <a-flex class="btn-section" :justify="'space-between'">
    <a-button @click="emit('previousStep')">Previous</a-button>
    <a-button type="primary" @click="emit('onSubmit')">Submit</a-button>
  </a-flex>
</template>

<style scoped>
h3 {
  font-weight: bold;
}
</style>
