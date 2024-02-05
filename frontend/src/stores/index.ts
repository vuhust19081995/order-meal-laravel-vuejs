import {ref} from "vue";
import {defineStore} from "pinia";

export const useAppStore = defineStore('app', () => {
  let isLoading = ref<boolean>(false)

  function setIsLoading(value: boolean) {
    isLoading.value = value
  }

  return {isLoading, setIsLoading}
})