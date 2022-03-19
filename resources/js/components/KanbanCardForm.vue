<template>
  <form @submit.prevent="save">
    <VFormInput label="" placeholder="New card..." v-model="form.title" />
  </form>
</template>

<script setup lang="ts">
import {VFormInput, useCardForm} from 'taskday';

const props = defineProps<{
  status: { id: number, name: string, color: string },
  project: Project,
}>()

const { form, store } = useCardForm();

function save() {
  form.fields = {
    'status': props.status.color
  }

  store(props.project)
}
</script>
