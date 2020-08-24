<template>
  <div class="d-flex figurine">
    <img height="100%" :src="hero.src" />
    <!-- <div class="bg-danger">position:{{player.position}}</div> -->
  </div>
</template>

<script>
export default {
    props: {
        hero: {
            type: Object,
            default: {}
        },
    },
};
</script>

<style scoped>
/*FIGURINE STYLES*/
.figurine {
  height: 40px;
  width: 40px;
  display: flex;
  width: inherit;
  flex-wrap: wrap;
  z-index: 1;
}
</style>