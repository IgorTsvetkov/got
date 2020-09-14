<template>
  <div
    class="position-absolute w-100 h-100 z-index-1000 d-flex"
    :class="arrowClass"
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
  computed: {
    arrowClass() {
      switch (true) {
        case this.position == 0:
          return "arrow-corner-top-left";
        case this.position > 0 && this.position < 15:
          return "arrow-line-to-right";
        case this.position == 15:
          return "arrow-corner-top-right";
        case this.position > 15 && this.position < 20:
          return "arrow-line-to-top";
        case this.position == 20:
          return "arrow-corner-bot-right";
        case this.position > 20 && this.position < 35:
          return "arrow-line-to-left";
        case this.position == 35:
          return "arrow-corner-bot-left";
        case this.position > 35 && this.position < 40:
          return "arrow-line-to-top";
        default:
          throw new Error("unpossible value");
      }
    },
  },
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
.arrow-corner-top-right .arrow-content::after,
.arrow-corner-top-left .arrow-content::after,
.arrow-corner-bot-right .arrow-content::after,
.arrow-corner-bot-left .arrow-content::after {
  content: "";
  position: absolute;
  background: #ffc107 !important;
  height: calc(50% + 1vw);
  width: 2vw;
}
.arrow-corner-bot-right .arrow-content::after,
.arrow-corner-bot-left .arrow-content::after {
  top: 0px;
}
/* RIGHT */
.arrow-corner-top-right .arrow-content::after,
.arrow-corner-bot-right .arrow-content::after {
  margin-left: calc(50% - 1vw);
}
.arrow-corner-top-right,
.arrow-corner-top-left,
.arrow-corner-bot-right,
.arrow-corner-bot-left {
  align-items: center;
}
.arrow-corner-top-right .arrow-content,
.arrow-corner-bot-right .arrow-content {
  height: 2vw;
  width: calc(50% + 1vw);
}
/* LEFT */
.arrow-corner-top-left,
.arrow-corner-bot-left {
  justify-content: flex-end;
}
.arrow-corner-top-left .arrow-content,
.arrow-corner-bot-left .arrow-content {
  height: 2vw;
  width: calc(50% + 1vw);
}
.arrow-corner-top-left .arrow-content::after,
.arrow-corner-bot-left .arrow-content::after {
  margin-right: calc(50% - 1vw);
}
</style>