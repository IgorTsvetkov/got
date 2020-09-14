<template>
  <div class="cube-animation" :class="[numberClass,delayedAnimation?'animation':'',hide?'hide':'']">
    <div class="cube position-relative">
      <div
        class="front square border border-secondary d-flex justify-content-center align-items-center"
      >
        <div class="point"></div>
      </div>
      <div
        class="back square border border-secondary d-flex justify-content-center align-items-center"
      >
        <div class="d-flex w-75 h-75 flex-column align-items-center justify-content-between">
          <div class="d-flex w-100 justify-content-between">
            <div class="point"></div>
            <div class="point"></div>
          </div>
          <div class="d-flex w-100 justify-content-between">
            <div class="point"></div>
            <div class="point"></div>
          </div>
          <div class="d-flex w-100 justify-content-between">
            <div class="point"></div>
            <div class="point"></div>
          </div>
        </div>
      </div>
      <div
        class="left square border border-secondary d-flex justify-content-center align-items-center"
      >
        <div class="d-flex w-75 h-75 flex-column align-items-center justify-content-between">
          <div class="d-flex w-100 justify-content-between">
            <div class="point"></div>
            <div class="point"></div>
          </div>
          <div class="d-flex w-100 justify-content-center">
            <div class="point"></div>
          </div>
          <div class="d-flex w-100 justify-content-between">
            <div class="point"></div>
            <div class="point"></div>
          </div>
        </div>
      </div>
      <div
        class="right square border border-secondary d-flex justify-content-center align-items-center"
      >
        <div class="w-50 h-50 d-flex flex-column justify-content-between">
          <div class="align-self-end">
            <div class="point"></div>
          </div>
          <div>
            <div class="point"></div>
          </div>
        </div>
      </div>
      <div
        class="square top border border-secondary d-flex justify-content-center align-items-center"
      >
        <div class="w-50 h-50 d-flex flex-column justify-content-between">
          <div class="d-flex w-100 align-self-end justify-content-between">
            <div class="point"></div>
            <div class="point"></div>
          </div>
          <div class="d-flex w-100 justify-content-between">
            <div class="point"></div>
            <div class="point"></div>
          </div>
        </div>
      </div>
      <div
        class="square bottom border border-secondary d-flex justify-content-center align-items-center"
      >
        <div class="d-flex w-75 h-75 flex-column align-items-center justify-content-between">
          <div class="d-flex w-100 justify-content-start">
            <div class="point"></div>
          </div>
          <div class="d-flex w-100 justify-content-center">
            <div class="point"></div>
          </div>
          <div class="d-flex w-100 justify-content-end">
            <div class="point"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    number: {
      type: Number,
      default: 1,
    },
    isAnimate: {
      type: Boolean,
      default: 1,
    },
    delay: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      delayedAnimation: false,
      hide: false,
    };
  },
  computed: {
    numberClass() {
      switch (this.number) {
        case 1:
          return "one";
        case 2:
          return "two";
        case 3:
          return "three";
        case 4:
          return "four";
        case 5:
          return "five";
        case 6:
          return "six";
        default:
          return "one";
      }
    },
  },
  watch: {
    isAnimate(newValue, oldValue) {
      if (!newValue) {
        this.delayedAnimation = newValue;
        return;
      }
      this.hide = true;
      setTimeout(() => {
        this.delayedAnimation = newValue;
        this.hide = false;
        this.$emit("");
      }, this.delay);
    },
  },
};
</script>

<style scoped>
.cube {
  height: var(--length);
  width: var(--length);
  transform-style: preserve-3d;
}
.cube:hover > .square {
  cursor: pointer;
}
.cube > .square {
  background: white;
  height: var(--length);
  width: var(--length);
  position: absolute;
}
.square.front {
  transform: translateZ(calc(var(--length) / 2));
}
.square.back {
  transform: translateZ(calc(var(--length) / -2)) rotateY(180deg);
}
.square.left {
  transform: translateX(calc(var(--length) / -2)) rotateY(-90deg);
}
.square.right {
  transform: translateX(calc(var(--length) / 2)) rotateY(90deg);
}
.square.top {
  transform: translateY(calc(var(--length) / -2)) rotateX(90deg);
}
.square.bottom {
  transform: translateY(calc(var(--length) / 2)) rotateX(-90deg);
}
body .point {
  width: calc(var(--length) / 6);
  height: calc(var(--length) / 6);
  border-radius: calc(var(--length) / 6);
  background: #6c757d;
}
.active .point {
  animation: point 3s linear infinite;
}
@keyframes point {
  0% {
    transform: scale(1.4);
    background: orangered;
  }
  40% {
    background: orange;
  }
  70% {
    transform: scale(1);
    background-color: rgb(0, 89, 255);
  }
  100% {
    transform: scale(1.4);

    background: orangered;
  }
}
.one > .cube {
  transform: translateY(-100px) rotateX(720deg);
}
.two > .cube {
  transform: translateY(-100px) rotateX(720deg) rotateY(270deg);
}
.three > .cube {
  transform: translateY(-100px) rotateX(810deg);
}
.four > .cube {
  transform: translateY(-100px) rotateX(990deg);
}
.five > .cube {
  transform: translateY(-100px) rotateX(720deg) rotateY(90deg);
}
.six > .cube {
  transform: translateY(-100px) rotateX(900deg);
}
.cube-animation.animation {
  animation: roll-z 2s linear;
}
/* .cube-animation.animation.delayed,
.cube-animation.animation.delayed > .cube {
  animation-delay: 0.3s !important;
} */
.cube-animation.hide > .cube {
  display: none;
}
.cube-animation.animation.one > .cube {
  animation: roll-rotate-one 2s linear;
}
.cube-animation.animation.two > .cube {
  animation: roll-rotate-two 2s linear;
}
.cube-animation.animation.three > .cube {
  animation: roll-rotate-three 2s linear;
}
.cube-animation.animation.four > .cube {
  animation: roll-rotate-four 2s linear;
}
.cube-animation.animation.five > .cube {
  animation: roll-rotate-five 2s linear;
}
.cube-animation.animation.six > .cube {
  animation: roll-rotate-six 2s linear;
}
.cube-animation {
  width: 100px;
}
.z-10 {
  z-index: 10;
}
@keyframes cube {
  0% {
    transform: rotateX(0deg) rotateY(45deg) rotateZ(90deg);
  }
  100% {
    transform: rotateX(360deg) rotateY(405deg) rotateZ(450deg);
  }
}
@keyframes roll-z {
  0% {
    transform: translateX(50px) translateY(-80px) translateZ(900px);
    animation-timing-function: cubic-bezier(0.33333, 0, 0.66667, 0.33333);
  }
  30% {
    transform: translateZ(0px);
    animation-timing-function: cubic-bezier(0.33333, 0.66667, 0.66667, 1);
  }
  50% {
    transform: translateZ(0px);
    animation-timing-function: cubic-bezier(0.33333, 0, 0.66667, 0.33333);
  }
  70% {
    transform: translateZ(0px);
    animation-timing-function: cubic-bezier(0.33333, 0.66667, 0.66667, 1);
  }
  80% {
    transform: translateZ(0px);
    animation-timing-function: cubic-bezier(0.33333, 0, 0.66667, 0.33333);
  }
  90% {
    transform: translateZ(0px);
    animation-timing-function: cubic-bezier(0.33333, 0.66667, 0.66667, 1);
  }
  95% {
    transform: translateZ(0px);
    animation-timing-function: cubic-bezier(0.33333, 0, 0.66667, 0.33333);
  }
  100% {
    transform: translateZ(0px);
    animation-timing-function: cubic-bezier(0.33333, 0.66667, 0.66667, 1);
  }
}
@keyframes roll-rotate-one {
  0% {
    transform: translateY(100px) rotateX(0deg) rotateY(10deg) rotateZ(10deg);
  }
  100% {
    transform: translateY(-100px) rotateX(720deg);
  }
}
@keyframes roll-rotate-two {
  0% {
    transform: translateY(100px) rotateX(0deg) rotateY(280deg) rotateZ(10deg);
  }
  100% {
    transform: translateY(-100px) rotateX(720deg) rotateY(270deg);
  }
}
@keyframes roll-rotate-three {
  0% {
    transform: translateY(100px) rotateX(90deg) rotateY(10deg) rotateZ(10deg);
  }
  100% {
    transform: translateY(-100px) rotateX(830deg);
  }
}
@keyframes roll-rotate-four {
  0% {
    transform: translateY(100px) rotateX(270deg) rotateY(10deg) rotateZ(10deg);
  }
  100% {
    transform: translateY(-100px) rotateX(990deg);
  }
}
@keyframes roll-rotate-five {
  0% {
    transform: translateY(100px) rotateX(0deg) rotateY(100deg) rotateZ(10deg);
  }
  100% {
    transform: translateY(-100px) rotateX(720deg) rotateY(90deg);
  }
}
@keyframes roll-rotate-six {
  0% {
    transform: translateY(100px) rotateX(180deg) rotateY(10deg) rotateZ(10deg);
  }
  100% {
    transform: translateY(-100px) rotateX(900deg);
  }
}
</style>