<template>
  <div>
    <a class="btn btn-danger" @click.prevent="leave"><slot>&times;</slot></a>
  </div>
</template>

<script>
export default {
  props: {
    game_id: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      socket: undefined,
    };
  },
  beforeMount() {
    this.socket = this.$socketGet(this.game_id,"send-local-to-all");
  },
  methods: {
    async leave(e) {
      let result = await this.$axios.post(
        `/match/leave?game_id=${+this.game_id}`
      );
      if (result) {
        if (this.$response.hasError(result)) {
            throw new Error("leave match error");
            return;
        }
        this.socket.send(result);
        this.$emit("leave");
        if (this.$response.isRedirect(result)) {
          this.$response.Redirect(result);
        }
        return;
      }
    },
    // refreshPageEveryone(e) {
    //   if (e && e.data && e.data.error) {
    //     this.error = e.data.error;
    //     return;
    //   }
    //   this.error = null;
    //   this.socket.send({ action: "refresh" });
    // },
  },
};
</script>

<style lang="scss" scoped>
</style>