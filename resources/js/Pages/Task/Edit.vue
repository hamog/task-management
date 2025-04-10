<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/tasks">Tasks</Link>
      <span class="text-indigo-400 font-medium">/</span> Edit
      {{ form.name }}
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full" label="Title" />
          <textarea-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full" label="Description" />
          <date-input v-model="form.started_at" :error="form.errors.started_at" class="pb-8 pr-6 w-full lg:w-1/2" label="Started At" />
          <date-input v-model="form.finished_at" :error="form.errors.finished_at" class="pb-8 pr-6 w-full lg:w-1/2" label="Finished At" />
          <select-input v-model="form.status" :error="form.errors.status" class="pb-8 pr-6 w-full" label="Status">
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
            <option value="incomplete">Incomplete</option>
          </select-input>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Task</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Task</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import DateInput from '@/Shared/DateInput.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
    DateInput
  },
  layout: Layout,
  props: {
    task: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        title: this.task.title,
        description: this.task.description,
        started_at: this.task.started_at,
        finished_at: this.task.finished_at,
        status: this.task.status
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/user/tasks/${this.task.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this task?')) {
        this.$inertia.delete(`/user/tasks/${this.task.id}`)
      }
    },
  },
}
</script>
