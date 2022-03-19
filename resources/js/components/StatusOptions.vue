<template>
  <div>
    <div class="space-y-3" v-for="status in (form.options ?? [])" :key="status.id">
      <div class="flex items-center justify-between space-x-2 p-2 border">
        <div>
          <div class="text-sm font-semibold">{{ status.name }}</div>
          <div class="text-sm">{{ status.color }}</div>
        </div>
        <VButton
          variant="base"
          class="w-5 h-5 rounded bg-gray-200 dark:bg-gray-700"
          type="submit"
          @click.prevent="remove(status)"
        >&times;</VButton>
      </div>
    </div>

    <div class="mt-4 space-y-4">
      <div class="flex items-center space-x-4">
        <VFormInput label="Name" v-model="newStatus.name" />
        <VFormSelect label="Color" v-model="newStatus.color">
          <option value="blue">blue</option>
          <option value="yellow">yellow</option>
          <option value="purple">purple</option>
          <option value="red">red</option>
          <option value="green">green</option>
          <option value="teal">teal</option>
          <option value="gray">gray</option>
        </VFormSelect>
      </div>

      <VButton type="button" @click.prevent="add">Add</VButton>
    </div>
  </div>
</template>

<script lang="ts">
//@ts-ignore
import { VButton, VFormInput, VFormSelect } from 'taskday';
import { defineComponent, onMounted, reactive } from 'vue';

interface Option {
  id: string;
  name: string;
  color: string;
}

export default defineComponent({
  components: {
    VButton,
    VFormInput,
    VFormSelect,
  },
  props: {
    form: {
      type: Object,
      required: true
    },
  },
  setup(props) {
    const newStatus = reactive({
      name: 'New',
      color: 'blue'
    });

    onMounted(() => {
      props.form.options = props.form.options || [];

      if (Object.keys(props.form.options).length === 0) {
        props.form.options = [];
      }
    })

    function remove(status: Option) {
      props.form.options = props.form.options.filter((s: Option) => s.name != status.name);
    }

    function add() {
      console.log(props.form.options);
      props.form.options.push({ ...newStatus })
    }

    return { newStatus, remove, add }
  }
});
</script>
