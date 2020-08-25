<template>
  <div>
    <div class="bg-light text-dark h-vh w-100 d-flex flex-column-reverse overflow-auto">
      <hr />
      <div class="pl-1" v-for="message in messages" :key="message.id">
        <div class="d-flex justify-content-end">
          <span class="h6 text-secondary font-weight-light py-1">{{message.time}}</span>
        </div>
        <div v-if="message.from">
          <span class="h6 text-primary p-0 m-0">
            {{message.from}}
            <span class="p-0 m-0" v-if="message.from==from">[Вы]</span>
          </span>
          <img :src="from_img" width="35px" class="text-wrap" />
          :{{ message.message }}
        </div>
        <div v-if="!message.from">
          <span class="text-danger">{{ message.message }}</span>
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
      console.log("parsedData :>> ", parsedData);
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