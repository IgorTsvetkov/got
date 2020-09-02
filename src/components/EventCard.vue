<template>
  <div class="w-100 h-100 d-flex align-items-center justify-content-center">
    <div class="event-card-wrapper"  @click="getEventRandom">
      <div class="event-card-external btn btn-dark rounded" :class="{'rotate':isRotated}">
        <div class="event-card-inner bg-secondary position-relative">
          <div class="card-front">
            <img :src="event.src" class="card-img position-absolute full" />
          </div>
          <div class="card-back position-absolute">
            <div>123111111111111</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    event: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isRotated: false,
    };
  },
  methods: {
    async getEventRandom(){
      let result = await this.$axios.get("/event/random?type=spider");
      this.rotate(true);
    },
    rotate() {
      this.isRotated = true;
    },
  },
};
</script>
<style scoped>
.card-img {
  left: 0px;
  top: 0px;
}
.full {
  height: -moz-available; /* WebKit-based browsers will ignore this. */
  height: -webkit-fill-available; /* Mozilla-based browsers will ignore this. */
  height: fill-available;
}
.event-card-wrapper {
  cursor: pointer;
  perspective: 400px;
}
.event-card-external {
  width: 14vw;
  height: 21vw;
  transform-style: preserve-3d;
  padding: 1vw;
  transform: rotateY(0deg);

  animation: card 3s linear infinite;
}
.event-card-external.rotate{
  transform: rotateY(180deg);
  animation: card-rotate 2s ease-in-out;
}
.event-card-external:hover{
    box-shadow: 0 0 20px 10px #9E9E9E;
}
.card-front {
  background: red;
  transform: translateZ(1px);
}
.card-back {
  top: 0;
  background: green;
  width: 100%;
  height: 100%;
  transform: translateZ(-1px) rotateY(180deg);
}
.p-1_5-vh {
  padding: 1.5vh;
}
@keyframes card {
  0% {
    transform: rotateY(0deg);
  }
  25% {
    transform: rotateY(-3deg);
  }
  50% {
    transform: rotateY(0deg);
  }
  75% {
    transform: rotateY(3deg);
  }
  100% {
    transform: rotateY(0deg);
  }
}
@keyframes card-rotate {
  0% {
    transform: rotateY(0deg);
  }
  40% {
    transform: rotateY(200deg);
  }
  60% {
    transform: rotateY(160deg);
  }
  80% {
    transform: rotateY(190deg);
  }
  100% {
    transform: rotateY(180deg);
  }
}
</style>