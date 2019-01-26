<template>
  <div id="myApp">
    <v-select :value.sync="selected" :options="options"></v-select>
  </div>
</template>
<script>
import vSelect from "vue-select"
  export default {
    components: {vSelect},

    data() {
      return {
        selected: null,
        options: ['foo','bar','baz']
      }
    }
  }
</script>
