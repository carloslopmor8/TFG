<template>
  <div>
    <GChart
      :type="type"
      :options="options"
      :data="data"
    />
  </div>
</template>

<script>
import { GChart } from "vue-google-charts";

export default {
  name: "App",
  components: {
    GChart
  },
  props: {
    data: Array,
    type: String
  },
  data() {
    return {
      options: {
        width: 700,
        height: 400
      }
    };
  }
};
</script>
