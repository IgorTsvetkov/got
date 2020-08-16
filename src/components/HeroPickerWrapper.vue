<template>
    <div>
        <slot></slot>
        <div v-if="error" class="lead bg-warning text-light border shadow  p-20">{{error.message}}</div>
    </div>
</template>

<script>
import AuthSocket from "../js/AuthSocket";
import axios from "axios";
export default {
  props: {
    name: {
      type: String,
      default: "field_name",
    },
    users_string: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      imgs: [
        { src: "figure1.png", name: "Faceless men" },
        { src: "figure1.png", name: "Stannis Baratheon" },
        { src: "figure2.png", name: "Robert Baratheon" },
        { src: "figure3.png", name: "Daenerys Targaryen" },
        { src: "figure4.png", name: "Tyrion Lannister" },
        { src: "figure5.png", name: "Jaime Lannister" },
        { src: "figure6.png", name: "Robb Stark" },
        { src: "figure7.png", name: "Jon Show" },
        { src: "figure8.png", name: "Cersei Lannister" },
      ],
      users: {},
      error: null,
      socket: new AuthSocket("ws://127.0.0.1:8989/send-to-all"),
    };
  },
  methods: {
    changeSlot(value) {
      // this.socket.send({slot:value});
      let result = axios.post(`/match/change-slot?slot=${value}`);
      if (result.error) {
        this.error = result.error;
        return;
      }
      this.error = null;
      this.socket.send({ refresh: true });
    },
    usersStringToObj(users_string) {
      let users = JSON.parse(users_string);
      return this.usersTransform(users);
    },
    usersTransform(users) {
      let obj = {};
      users.forEach((item, key) => (obj[item.slot] = item.username));
      return obj;
    },
  },
  created() {
    this.users = this.usersStringToObj(this.users_string);

    this.socket.onmessageAuth = (e, res) => {
      console.log(res);
      if (res.refresh)
        axios
          .get("/match/connect?isJson=true")
          .then((res) => {
            console.log(res);
          })
          .catch((res) => console.log("Error REFRESH :>> ", res));
      if (res.data && res.data.users)
        this.users = this.usersTransform(res.data.users);
    };
  },
};
</script>

<style lang="scss" scoped>

</style>