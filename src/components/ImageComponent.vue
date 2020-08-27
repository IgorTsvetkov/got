<template>
  <div class="cell-wrapper d-flex flex-column justify-content-center">
      <img :src="this.src" alt />
  </div>
</template>

<script>
export default {
  props: {
    src: {
      type: String,
      default: "",
    },
  },
};
</script>

<style scoped>
.cell-wrapper {
  width: 100%;
  height: auto;
}
.cell-wrapper img,
.cell-center img {
  width: inherit;
}

.height {
  /* font-size: 1.3em; */
  font-size: 1.2vw;
}
</style>