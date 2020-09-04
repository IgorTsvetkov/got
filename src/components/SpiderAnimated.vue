<template>
  <div>
    <!-- //////////////// SHADOW //////////////// -->
    <div class="spider-web shadow"></div>
    <div class="container-spider shadow">
      <div class="arm-container-spider right">
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
      </div>
      <div class="arm-container-spider left">
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
      </div>
      <div class="spider-body shadow-body">
        <div class="eye eye-left"></div>
        <div class="eye eye-right"></div>
      </div>
    </div>

    <!-- //////////////// SPIDER //////////////// -->
    <div class="spider-web"></div>
    <div class="container-spider">
      <div class="arm-container-spider right">
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
      </div>
      <div class="arm-container-spider left">
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
        <div class="arm A">
          <div class="arm B">
            <div class="arm C"></div>
          </div>
        </div>
      </div>
      <div class="spider-body">
        <div class="eye eye-left"></div>
        <div class="eye eye-right"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
/*/////////////////////// spider ////*/
.container-spider {
  animation: spider-anima 6s ease-out;
  animation-fill-mode: forwards;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
@keyframes spider-anima {
  0% {
    top: 0px;
  }
  50% {
    top: 50%;
  }
}
.container-spider .eye {
  background-color: #fff;
  height: 1px;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 1px;
}
.container-spider .eye-right {
  right: 6px;
}
.container-spider .eye-left {
  left: 6px;
}

.spider-web {
  animation: spider-web-anima 3s ease-out;
  animation-fill-mode: forwards;
  background-color: #000;
  border-radius: 4px;
  height: 0;
  left: 50%;
  position: absolute;
  transform: translateX(-50%);
  width: 2px;
}
@keyframes spider-web-anima {
  0% {
    height: 0;
    top: 0;
  }
  100% {
    height: 50%;
    top: 0;
  }
}

.spider-body {
  background-color: #000;
  border-radius: 50%;
  height: 20px;
  left: 50%;
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 20px;
}
.spider-body:before {
  content: "";
  background-color: #000;
  border-radius: 50%;
  height: 15px;
  left: 2.5px;
  position: absolute;
  top: -5px;
  width: 15px;
  z-index: -99;
}

.right {
  transform: rotateY(180deg);
}

.arm-container-spider .arm {
  background-color: #000;
  border-radius: 4px;
  height: 4px;
  position: absolute;
  transform-origin: center left;
  width: 30px;
}
.arm-container-spider .A {
  animation: A-anima 3s infinite ease-in-out;
  transform: rotate(75deg);
}
@keyframes A-anima {
  0% {
    transform: rotate(75deg);
  }
  50% {
    transform: rotate(-70deg);
  }
}
.arm-container-spider .A:nth-child(2) {
  animation-delay: -0.2s;
}
.arm-container-spider .A:nth-child(3) {
  animation-delay: -0.5s;
}
.arm-container-spider .A:nth-child(4) {
  animation-delay: -0.4s;
}
.arm-container-spider .B {
  animation: B-anima 3s infinite ease-in-out;
  left: calc(100% - 2px);
  transform: rotate(10deg);
}
@keyframes B-anima {
  0% {
    transform: rotate(10deg);
  }
  50% {
    transform: rotate(120deg);
  }
  80% {
    transform: rotate(20deg);
  }
}
.arm-container-spider .C {
  animation: C-anima 3s infinite ease-in-out;
  left: calc(100% - 2px);
  transform: rotate(10deg);
}
@keyframes C-anima {
  0% {
    transform: rotate(10deg);
  }
  50% {
    transform: rotate(90deg);
  }
  80% {
    transform: rotate(10deg);
  }
}

/*/////////////////////// shadow ////*/
.shadow {
  left: calc(50% + 10px);
  background-color: rgba(45, 45, 45, 0.5);
}

.shadow .arm {
  background-color: rgba(45, 45, 45, 0.5);
}

.shadow-body {
  background-color: rgba(45, 45, 45, 0.5);
}
.shadow-body:before {
  background-color: rgba(45, 45, 45, 0.5);
}
.shadow-body .eye {
  display: none;
}
.pause-all,.pause-all>*,.pause-all *,.pause-all>*>*,.pause-all>*>*{
  animation-delay: 5s;
  animation-play-state: paused!important;
}
</style>