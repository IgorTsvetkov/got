<template>
  <div class="draggable">
    <div class="bg-light text-dark h-vh w-100 d-flex flex-column-reverse overflow-auto">
      <hr />
      <div class="pl-1" v-for="message in messages" :key="message.id">
        <div class="d-flex justify-content-end">
          <span class="h6 text-secondary font-weight-light py-1">{{message.time}}</span>
        </div>
        <div v-if="message.from">
          <span :style="{color:'color'}" class="h6 p-0 m-0">
            {{message.from}}
            <span class="p-0 m-0" v-if="message.from==from">[Вы]</span>
          </span>
          <img :src="message.from_img" width="35px" class="text-wrap" />
          :{{ message.message }}
        </div>
        <div v-if="!message.from">
          <span v-html="message.message"></span>
        </div>
      </div>
    </div>
    <div class="container-fluid mt-1">
      <div class="row">
        <input
          class="form-control col-8"
          v-model="message"
          type="text"
          @keypress.enter="sendMessage"
        />
        <input
          class="col-4 m-0 btn btn-success text-truncate"
          value="Отправить"
          type="button"
          @click="sendMessage"
          :disabled="message.length==0"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    from: {
      type: String,
      default: "anonymous",
    },
    from_img: {
      type: String,
      default: undefined,
    },
    game_id: {
      type: Number,
      default: undefined,
    },
    color:{
      type:String,
      default:"#000000"
    }
  },
  data() {
    return {
      socket: undefined,
      message: "",
      messages: [],
    };
  },
  beforeMount() {
    this.socket = this.$socketGet(this.game_id, "send-local-to-all");
    this.socket.addMessageCallback((e, parsedData) => {
      if (parsedData.action && parsedData.action == "chat") {
        this.messages.unshift(parsedData.data);
      }
      if (parsedData.systemMessage) {
        this.messages.unshift(parsedData.systemMessage);
      }
    });
  },
  methods: {
    sendMessage() {
      this.socket.send({
        action: "chat",
        data: {
          from: this.from,
          message: this.message,
          from_img:this.from_img,
          time: new Date().toLocaleTimeString(),
        },
      });
      this.message = "";
    },
  },
};
</script>

<style scoped>
/*need */
.h-vh {
  height: 24vw;
}
</style>