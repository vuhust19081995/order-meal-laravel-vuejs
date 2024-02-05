<script setup lang="ts">
import { useAppStore } from '@/stores'

const appStore = useAppStore()
</script>

<template>
  <a-spin class="container" :spinning="appStore.isLoading">
    <RouterView />
  </a-spin>
</template>

<style scoped></style>
