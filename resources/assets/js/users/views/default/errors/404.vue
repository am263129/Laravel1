<template>
  <div>
      <div class="col-12 h-100">
      <div class="not-found text-center ">
          <h1> <strong style="color:#00BCD4;"> 4 </strong> 0 <strong style="color:#00BCD4;"> 4 </strong></h1>
        <router-link role="button" class="btn btn-lg btn-primary" :to="{name: 'discover'}">BACK TO HOME</router-link>
      </div>
      </div>
  </div>
</template>

<style>
.not-found {
    position: absolute;
    top: 25%;
    left: 50%;
    -webkit-transform: translate(-50%);
    transform: translate(-50%);
}
.not-found  h1 {
    font-size: 25vh;
    font-weight: bold;

}
.btn-primary, .btn-primary:hover, .btn-primary:focus, .btn-primary.active {
    color: #fff;
    background-color: #03A9F4;
    background-image: none;
    border-color: #03A9F4;
}
</style>
