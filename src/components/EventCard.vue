<template>
  <div class="w-100 h-100 d-flex align-items-center justify-content-center">
    <div
      class="event-card-wrapper card-wrapper-small"
      :class="{'card-wrapper-normal':eventData}"
      @click="pickCard"
    >
      <div class="event-card-external btn btn-secondary rounded" :class="{'rotate':isRotated}">
        <div class="event-card-inner bg-dark w-100 h-100 position-relative">
          <div
            class="card-back position-absolute w-100 h-100 d-flex align-items-center justify-content-between"
          >
            <div class="d-flex flex-column h-100 justify-content-around">
              <div v-if="eventData" class="d-flex flex-column">
                <div>
                  <img src="/web/images/GOT.svg" class="w-50 img-GOT" />
                </div>
                <div v-if="eventData.operation=='earn'">
                  <text-with-money :text="eventData.text"></text-with-money>
                  <div v-if="!is_readonly" class="btn btn-success m-2" @click="doEvent">
                    Забрать {{eventData.money}}
                    <text-with-money text="$"></text-with-money>
                  </div>
                  <div v-else>
                    <span>Выполено</span>
                  </div>
                </div>
                <div v-if="eventData.operation=='rollAndPayMultiply10'">
                  <text-with-money :text="eventData.text"></text-with-money>
                  <div v-if="dice_rolled">
                    <div class="btn btn-success m-2" @click="doEvent">Заплатить</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-front position-absolute bg-dark w-100 h-100">
            <!-- <img :src="event.src" class="card-img position-absolute full" /> -->
            <div>
              <spider-animated class="spider" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SpiderAnimated from "./SpiderAnimated.vue";
import TextWithMoney from "./TextWithMoney.vue";
export default {
  components: {
    SpiderAnimated,
    TextWithMoney,
  },
  props: {
    event: {
      type: Object,
      default: null,
    },
    is_readonly: {
      type: Boolean,
      default: false,
    },
    current_event_id: {
      type: Number,
      default: undefined,
    },
    dice_rolled:{
      type:Boolean,
      default:false
    }
  },
  data() {
    return {
      isRotated: false,
      eventData: undefined,
    };
  },
  async created() {
    if (this.current_event_id)
      this.eventData = await this.getEvent("spyder", this.current_event_id);
  },
  methods: {
    async pickCard() {
      if (this.eventData) {
        this.rotate();
        return;
      }
      let result = await this.$axios.get("/event/random?type=spyder");
      this.eventData = result.data.data.event;

      if (this.$response.getAction(result)) {
        this.$emit("turnStatusUpdate", result);
      }
    },
    async getEvent(type, id) {
      let result = await this.$axios.get(
        `/event/view?type=${type}&id=${this.current_event_id}`
      );
      console.log("result :>> ", result);
      //roll dice allow
      if (this.$response.getAction(result)) {
        this.$emit("turnStatusUpdate", result);
        return result.data.data.event;
      }
      return result.data;
    },
    rotate() {
      this.isRotated = true;
    },
    async doEvent() {
      let result = await this.$axios.post(
        `/event/do?type=spyder&id=${this.eventData.id}`
      );
      this.$emit("eventDone", result);
    },
  },
  computed: {
    eventText() {
      return this.eventData.text;
    },
  },
};
</script>
<style scoped>
.img-GOT {
  clip-path: polygon(0 32%, 100% 33%, 100% 67%, 0 68%);
}
.rotate .spider {
  visibility: hidden;
  transition: visibility 0.3s linear;
}
.card-img {
  left: 0px;
  top: 0px;
}
.full {
  height: -moz-available; /* WebKit-based browsers will ignore this. */
  height: -webkit-fill-available; /* Mozilla-based browsers will ignore this. */
  height: fill-available;
}
.card-wrapper-small {
  transform: scale(0.5);
}
.card-wrapper-normal {
  transform: scale(1);
  transition: transform 2s cubic-bezier(0.075, 0.82, 0.165, 1);
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
}
.external-animation {
  animation: card 3s linear infinite;
}
.event-card-external.rotate {
  transform: rotateY(180deg);
  animation: card-rotate 2s ease-in-out;
}
.event-card-external:hover {
  box-shadow: 0 0 20px 10px #9e9e9e;
}
.card-front {
  transform: translateZ(1px);
}
.card-back {
  top: 0;
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