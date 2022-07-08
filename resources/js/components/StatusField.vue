<template>
  <span>
    <span v-if="readonly">
      <span :class="{
        'bg-gray-100 dark:bg-gray-400 text-gray-600 dark:text-gray-400 dark:bg-opacity-20': color === 'gray',
        'bg-red-100 dark:bg-red-400 text-red-600 dark:text-red-400 dark:bg-opacity-20': color === 'red',
        'bg-green-100 dark:bg-green-400 text-green-600 dark:text-green-400 dark:bg-opacity-20': color === 'green',
        'bg-yellow-100 dark:bg-yellow-400 text-yellow-600 dark:text-yellow-400 dark:bg-opacity-20': color === 'yellow',
        'bg-blue-100 dark:bg-blue-400 text-blue-600 dark:text-blue-400 dark:bg-opacity-20': color === 'blue',
        'bg-purple-100 dark:bg-purple-400 text-purple-600 dark:text-purple-400 dark:bg-opacity-20': color === 'purple',
      }">
        {{ name }}
      </span>
    </span>
    <VDropdown v-else>
        <VDropdownButton
          :class="{
            'bg-gray-100 dark:bg-gray-400 text-gray-600 dark:text-gray-400 dark:bg-opacity-20': color === 'gray',
            'bg-red-100 dark:bg-red-400 text-red-600 dark:text-red-400 dark:bg-opacity-20': color === 'red',
            'bg-green-100 dark:bg-green-400 text-green-600 dark:text-green-400 dark:bg-opacity-20': color === 'green',
            'bg-yellow-100 dark:bg-yellow-400 text-yellow-600 dark:text-yellow-400 dark:bg-opacity-20': color === 'yellow',
            'bg-blue-100 dark:bg-blue-400 text-blue-600 dark:text-blue-400 dark:bg-opacity-20': color === 'blue',
            'bg-purple-100 dark:bg-purple-400 text-purple-600 dark:text-purple-400 dark:bg-opacity-20': color === 'purple',
          }"
          class="rounded transition font-medium h-full text-sm whitespace-nowrap w-auto px-4 flex items-center justify-center"
        >{{ name }}</VDropdownButton>

      <VDropdownItems>
        <VDropdownItem
          @click="update(option)"
          v-for="option in options"
          :key="option.id"
        >
          <span>{{ option.name }}</span>
        </VDropdownItem>
      </VDropdownItems>
    </VDropdown>
  </span>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue';

//@ts-ignore
import { useField, VDropdown, VDropdownButton, VDropdownItems, VDropdownItem } from 'taskday';

interface Option {
  color: string,
  name: string
}

export default defineComponent({
  props: {
    readonly: {
      type: Boolean,
      default: false
    },
    value: {
      type: String,
      required: false,
    }
  },
  components: {
    VDropdown,
    VDropdownButton,
    VDropdownItems,
    VDropdownItem,
  },
  setup(props) {
    const { state, options, onChange } = useField();

    const current = computed(() => {
      if (props.readonly) {
        return options.find((option: Option) => option.color === props.value) ?? options[0];
      }

      return options.find((option: Option) => option.color === state.value) ?? options[0];
    });

    const name = computed(() => {
      return current.value?.name;
    });

    const color = computed(() => {
      return current.value?.color;
    });

    function update(option: Option) {
      state.value = `${option.color}`;
      onChange();
    }

    return { current, color, name, options, update };
  },
});
</script>
