<template>
  <div class="divide-y">
    <div v-for="card in cards" class="flex flex-col gap-2 py-3">
      <VBreadcrumb>
        <VBreadcrumbItem :href="route('workspaces.show', card.project.workspace)">{{ card.project.workspace.title }}</VBreadcrumbItem>
        <VBreadcrumbItem :href="route('projects.show', card.project)">{{ card.project.title }}</VBreadcrumbItem>
      </VBreadcrumb>
      <VLink variant="secondary" :href="route('cards.show', card)">{{ card.title }}</VLink>
      <div class="flex items-center gap-2">
        <div v-for="field in card.project.fields">
          <VFieldWrapper :field="{ field, ...card.customFields[field.handle] }" :card="card" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { VLink, VFieldWrapper, VBreadcrumb, VBreadcrumbItem } from 'taskday';
const props = defineProps<{ cards: Card[] }>()
</script>
2
