<template v-if="text">
  <span>
    <span v-for="(textPart,index) in textParts" :key="index">
      {{textPart}}
      <img
        v-if="isBetweenArrayItems(index,textParts.length)"
        src="/web/images/money.svg"
        width="20px"
        height="20px"
      />
    </span>
  </span>
</template>

<script>
export default {
  props: {
    text: {
      type: String,
      default: "",
    },
  },
  computed: {
    textParts() {
      return this.text.split("$");
    },
  },
  methods: {
    isBetweenArrayItems(index, length) {
      return index + 1 < length;
    },
  },
};
</script>

<style scoped>
</style>