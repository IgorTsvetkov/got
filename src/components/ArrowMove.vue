<template>
  <div class="position-absolute w-100 h-100 z-index-1000 d-flex opacity-8" :class="arrowClass">
    <div v-if="isArrowFinish" class="arrow-move" :style="arrowStyles">
      <img src="/web/images/arrow_move.svg" />
    </div>
    <div v-else class="arrow-content bg-warning" :style="arrowStyles"></div>
  </div>
</template>

<script>
export default {
  props: {
    position: {
      type: Number,
      required: true,
    },
    isArrowFinish: {
      type: Boolean,
      default: false,
    },
    isStartCell: {
      type: Boolean,
      default: false,
    },
    index:{
      type: Number,
      default:0,
    }
  },
  computed: {
    arrowStyles(){
      return{
        "animation-delay":this.index*0.4+"s",
      }
    },
    arrowClass() {
      let relatedPosition = this.position;
      switch (true) {
        case relatedPosition > 0 && relatedPosition < 15:
          return "arrow-line-to-right";
        case relatedPosition > 15 && relatedPosition < 20:
          return "arrow-line-to-bottom";
        case relatedPosition > 20 && relatedPosition < 35:
          return "arrow-line-to-left";
        case relatedPosition > 35 && relatedPosition < 40:
          return "arrow-line-to-top";
        case relatedPosition == 0:
          return this.isStartCell?"arrow-corner-start-top-left":"arrow-corner-top-left";
        case relatedPosition == 15:
          return this.isStartCell?"arrow-corner-start-top-right":"arrow-corner-top-right";
        case relatedPosition == 20:
          return this.isStartCell?"arrow-corner-start-bottom-right":"arrow-corner-bottom-right";
        case relatedPosition == 35:
          return this.isStartCell?"arrow-corner-start-bottom-left":"arrow-corner-bottom-left";
        default:
          throw new Error("unpossible value");
      }
    },
  },
};
</script>

<style scoped>
.arrow-content,.arrow-move{
  opacity: 1;
  animation: arrow 1000s infinite;
}
@keyframes arrow{
  0%{
    opacity: 0;
  }
  100%{
    opacity: 0;
  }
}
.arrow-line-to-bottom,
.arrow-line-to-top,
.arrow-corner-start-bottom-left,
.arrow-corner-start-top-right{
  justify-content: center;
}
.arrow-line-to-left,
.arrow-line-to-right,
.arrow-corner-start-bottom-right,
.arrow-corner-start-top-left {
  align-items: center;
}
.arrow-corner-start-top-left{
  justify-content:flex-end;
}
.arrow-corner-start-top-right{
    align-items:flex-end;

}
.arrow-line-to-right .arrow-content, 
.arrow-line-to-left .arrow-content {
  width: 100%;
  height: 1.5vw;
}
.arrow-corner-start-bottom-right .arrow-content,.arrow-corner-start-top-left .arrow-content {
  width: 50%;
  height: 1.5vw;
}
.arrow-corner-start-bottom-left .arrow-content,.arrow-corner-start-top-right .arrow-content {
  width: 1.5vw;
  height: 50%;
}
.arrow-line-to-bottom .arrow-content,
.arrow-line-to-top .arrow-content {
  height: 100%;
  width: 1.5vw;
}
/* CORNERS */
.arrow-corner-top-right .arrow-content::after,
.arrow-corner-top-left .arrow-content::after,
.arrow-corner-bottom-right .arrow-content::after,
.arrow-corner-bottom-left .arrow-content::after {
  content: "";
  position: absolute;
  background: #ffc107 !important;
  height: calc(50% + 0.75vw);
  width: 1.5vw;
}
.arrow-corner-bottom-right .arrow-content::after,
.arrow-corner-bottom-left .arrow-content::after {
  top: 0px;
}
/* RIGHT */
.arrow-corner-top-right .arrow-content::after,
.arrow-corner-bottom-right .arrow-content::after {
  margin-left: calc(50% - 0.75vw);
}
.arrow-corner-top-right,
.arrow-corner-top-left,
.arrow-corner-bottom-right,
.arrow-corner-bottom-left {
  align-items: center;
}
.arrow-corner-top-right .arrow-content,
.arrow-corner-bottom-right .arrow-content {
  height: 1.5vw;
  width: calc(50% + 0.75vw);
}
/* LEFT */
.arrow-corner-top-left,
.arrow-corner-bottom-left {
  justify-content: flex-end;
}
.arrow-corner-top-left .arrow-content,
.arrow-corner-bottom-left .arrow-content {
  height: 1.5vw;
  width: calc(50% + 0.75vw);
}
.arrow-corner-top-left .arrow-content::after,
.arrow-corner-bottom-left .arrow-content::after {
  margin-right: calc(50% - 0.75vw);
}
/* ARROW FINAL */
.arrow-move img {
  width: 100%;
  height: 4vw;
}
.arrow-line-to-right .arrow-move {
  transform: rotateZ(90deg);
}
.arrow-line-to-left .arrow-move {
  transform: rotateZ(-90deg);
}
.arrow-line-to-bottom .arrow-move {
  transform: rotateZ(-180deg);
}
.arrow-corner-top-left .arrow-move::after,
.arrow-corner-top-right .arrow-move::after,
.arrow-corner-bottom-left .arrow-move::after,
.arrow-corner-bottom-right .arrow-move::after {
  content: "";
  position: absolute;
  background: #ffc107 !important;
  height: calc(50% + 0.75vw);
  width: 1.5vw;
  left: 50%;
  transform: rotateZ(90deg) translateY(0.15vw) translateX(2.8vw);
}
.arrow-corner-top-left .arrow-move {
  transform: rotateZ(90deg) translateY(-2.2vw);
}
.arrow-corner-top-right .arrow-move {
  transform: rotateZ(180deg) translateY(-2.2vw);
}
.arrow-corner-bottom-right .arrow-move {
  transform: rotateZ(270deg) translateY(-2.2vw);
}
.arrow-corner-bottom-left .arrow-move {
  transform: rotateZ(0deg) translateY(-2.2vw);
}
</style>