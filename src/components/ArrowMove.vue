<template>
  <div
    v-if="position==0"
    class="position-relative position-absolute w-100 h-100 z-index-1000 d-flex arrow-corner-top-left"
  >
    <div class="arrow-content bg-warning"></div>
  </div>
</template>

<script>
export default {
  props: {
    position: {
      type: Number,
      required: true,
    },
  },
  //   computed: {
  //       arrowType() {

  //       }
  //   },
};
</script>

<style scoped>
.arrow-line-to-bottom,
.arrow-line-to-top {
  justify-content: center;
}
.arrow-line-to-left,
.arrow-line-to-right {
  align-items: center;
}
.arrow-line-to-right .arrow-content,
.arrow-line-to-left .arrow-content {
  width: 100%;
  height: 2vw;
}
.arrow-line-to-bottom .arrow-content,
.arrow-line-to-top .arrow-content {
  height: 100%;
  width: 2vw;
}
/* CORNERS */
.arrow-corner-top-right .arrow-content::after,.arrow-corner-top-left .arrow-content::after{
    content: "";
    position: absolute;
    background: #ffc107 !important;
    height: calc(50% + 1vw);
    width:2vw;
}
/* TOP RIGHT */
.arrow-corner-top-right .arrow-content::after{
    margin-left:calc(50% - 1vw);
}
.arrow-corner-top-right,.arrow-corner-top-left{
  align-items: center;
}
.arrow-corner-top-right .arrow-content{
    height: 2vw;
    width: calc(50% + 1vw);
}

/* TOP LEFT */
.arrow-corner-top-left{
    justify-content: flex-end;
}
.arrow-corner-top-left .arrow-content{
    height: 2vw;
    width: calc(50% + 1vw);
}
.arrow-corner-top-left .arrow-content::after{
    margin-right:calc(50% - 1vw);}
</style>