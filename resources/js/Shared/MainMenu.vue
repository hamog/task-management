<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/user">
        <icon name="dashboard" class="mr-2 w-4 h-4" :class="isUrl('user') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('user') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/user/tasks">
        <icon name="office" class="mr-2 w-4 h-4" :class="isUrl('user/tasks') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('user/tasks') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Tasks</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
